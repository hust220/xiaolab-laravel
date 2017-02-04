<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

require_once("utils.php");
require_once("jntask.php");

class RNA3D extends Controller {
    const WORK_PATH = "/home/wangjian/server/nsp-server/";
    const RESULT_PATH = self::WORK_PATH."results/";
    const LOG_PATH = self::WORK_PATH."logs/";
    const UPLOAD_PATH = "/home/wangjian/upload/";
    const PAPER_PATH = "/var/www/xiao-lab/public/download/";

    public function assemble(Request $request) {
        return $this->base($request, "nat_struct");
    }

    public function dg(Request $request) {
        return $this->base($request, "DG");
    }

    public function dna(Request $request) {
        return view('3dRNA.dna', inf_visit($request));
    }

    public function duplex_2d(Request $request, $seq) {
        $arr = [];
        exec("/home/wangjian/server/nsp-server/duplex_2d.sh $seq", $arr);
        return $arr[1];
    }

    public function triplex_2d(Request $request, $seq) {
        $arr = [];
        exec("/home/wangjian/server/nsp-server/triplex_2d.sh $seq", $arr);
        if (count($arr) > 0) {
            return preg_split("/\s+/", $arr[0])[0];
        } else {
            return '';
        }
    }

    public function submit(Request $request) {
        $input = $request->all(); 
        $check_result = $this->check_input($input);
//        $num_running_tasks = DB::table('jobs')->where('ip', '=', $request->ip())->where('state', '<>', '')->where('state', '<>', 'finished')->count();
        if (count($check_result) != 0) {
            return response()->json(["status"=>"failed", "errors"=>$check_result], 420);
//        } else if ($num_running_tasks >= 10) {
//            return response()->json(["status"=>"failed", "errors"=>["tasks"=>"You already have $num_running_tasks tasks running! Please wait for the number of your running tasks being less than 10!"]], 420);
        } else {
            $job = rand(1, 999999999);
            $input['job'] = $job;
            $input['ip'] = $request->ip();
            $this->pred_3d($input);
            $this->save_task($input);
            return response()->json(["status"=>"ok", "task_id"=>$job], 200);
        }
    }

    public function monitor(Request $request, $job) {
        return response()->json($this->task_state($job), 200);
    }

    public function running_tasks(Request $request, $ip) {
        $result = DB::table('jobs')->where('ip', $ip)->where('inf', '<>', '')->get();
        return response()->json($result, 200);
    }

    public function redo(Request $request, $job) {
        $result = json_decode(DB::table('jobs')->where('num', $job)->first()->inf, true);
        $this->pred_3d($result);
        sleep(3);
        return $this->result($request, $job);
    }

