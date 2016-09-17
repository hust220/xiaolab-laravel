<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CADNAS extends Controller {
//    const WORK_PATH = "/home/wangjian/server/nsp-server/";
//    const RESULT_PATH = "/home/wangjian/server/nsp-server/results/";

    function inf_visit() {
        $num_users = DB::table('jobs')->count('ip');
        $num_visitors = DB::table('visitors')->count('id');
        return ['num_users'=>$num_users, 'num_visitors'=>$num_visitors];
    }

    public function show(Request $request) {
        return view('CADNAS.show', $this->inf_visit());
    }

    public function submit(Request $request) {
        $items = ['type', 'name', 'length', 'seq'];
        $item_names = array("name" => "Name", "type" => "Type", "length" => "Length", "seq" => "Sequence");
        $query = DB::table('dnas')->select('name', 'type', 'length', 'seq')->where('id', '>', '0');
        foreach ($items as $name) {
            if ($request->has($name)) {
                $query->where(strval($name), strval($request->input($name)));
            }
        }
        if ($request->has('num')) {
            $query->take($request->input('num'));
        }
        $result = [];
        foreach ($query->get() as $row) {
            $result[] = (array)$row;
        }
        return response()->view('CADNAS.table', ['result' => $result, 'items' => $items, 'item_names' => $item_names]);
    }

//    public function base(Request $request, $method) {
//        $ip = $request->ip();
//        $url = $request->url();
//        DB::insert('insert into visitors (ip, visit_time, url) values (?, ?, ?)', [$ip, 'unix_timestamp()', $url]);
//
//        $num_users = DB::table('jobs')->count('ip');
//        $num_visitors = DB::table('visitors')->count('id');
//
//        return view('3dRNA.index', ['num_users' => $num_users, 'num_visitors' => $num_visitors, 'method' => $method]);
//    }
//
//    public function assemble(Request $request) {
//        return $this->base($request, "assemble");
//    }
//
//    public function dg(Request $request) {
//        return $this->base($request, "dg");
//    }
//
//    function pred_3d($job, $email, $seq, $ss, $pre_num, $method, $en_min, $compute_score, $compute_rmsd, $disused_pdb) {
//        exec(sprintf("%s/3drna.sh -email %s -seq %s -ss '%s' -job %d -num %s -method %s -en_min %s -compute_score %s -compute_rmsd %s -disused_pdb %s>%s/3drna.log 2>&1 &",
//                     self::WORK_PATH, $email, $seq, $ss, $job, $pre_num, $method, $en_min, $compute_score, $compute_rmsd, $disused_pdb, self::WORK_PATH));
//    }
//
//    public function submit(Request $request) {
//        $ip = $request->ip(); 
//        $job = rand(1, 999999999); 
//        $input = $request->all(); 
//        $pars = [$job, $ip];
//        $items = ['email', 'seq', 'ss', 'pre_num', 'method', 'disused_pdb', 'en_min', 'compute_score', 'compute_rmsd'];
//        foreach ($items as $item) {
//            if ($item == 'email' && $input[$item] == "") $pars[] = 'NULL'; else $pars[] = $input[$item];
//            if ($item == 'seq') $this->check_seq($input['seq']); if ($item == 'ss') $this->check_ss($input['ss']);
//        }
//        DB::insert('insert into jobs(num, ip, submit_time, email, seq, ss, pre_num, method, disused_pdb,
//                    en_min, compute_score, compute_rmsd) values(?, ?, unix_timestamp(), ?, ?, ?, ?, ?, ?, ?, ?, ?)', $pars);
//        if ($request->hasFile('native_structure')) $request->file('native_structure')->move(self::WORK_PATH, "native.pdb");
//        $this->pred_3d($job, $input['email'], $input['seq'], $input['ss'], $input['pre_num'], $input['method'], $input['en_min'], $input['compute_score'], $input['compute_rmsd'], $input['disused_pdb']);
//        return $this->result($request, $job);
//    }
//
//    public function redo(Request $request, $job) {
//        $result = DB::table('jobs')->select('email', 'seq', 'ss', 'pre_num', 'method', 'en_min', 'compute_score', 'compute_rmsd', 'disused_pdb')->where('num', $job)->first();
//        $email = $result->email; $seq = $result->seq; $ss = $result->ss; $pre_num = $result->pre_num; $disused_pdb = $result->disused_pdb;
//        $method = $result->method; $en_min = $result->en_min;
//        $compute_score = $result->compute_score; $compute_rmsd = $result->compute_rmsd;
//        $this->check_seq($seq); $this->check_ss($ss);
//        $native_path = self::WORK_PATH."/results/$job/native.pdb";
//        if (file_exists($native_path)) copy($native_path, self::WORK_PATH."/native.pdb");
//        $this->del_dir(self::WORK_PATH."/results/$job");
//        $this->pred_3d($job, $email, $seq, $ss, $pre_num, $method, $en_min, $compute_score, $compute_rmsd, $disused_pdb);
//        return $this->result($request, $job);
//    }
//
//    public function jobs(Request $request) {
//        $items = ["num", "email", "pre_num", "submit_time", "done_time"];
//        $names = ["num" => "Job ID", "email" => "Email", "ip" => "IP", "pre_num" => "Number", 
//                  "submit_time" => "Submit Time", "done_time" => "Finish Time"];
//        $query = $request->input('query');
//        $query_sql = DB::table('jobs')->select('num', 'email', 'ip', 'pre_num', 'submit_time', 'done_time');
//        if (!is_null($query)) {
//            if (preg_match('/^\d+$/', $query)) {
//                $query_sql = $query_sql->where('num', $query);
//            } else if (preg_match('/^.+@.+$/', $query)) {
//                $query_sql = $query_sql->where('email', $query);
//            } else if (preg_match('/^\d+\.\d+\.\d+\.\d+$/', $query)) {
//                $query_sql = $query_sql->where('ip', $query);
//                $items = ["num", "email", "ip", "pre_num", "submit_time", "done_time"];
//            } else if (preg_match('/^all$/', $query)){
//                $items = ["num", "email", "ip", "pre_num", "submit_time", "done_time"];
//            } else {
//                $items = [];
//                return view('3dRNA/jobs', $this->inf_visit());
//            }
//        }
//        $query_result = $query_sql->orderBy('id', 'desc')->get();
//        $result = [];
//        foreach ($query_result as $line) {
//            $result[] = ['num'=>($line->num), 'email'=>($line->email), 'ip'=>($line->ip),
//                         'pre_num'=>($line->pre_num), 'submit_time'=>($line->submit_time), 'done_time'=>($line->done_time)];
//        }
//        return view('3dRNA/jobs', $this->inf_visit() + ['names'=>$names, 'items'=>$items, 'result'=>$result]);
//    }
//
//    public function result(Request $request, $job) {
//        $result = DB::table('jobs')->where('num', $job)->first();
//        $num_completed = 0; $result_path = self::RESULT_PATH."/$job";
//        for ($i = 1; $i <= $result->num; $i++) {
//            if (file_exists("$result_path/$job-$i".($result->en_min == "yes" ? "-min" : "").".pdb")) $num_completed++; else break;
//        }
//        $pars = ['num_completed'=>$num_completed];
//        if ($result->compute_score == "yes" and file_exists("$result_path/scores.txt")) {
//            $pars['scores'] = $this->get_column_file("$result_path/scores.txt", 1);
//        } 
//        if ($result->compute_rmsd == "yes" and file_exists("$result_path/rmsds.txt")) {
//            $pars['rmsds'] = $this->get_column_file("$result_path/rmsds.txt", 0);
//        }
//        return view('3dRNA.result', $this->inf_visit() + $this->job_inf($result) + $pars);
//    }
//
//    function get_column_file($path, $n) {
//        $results = [];
//        $f = fopen($path, 'r');
//        while (!feof($f)) {
//            $line = preg_split("/\s+/", trim(fgets($f)));
//            if (count($line) > $n) $results[] = $line[$n];
//        }
//        fclose($f);
//        return $results;
//    }
//
//    function job_inf(&$result) {
//        return ['job'=>($result->num), 'num'=>($result->pre_num), 'email'=>($result->email), 'seq'=>($result->seq), 
//                'ss'=>($result->ss), 'method'=>($result->method), 'compute_score'=>($result->compute_score), 'compute_rmsd'=>($result->compute_rmsd),
//                'en_min'=>($result->en_min), 'submit_time'=>($result->submit_time), 'done_time'=>($result->done_time)];
//    }
//
//    public function view(Request $request, $job, $num) {
//        $num_users = DB::table('jobs')->count('ip');
//        $num_visitors = DB::table('visitors')->count('id');
//
//        $name = $job."-".$num;
//        $line = DB::table('jobs')->select('seq', 'ss')->where('num', $job)->first();
//        return view('3dRNA/view', ['num_users'=>$num_users, 'num_visitors'=>$num_visitors, 'name'=>$name,
//                                   'job'=>$job, 'num'=>$num, 'seq'=>($line->seq), 'ss'=>($line->ss)]);
//    }
//
//    public function download(Request $request, $job, $num) {
//        //$addr = storage_path("app/jobs/".$job."/".$job."-".$num.".pdb");
//        $result = DB::table('jobs')->where('num', $job)->first();
//        $addr = '/home/wangjian/server/nsp-server/results/'.$job.'/';
//        if ($num == "all") {
//            $addr .= $job.'.tar.gz';
//        } else $addr .= $job.'-'.$num.($result->en_min == "yes" ? "-min" : "").".pdb";
//        return response()->download($addr);
//    }
//
//    function save_pdbs($str, $name) {
//        Storage::makeDirectory("jobs/$name");
//        $n = 1;
//        $line = '';
//        for ($i = 0; $i < strlen($str); $i++) {
//            if ($str[$i] != '$') $line .= $str[$i];
//            else {
//                $line = trim($line);
//                if (substr($line, 0, 5) == 'MODEL') {
//                    Storage::put("jobs/$name/$name-$n.pdb", $line."\n");
//                } else if (substr($line, 0, 6) == 'ENDMDL') {
//                    Storage::append("jobs/$name/$name-$n.pdb", $line."\n");
//                    $n++;
//                } else if (substr($line, 0, 4) == 'ATOM') {
//                    Storage::append("jobs/$name/$name-$n.pdb", $line."\n");
//                }
//                $line = '';
//            }
//        }
//    }
//
//    function new_visit(Request $request) {
//        DB::insert('insert into visitors (ip, visit_time, url) values (?, ?, ?)', [$request->ip(), 'unix_timestamp()', $request->url()]);
//    }
//
//    function check_seq(&$seq) {
//        $seq = preg_replace('/\s+/', '', $seq);
//        if ($seq == "") {
//            die("Please input the sequence.");
//        }
//        $seq = strtoupper($seq);
//        if (preg_match('/T/', $seq)) {
//            die('DNA would be supported later! Please input an RNA sequence');
//        }
//        if (preg_match('/[^AUGC]/', $seq)) {
//            die('The sequence is not a standard RNA sequence.');
//        }
//    }
//
//    function check_ss(&$ss) {
//        /// delete space
//        $ss = preg_replace('/\s+/', '', $ss);
//
//        /// not null
//        if ($ss == "") {
//            die("Please input the 2D structure.");
//        }
//
//        /// only include ( ) [ ] .
//        if (preg_match('/[^()\[\]&.]/', $ss)) {
//            die("The 2D structure should only include '(', ')', '[', ']', '.'.");
//        }
//
//        /// check the parentheses and brackets
//        $left_paren = array();
//        $left_bracket = array();
//        $err_nuc = array();
//        for ($i = 0; $i < strlen($ss); $i++) {
//            if ($ss[$i] == '(') {
//                array_push($left_paren, $i);
//            } else if ($ss[$i] == '[') {
//                array_push($left_bracket, $i);
//            } else if ($ss[$i] == ')') {
//                if (count($left_paren) == 0) {
//                    array_push($err_nuc, $i);
//                } else {
//                    array_pop($left_paren);
//                }
//            } else if ($ss[$i] == ']') {
//                if (count($left_bracket) == 0) {
//                    array_push($err_nuc, $i);
//                } else {
//                    array_pop($left_bracket);
//                }
//            }
//        }
//        $err_nuc = array_merge($err_nuc, $left_paren, $left_bracket);
//        if (count($err_nuc) != 0) {
//            echo "Please check your 2D structure:<br />";
//            for ($i = 0; $i < strlen($ss); $i++) {
//                if (in_array($i, $err_nuc)) {
//                    echo "<font color='#FF0000'>".$ss[$i]."</font>";
//                } else {
//                    echo $ss[$i];
//                }
//            }
//            die("<br />");
//        }
//    }
//
//
//    function del_dir($dir) {
//        $dh=opendir($dir);
//        while ($file=readdir($dh)) {
//            if($file!="." && $file!="..") {
//                $fullpath=$dir."/".$file;
//                if(!is_dir($fullpath)) unlink($fullpath); else $this->del_dir($fullpath);
//            }
//        }
//        closedir($dh);
//        if(rmdir($dir)) return true; else return false;
//    }

}

