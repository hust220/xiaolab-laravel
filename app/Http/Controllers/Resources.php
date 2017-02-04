<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

require_once("utils.php");

class Resources extends Controller {
    public function index(Request $request) {
        return view('resources.index', inf_visit($request));
    }

    public function _3drna_opt_dca(Request $request) {
        return view('resources.3drna_opt_dca', inf_visit($request));
    }

}

