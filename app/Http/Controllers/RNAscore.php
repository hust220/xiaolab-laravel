<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

require_once("utils.php");

class RNAscore extends Controller {
    public function index(Request $request) {
        return view('RNAscore.index', inf_visit($request));
    }

    public function submit(Request $request) {
        $errors = [];
        if ($request->input('input_type') == "") {
            $errors['input_type'] = "The input_type field is required!";
        }

        if (!$request->hasFile('struct_file')) {
            $errors['struct_file'] = "The struct_file field is required!";
        }

        if (count($errors) != 0) {
            return response()->json($errors, 420);
        }

        $input_type = $request->input('input_type');
        $file_type = '.pdb';
        $file = $request->file('struct_file');
        $file_name = $file->getClientOriginalName();
        $file_ext = substr($file_name, strlen($file_name)-4, 4);
        if ($input_type == 'multiple') {
            $file_type = '.tar.gz';
            $file_ext = substr($file_name, strlen($file_name)-7, 7);
        }

        $validator = Validator::make([
            'file_type'=>$file_ext
        ], [
            'file_type' => "in:$file_type"
        ]);

        if ($validator->fails()) {
            return response()->json(['struct_file'=>["The file type must be $file_type"]], 422);
        } else {
            $request->file('struct_file')->move('/home/wangjian/server/3dRNAscore-server/', $file_name);
            $results = [];
            exec("/home/wangjian/server/3dRNAscore-server/submit.sh $file_name $file_ext", $results);
            return response()->json(['results'=>$results], 200);
        }
//        return 'hi';
    }
}