    public function query(Request $request) {
        $input = $request->all();
        if (!isset($input['query']) || $input['query'] == '') return response()->json(['errors'=>['Please input the content of your query!']], 420);
        $content = $input['query'];
        $result = '';
        $sql = DB::table('jobs')->where('inf', '<>', '');
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

    public function result(Request $request, $job) {
        return view('3dRNA.result', inf_visit($request) + ['task'=>json_encode($this->task_result($job))]);
    }

    public function tasks(Request $request, $job) {
        return response()->json($this->task_result($job), 200);
    }

    public function references(Request $request) {
        return view('3dRNA.references', inf_visit($request));
    }

    public function view(Request $request, $job, $index) {
        $result = json_decode(DB::table('jobs')->where('num', $job)->first()->inf, true);
        $name = $job."-".$index;
        return view('3dRNA/view', inf_visit($request) + $result + ['name'=>$name, 'index'=>$index]);
    }

    public function results(Request $request, $job) {
        $addr = self::RESULT_PATH.$job."/$job.tar.gz";
        return response()->download($addr);
    }

    public function test(Request $request, $paper, $set, $item, $test_type) {
        $state = [];
        $state["ss"] = file(self::PAPER_PATH.$paper."/".$set."/".$item.".ss", FILE_IGNORE_NEW_LINES)[0];
        if (strpos($test_type, "a")!==false) {
            $state["seq"] = file(self::PAPER_PATH.$paper."/".$set."/".$item.".seq", FILE_IGNORE_NEW_LINES)[0];
        } else {
            $handle = fopen(self::PAPER_PATH.$paper."/".$set."/".$item.".pdb", 'r');
            $state["init_struct"] = stream_get_contents($handle);
            fclose($handle);
        }
        if (strpos($test_type, "c")!==false) {
            $state["constraints"] = implode("\n", file(self::PAPER_PATH.$paper."/".$set."/".$item.".rm_fp.di", FILE_IGNORE_NEW_LINES));
        }
        $state["task_type"] = str_replace("c", "", $test_type);
        return view('3dRNA.index', inf_visit($request) + ['method' => 'nat_struct', 'ip'=>$request->ip(), 'init_state'=>json_encode($state)]);
    }

    public function download(Request $request, $job, $num) {
        $addr = self::RESULT_PATH.$job.'/';
        if ($num == "all") {
            $addr .= $job.'.tar.gz';
        } else $addr .= $job.'.'.$num.".pred.pdb";
        return response()->download($addr);
    }

    function pred_3d($info) {
        $this->save_constraints($info);
        $this->save_job_info($info);
        #$command = 'bash /home/wangjian/server/nsp-server/submit.sh '.$info['job'].' >/dev/null 2>&1 &';
        #system($command);
        $command = 'bash /home/wangjian/server/nsp-server/submit.sh '.$info['job'];
        return jntask($command);
    }

    function task_result($job) {
        $result = json_decode(DB::table('jobs')->where('num', $job)->first()->inf, true);
        $pars = ['job'=>$job];
        $result_path = self::RESULT_PATH."/$job";
        $pars += $this->task_state($job);
        if ($pars["state"] == "finished") {
            $pars['scores'] = $this->get_column_file("$result_path/scores.txt", 1);
        }
        return $pars + $result;
    }

    function base(Request $request, $method) {
        return view('3dRNA.index')->with(inf_visit($request) + ['method' => $method, 'ip'=>$request->ip()]);
    }

    function save_constraints($info) {
        if (array_key_exists('constraints', $info) && $info['constraints'] != '') {
            $arr = preg_split("/\s+/", trim($info['constraints']));
            $f = fopen(self::WORK_PATH."jobs/".$info['job'].".constraints", 'w');
            for ($i = 0; $i < count($arr); $i += 2) {
                fwrite($f, $arr[$i]);
                fwrite($f, ' ');
                fwrite($f, $arr[$i+1]);
                fwrite($f, "\n");
            }
            fclose($f);
        }
    }

    function save_init_struct($info) {
        if (array_key_exists('init_struct', $info) && $info['init_struct'] != '') {
            $arr = preg_split("/\s+/", trim($info['constraints']));
            $f = fopen(self::WORK_PATH."jobs/".$info['job'].".init.pdb", 'w');
            fwrite($f, $info['init_struct']);
            fclose($f);
        }
    }

    function save_job_info($info) {
        $info['constraints'] = $info['job'].".constraints";
        $f = fopen(self::WORK_PATH."jobs/".$info['job'].".par", 'w');
        foreach ($info as $key=>$value) {
            fwrite($f, $key);
            fwrite($f, ' ');
            fwrite($f, $value);
            fwrite($f, "\n");
        }
        fclose($f);
    }

    function task_state($job) {
        $result_path = self::RESULT_PATH."/$job";
        $result = [];
        exec("/home/wangjian/server/nsp-server/status.sh $job", $result);
        return [
            "state"=>end($result),
            "states"=>["ready", "predicting", "eliminating clash", "compressing results", "scoring", "finished"]
        ];
    }

    function save_task($input) {
        DB::insert(
            'insert into jobs(num, ip, email, submit_time, inf) values(?, ?, ?, unix_timestamp(), ?)',
            [$input['job'], $input['ip'], $input['email'], json_encode($input)]
        );
    }

    function jian(Request $request) {
        DB::connection('hyy')->insert(
            'insert into jobs(num) values(?)', [11]
        );
        $result = DB::connection('hyy')->select("select * from test");
        dd($result);
        return 'hi';
    }

    function check_input($input) {
        $result = [];
        foreach (["seq", "ss"] as $el) {
            if (!isset($input[$el]) || $input[$el] == "") {
                $result[$el] = "The $el field is required";
                return $result;
            }
        }
        $seq = $input['seq'];
        $ss = $input['ss'];
        $mol_type = $input['mol_type'];
        if ($mol_type == "RNA") {
            $result += $this->check_seq_ss($seq, $ss);
        }
        return $result;
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

    function save_pdbs($str, $name) {
        Storage::makeDirectory("jobs/$name");
        $n = 1;
        $line = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] != '$') $line .= $str[$i];
            else {
                $line = trim($line);
                if (substr($line, 0, 5) == 'MODEL') {
                    Storage::put("jobs/$name/$name-$n.pdb", $line."\n");
                } else if (substr($line, 0, 6) == 'ENDMDL') {
                    Storage::append("jobs/$name/$name-$n.pdb", $line."\n");
                    $n++;
                } else if (substr($line, 0, 4) == 'ATOM') {
                    Storage::append("jobs/$name/$name-$n.pdb", $line."\n");
                }
                $line = '';
            }
        }
    }

    function check_seq_ss(&$seq, &$ss) {
        $seq = preg_replace('/\s+/', '', $seq);
        $seq = strtoupper($seq);
        $ss = preg_replace('/\s+/', '', $ss);

        if (preg_match('/T/', $seq)) {
            return ['seq'=>'DNA would be supported later! Please input an RNA sequence'];
        }

        if (preg_match('/[^AUGC]/', $seq)) {
            return ['seq'=>'The sequence is not a standard RNA sequence.'];
        }

        if (strlen($seq) != strlen($ss)) {
            return ['ss'=>"The length of the sequence and the 2D structure should be equal!"];
        }

        /// only include ( ) [ ] .
        if (preg_match('/[^()\[\]&.]/', $ss)) {
            return ['ss'=>"The 2D structure should only include '(', ')', '[', ']', '.'."];
        }

        /// check the parentheses and brackets
        $left_paren = array();
        $left_bracket = array();
        $err_nuc = array();
        for ($i = 0; $i < strlen($ss); $i++) {
            if ($ss[$i] == '(') {
                array_push($left_paren, $i);
            } else if ($ss[$i] == '[') {
                array_push($left_bracket, $i);
            } else if ($ss[$i] == ')') {
                if (count($left_paren) == 0) {
                    array_push($err_nuc, $i);
                } else {
                    array_pop($left_paren);
                }
            } else if ($ss[$i] == ']') {
                if (count($left_bracket) == 0) {
                    array_push($err_nuc, $i);
                } else {
                    array_pop($left_bracket);
                }
            }
        }
        $err_nuc = array_merge($err_nuc, $left_paren, $left_bracket);
        if (count($err_nuc) != 0) {
            $err_inf = "Please check your 2D structure: ";
            for ($i = 0; $i < strlen($ss); $i++) {
                if (in_array($i, $err_nuc)) {
                    $err_inf = $err_inf."<font color='#FF0000'>".$ss[$i]."</font>";
                } else {
                    $err_inf = $err_inf.$ss[$i];
                }
            }
            $err_inf = $err_inf."<br />";
            return ['ss'=>$err_inf];
        } else {
            return [];
        }
    }


}

