<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DNA2D extends Controller {
    public function new_job(Request $request) {
        $ip = $request->ip();
        $url = $request->url();
        DB::insert('insert into visitors (ip, visit_time, url) values (?, ?, ?)', [$ip, 'unix_timestamp()', $url]);

        $num_users = DB::table('jobs')->count('ip');
        $num_visitors = DB::table('visitors')->count('id');

        return view('2dDNA', ['num_users' => $num_users, 'num_visitors' => $num_visitors]);
    }


}

