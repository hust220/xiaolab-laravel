<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

require_once("utils.php");

class RNA3D extends Controller {
    const WORK_PATH = "/home/wangjian/server/nsp-server/";
    const RESULT_PATH = self::WORK_PATH."results/";
    const LOG_PATH = self::WORK_PATH."logs/";
    const UPLOAD_PATH = "/home/wangjian/upload/";

    public function assemble(Request $request) {
        return $this->base($request, "assemble");
    }

    public function dg(Request $request) {
        return $this->base($request, "dg");
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
        if (count($check_result) != 0) {
            return response()->json(["status"=>"failed", "errors"=>$check_result], 420);
        } else {
            $job = rand(1, 999999999);
            $input['job'] = $job;
            $input['ip'] = $request->ip();

            $this->save_task($input);
            $this->pred_3d($input);
            return response()->json(["status"=>"ok", "task_id"=>$job], 200);
        }
    }

    public function monitor(Request $request, $job) {
        return response()->json($this->task_state($job), 200);
    }

    public function redo(Request $request, $job) {
        $result = json_decode(DB::table('jobs')->where('num', $job)->first()->inf, true);
        $this->pred_3d($result);
        sleep(3);
        return $this->result($request, $job);
    }

    public function jobs(Request $request) {
        $input = $request->all();
        $input['ip'] = $request->ip();
        $items = ["num", "email", "ip", "submit_time"];
        $names = ["num" => "Job ID", "email" => "Email", "ip" => "IP", 
                  "submit_time" => "Submit Time"];
        $query = $request->input('query');
        $query_sql = DB::table('jobs')->select('num', 'email', 'ip', 'submit_time');
        if (!is_null($query)) {
            if (preg_match('/^\d+$/', $query)) {
                $query_sql = $query_sql->where('num', $query);
            } else if (preg_match('/^.+@.+$/', $query)) {
                $query_sql = $query_sql->where('email', $query);
            } else if (preg_match('/^\d+\.\d+\.\d+\.\d+$/', $query)) {
                $query_sql = $query_sql->where('ip', $query);
                $items = ["num", "email", "ip", "submit_time"];
            } else if (preg_match('/^all$/', $query)){
                $items = ["num", "email", "ip", "submit_time"];
            } else {
                $items = [];
                return view('3dRNA/jobs', inf_visit($request));
            }
        }
        $query_result = $query_sql->orderBy('id', 'desc')->get();
        $result = [];
        foreach ($query_result as $line) {
            $result[] = ['num'=>($line->num), 'email'=>($line->email), 'ip'=>($line->ip),
                         'submit_time'=>($line->submit_time)];
        }
        return view('3dRNA/jobs', inf_visit($request) + ['names'=>$names, 'items'=>$items, 'result'=>$result]);
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

    public function download(Request $request, $job, $num) {
        $addr = self::RESULT_PATH.$job.'/';
        if ($num == "all") {
            $addr .= $job.'.tar.gz';
        } else $addr .= $job.'.3drna.'.$num.".pdb";
        return response()->download($addr);
    }

    function pred_3d($info) {
        $this->save_constraints($info);
        $this->save_job_info($info);
        $command = 'bash /home/wangjian/server/nsp-server/submit.sh '.$info['job'].' >/dev/null 2>&1 &';
        system($command);
    }

    function task_result($job) {
        $result = json_decode(DB::table('jobs')->where('num', $job)->first()->inf, true);
        $pars = ['job'=>$job];
        $result_path = self::RESULT_PATH."/$job";
        $pars += $this->task_state($job);
        if ($pars["state"] == "finished") {
            $pars['scores'] = $this->get_column_file("$result_path/scores.txt", 1);
        } /*else {
            $log_file = self::LOG_PATH.$job.".log";
            if (file_exists($log_file)) {
                $pars['status'] = implode("<br>", file($log_file));
            }
        }*/
        return $pars + $result;
    }

    function base(Request $request, $method) {
        return view('3dRNA.index')->with(inf_visit($request) + ['method' => $method]);
    }

    function save_constraints($info) {
        if ($info['constraints'] != '') {
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

    function check_input($input) {
        $result = [];
        foreach (["seq", "ss", "num", "num_sampling", "seed"] as $el) {
            if ($input[$el] == "") {
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

