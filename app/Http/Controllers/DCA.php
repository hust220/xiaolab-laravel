<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

require_once("utils.php");

class DCA extends Controller {
    const WORK_PATH = "/home/wangjian/server/DCA/";

    public function index(Request $request) {
        return view('DCA.index', inf_visit($request));
    }

    public function msa(Request $request) {
        $input = $request->all();
        $seq = $input['seq'];
        if ($seq != "") {
            if (preg_match('/[^AUGC]/', $seq)) {
                return response()->json(["status"=>"failed", "errors"=>["The sequence is not an RNA sequence!"]], 420);
            } else {
                $result = [];
                exec("/home/wangjian/server/DCA/msa.sh $seq", $result);
                return response()->json(["status"=>"ok", "result"=>$result], 200);
            }
        } else {
            return response()->json(["status"=>"failed", "errors"=>["Please input the sequence!"]], 420);
        }
     }

    public function dca(Request $request) {
        $input = $request->all();
        $msa = $input['msa'];
        if ($msa != "") {
            $this->save_msa($msa, "MSA.txt");
            $result = [];
            exec("/home/wangjian/server/DCA/dca.sh MSA.txt", $result);
            return response()->json(["status"=>"ok", "result"=>$result], 200);
        } else {
            return response()->json(["status"=>"failed", "errors"=>["Please input the MSA"]], 420);
        }
    }

    function save_msa($msa, $file) {
        if ($msa != '') {
            $arr = preg_split("/[\n\r]+/", trim($msa));
            $f = fopen(self::WORK_PATH.$file, 'w');
            foreach ($arr as $i) {
                fwrite($f, $i);
                fwrite($f, "\n");
            }
            fclose($f);
        }
    }

    public function result(Request $request, $id) {
        $exists = exec("/home/wangjian/server/DCA/is_done.sh $id");
        if ($exists == "yes") {
            $results = $this->read_result_file("/home/wangjian/server/DCA/results/DCA-result-$id.txt");
            return view('DCA.result', $this->inf_visit() + ['id'=>$id, 'results'=>$results]);
        } else {
            return view('DCA.result', $this->inf_visit() + ['id'=>$id]);
        }
    }

    public function download(Request $request, $id) {
        return response()->download("/home/wangjian/server/DCA/results/DCA-result-$id.txt");
    }

    function read_result_file($path) {
        $results = [];
        $f = fopen($path, "r");
        while (!feof($f)) {
            $line = fgets($f);
            $arr = preg_split("/\s+/", trim($line));
            if (count($arr) == 4) $results[] = $arr;
        }
        fclose($f);
        return $results;
    }

    function inf_visit() {
        $num_users = DB::table('jobs')->count('ip');
        $num_visitors = DB::table('visitors')->count('id');
        return ['num_users'=>$num_users, 'num_visitors'=>$num_visitors];
    }

    function new_visit(Request $request) {
        DB::insert('insert into visitors (ip, visit_time, url) values (?, ?, ?)', [$request->ip(), 'unix_timestamp()', $request->url()]);
    }

}

