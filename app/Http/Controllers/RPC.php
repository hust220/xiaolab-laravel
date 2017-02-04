<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

require_once("utils.php");

class RPC extends Controller {
    const WORK_PATH = "/home/xiaolab/server/3dRPC/";
    const RESULT_PATH = self::WORK_PATH."results/";

    public function index(Request $request) {
        return view('3dRPC.index', inf_visit($request,'hyy'));
    }

    public function submit(Request $request) {
        $errors=[];
        $input = request()->all();
        if (!$request->hasFile('protein')) {
            $errors['protein'] = "The protein structure is required!";
        }

        if (!$request->hasFile('rna')) {
            $errors['rna'] = "The rna structure is required!";
        } 
        
        if ($input['num'] == "" ) {
            $errors['num'] = "The number of predictions is required!";
        } 


        if ($input['num'] > 100 ) {
            $errors['num'] = "The number of predictions should be less than 100!";
        } 


        
        $sql = DB::connection('hyy')->table('jobs');
        $num_running_tasks = $sql->where('ip', '=', $request->ip())->where('state', '<>', '')->where('state', '=', 'calculation')->count();
        if ($num_running_tasks >= 5 )
        return response()->json(["status"=>"failed", "errors"=>["tasks"=>"You already have $num_running_tasks tasks running! Please wait for the number of your running tasks being less than 5!"]], 420);
        
        $num_running_tasks = $sql->where('state', '<>', '')->where('state', '=', 'calculation')->count();
        if ($num_running_tasks >= 8 )
        return response()->json(["status"=>"failed", "errors"=>["tasks"=>"The service is busy now! Please submit your job later!"]], 420);
 

        if (count($errors) != 0) {
            return response()->json(["status"=>"failed", "errors"=>$errors], 420);
        }


        $request->file('protein')->move('/home/xiaolab/server/3dRPC/results/', 'protein.pdb');
        $request->file('rna')->move('/home/xiaolab/server/3dRPC/results/', 'rna.pdb');
        $job = rand(1, 999999999);
        $input['job'] = $job ;
        $input['ip'] = $request->ip();

        $this->save_task($input); 
        $this->calculation($input);
        return response()->json([ "status"=>"ok", "task_id"=>$job], 200);
        }
            function calculation ( $info ) {
                $command='bash /home/xiaolab/server/3dRPC/results/work.sh  '.$info['job'].' '.$info['num'].' '.$info['sf'].' '.$info['email'].'>/dev/null 2>&1 & ';
                system($command);
            }

            public function result(Request $request, $job) {
            return view('3dRPC.result', inf_visit($request,'hyy') + ['task'=>json_encode($this->task_result($job))]);
                    }


        function save_task($input) {
        DB::connection('hyy')->insert(
        'insert into jobs(num,email,ip,submit_time,inf) values(?,?,?,unix_timestamp(),?)',
        [$input['job'],$input['email'],$input['ip'],json_encode($input)]
          );

        }

        public function running_tasks(Request $request, $ip) {
                $result = DB::connection('hyy')->table('jobs')->where('ip', $ip)->get();
                        return response()->json($result, 200);
        }

        function task_result($job) {
            $result = json_decode(DB::connection('hyy')->table('jobs')->where('num', $job)->first()->inf, true);
            $pars = ['job'=>$job];
            $result_path = self::RESULT_PATH."/$job";
            $pars += $this->task_state($job);
            if ($pars["state"] == "finished") {
                $pars['scores'] = $this->get_column_file("$result_path/scoring.txt", 1);
            }
            return $pars + $result;
        }

        function task_state($job) {
            $result_path = self::RESULT_PATH."/$job";
            $result = [];
            exec("/home/xiaolab/server/3dRPC/results/state.sh $job", $result);
            return [
                "state"=>end($result),
            ];
        }

    public function monitor(Request $request, $job) {
                return response()->json($this->task_state($job), 200);
                    }
    public function tasks(Request $request, $job) {
                return response()->json($this->task_result($job), 200);
                    }

    public function download(Request $request, $job, $num) {
        $addr = self::RESULT_PATH.$job.'/';
        if ($num == "all") {
            $addr .= $job.'.tar.gz';
            } else if ($num=="p") {
            $addr .= "protein.pdb";
            } else if ($num=="r") {
            $addr .= "rna.pdb";
        } else $addr .= "3drpc-complex".$num.".pdb";
            return response()->download($addr);
        } 

    public function view(Request $request, $job, $index) {
        $result = json_decode(DB::connection('hyy')->table('jobs')->where('num', $job)->first()->inf, true);
        $name = $job."-".$index;
        return view('3dRPC/view', inf_visit($request,'hyy') + $result + ['name'=>$name, 'index'=>$index]);
    }

    function get_column_file($path, $n) {
        $results = [];
        $f = fopen($path, 'r');
        while (!feof($f)) {
            $line = preg_split("/\s+/", trim(fgets($f)));
            if (count($line) > $n) $results[] = $line[$n];
        }
        fclose($f);
        return $results;

    }

   public function query(Request $request) {
            $input = $request->all();
        if (!isset($input['query']) || $input['query'] == '') return response()->json(['errors'=>['Please input the content of your query!']], 420);
        $content = $input['query'];
        $result = '';
        $sql = DB::connection('hyy')->table('jobs')->where('inf', '<>', '');
        if (is_ip($content)) {
                $result = $sql->where('ip', $content)->get();
        } else if (is_email($content)) {
                $result = $sql->where('email', $content)->get();
        } else if (is_num($content)) {
                $result = $sql->where('num', $content)->get();
        } else if ($content == "all") {
                $result = $sql->select('num', 'ip', 'email', 'submit_time', 'state')->get();
        }
        if ($result == '' || count($result) == 0) {
                return response()->json(['errors'=>['Not Found!']], 420);
        } else {
                return response()->json(['results'=>$result], 200);
        }
    }


}

