<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

require_once("utils.php");

class Xiaolab extends Controller {
    public function home(Request $request) {
        return view('home', inf_visit($request));
    }

    public function publications(Request $request) {
        return view('publications', inf_visit($request));
    }

}

