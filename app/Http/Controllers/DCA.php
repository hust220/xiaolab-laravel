<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\dca_tasks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

require_once("utils.php");

class DCA extends Controller {
    const WORK_PATH = "/home/wangjian/server/DCA/";

    public function index(Request $request) {
        return view('DCA.index', inf_visit($request));
    }

    public function submit(Request $request) {
        $input = $request->all();
        $input['task_id'] = rand(1, 999999999);
        $input['ip'] = $request->ip();
        $errors = $this->check_input($input);
        if (count($errors) == 0) {
            $this->dca($input);
            $this->save_task($input);
            return response()->json(['status'=>'ok', 'task_id'=>$input['task_id']], 200);
        } else {
            return response()->json(['status'=>'failed', 'errors'=>$errors], 420);
        }
    }

    public function monitor(Request $request, $id) {
        return response()->json($this->load_task($id), 200);
    }

    public function running_tasks(Request $request, $ip) {
        $result = dca_tasks::where('ip', $ip)->get();
        return response()->json($result, 200);
    }

    public function task(Request $request, $id) {
        return view('DCA.task', inf_visit($request) + ['task'=>$this->load_task($id)]);
    }

    public function result(Request $request, $id) {
        $path = self::WORK_PATH."results/$id/$id.nongap.di";
        $result = $this->read_results($path);
        return response()->json($result, 200);
    }

    public function download(Request $request, $id, $type) {
        if ($type == "di") {
            return response()->download("/home/wangjian/server/DCA/results/$id/$id.nongap.di");
        } else {
            return response()->download("/home/wangjian/server/DCA/results/$id/$id.msa");
        }
    }

    public function query(Request $request) {
        $input = $request->all();
        if (!isset($input['query']) || $input['query'] == '') return response()->json(['errors'=>['Please input the content of your query!']], 420);
        $content = $input['query'];
        $result = '';
        if (is_ip($content)) {
            $result = dca_tasks::where('ip', $content)->get();
        } else if (is_email($content)) {
            $result = dca_tasks::where('email', $content)->get();
        } else if (is_num($content)) {
            $result = dca_tasks::where('id', $content)->get();
        } else if ($content == "all") {
            $result = dca_tasks::all();
        }
        if ($result == '' || count($result) == 0) {
            return response()->json(['errors'=>['Not Found!']], 420);
        } else {
            return response()->json(['results'=>$result], 200);
        }
    }

    function save_task($input) {
        $dca_task = new dca_tasks;
        $dca_task->id = $input['task_id'];
        $dca_task->created_at = DB::raw('unix_timestamp()');
        $dca_task->ip = $input['ip'];
        if (isset($input['seq'])) $dca_task->seq = $input['seq'];
        if (isset($input['msa'])) $dca_task->msa = $input['msa'];
        $dca_task->save();
    }

    function load_task($id) {
        return dca_tasks::where('id', $id)->first()->toArray();
    }

    function check_input($input) {
        $errors = [];
        if (!isset($input['mol_type'])) {
            $errors[] = 'Please select the molecular type!';
        }
        if (isset($input['seq'])) {
            $seq = $input['seq'];
            if ($seq == '') {
                $errors[] = 'Please provide the sequence!';
            } else if (preg_match('/[^AUGC]/', $seq)) {
                $errors[] = 'The sequence is not an RNA sequence!';
            }
        } else if (isset($input['msa'])) {
            if ($input['msa'] == '') {
                $errors[] = 'Please provide the MSA!';
            }
        } else {
            $errors[] = 'Please provide the sequence or the MSA!';
        }
        return $errors;
    }

    function dca($input) {
        $task_id = $input['task_id'];
        if (isset($input['seq'])) {
            $this->save_input($input['seq'], $task_id, "$task_id.seq");
        } else if (isset($input['msa'])) {
            $this->save_input($input['msa'], $task_id, "$task_id.msa");
        }
        shell_exec("/home/wangjian/server/DCA/dca.sh $task_id >/dev/null 2>&1 &");
    }

    function save_input($input, $task_id, $file) {
        $path = self::WORK_PATH."results/$task_id";
        mkdir($path, 0777, true);
        if ($input != '') {
            $arr = preg_split("/[\n\r]+/", trim($input));
            $f = fopen("$path/$file", 'w');
            foreach ($arr as $i) {
                fwrite($f, $i);
                fwrite($f, "\n");
            }
            fclose($f);
        }
    }

    function read_results($path) {
        $results = [];
        $f = fopen($path, "r");
        while (!feof($f)) {
            $line = fgets($f);
            $arr = preg_split("/\s+/", trim($line));
            if (count($arr) > 0) $results[] = $arr;
        }
        fclose($f);
        return $results;
    }

}

