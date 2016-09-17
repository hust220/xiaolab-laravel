<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class lei extends Controller {
    public function show(Request $request) {
        system('dir=$(pwd);cd /usr/local/apache/laravel/public/leilei; ./shuffle.sh >/dev/null; cd $dir');
        return view('lei');
    }
}

