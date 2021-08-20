<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\LogError;
use App\Models\Quiz;
use App\Models\QuizStudent;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class ReportController extends Controller
{

    public function index() {
        $classRoom = ClassRoom::where('is_deleted', 0)->get();

        return view('report', compact('classRoom'));
    }

    public function ajaxRead() {
        DB::enableQueryLog();
        $data = [];
        $iTbl = [];
        $total = 0;

        $iTbl = QuizStudent::whereMonth('quiz_student.created_at', Carbon::parse(request('datetime')))
                        ->where('class_room_id' , request('class_room_id'))
                        ->orderBy('quiz_student.created_at');

        $iTbl = $iTbl->leftJoin('student', 'student.id', '=', 'quiz_student.student_id');

        if(request("search") != null) {
        $iTbl = $iTbl->where('is_deleted', 0)
                ->where('student.name_student', 'like', '%'.request('search').'%');
        }

        $total = $iTbl->count();
        $data = $iTbl->where('quiz_student.is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();

        // dd(DB::getQueryLog());

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }
}
