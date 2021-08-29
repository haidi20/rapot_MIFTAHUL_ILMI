<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use PhpParser\Builder\Class_;

class DashboardController extends Controller
{

    public function index() {
        $countStudent = Student::count();
        $countClass = ClassRoom::count();

        return view('dashboard', compact('countStudent', 'countClass'));
    }
}
