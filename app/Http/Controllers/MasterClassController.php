<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterClassController extends Controller
{

    public function index() {

        return view('master_class');
    }
}
