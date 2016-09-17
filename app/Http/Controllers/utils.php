<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

function inf_visit(Request $request) {
    DB::insert('insert into visitors (ip, visit_time, url) values (?, ?, ?)', [$request->ip(), 'unix_timestamp()', $request->url()]);
    $num_users = DB::table('jobs')->count('ip');
    $num_visitors = DB::table('visitors')->count('id');
    $num_diff_visitors = 2028+count(DB::table('visitors')->select('id')->groupBy('ip')->get());
    return ['num_users'=>$num_users, 'num_visitors'=>$num_visitors, 'num_diff_visitors'=>$num_diff_visitors];
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



