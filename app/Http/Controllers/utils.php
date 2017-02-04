<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

function inf_visit(Request $request, $conn = "mysql") {
    DB::connection($conn)->insert('insert into visitors (ip, visit_time, url) values (?, ?, ?)', [$request->ip(), 'unix_timestamp()', $request->url()]);
    $num_users = DB::connection($conn)->table('jobs')->count('ip');
    $num_visitors = DB::connection($conn)->table('visitors')->count('id');
    $num_diff_visitors = count(DB::connection($conn)->table('visitors')->select('id')->groupBy('ip')->get());
    if ($conn == "mysql") $num_diff_visitors += 2028;
    return ['num_users'=>$num_users, 'num_visitors'=>$num_visitors, 'num_diff_visitors'=>$num_diff_visitors, 'ip'=>$request->ip()];
}

function del_dir($dir) {
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                $this->del_dir($fullpath);
            }
        }
    }
    closedir($dh);
    if(rmdir($dir)) return true; else return false;
}

function is_ip($a) {
    return preg_match('/^\d+\.\d+\.\d+\.\d+$/', $a);
}

function is_email($a) {
    return preg_match('/^.+@.+$/', $a);
}

function is_num($a) {
    return preg_match('/^\d+$/', $a);
}

function array2object($array) {  
    if (is_array($array)) {  
        $obj = new StdClass();  
        foreach ($array as $key => $val){  
            $obj->$key = $val;  
        }  
    } else {
        $obj = $array;
    }  
    return $obj;  
}  

function object2array($object) {  
    if (is_object($object)) {  
        foreach ($object as $key => $value) {  
            $array[$key] = $value;  
        }  
    } else {  
        $array = $object;  
    }  
    return $array;  
}  

