<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\AbsenType;
use App\Models\ClassRoom;
use App\Models\QuizStudent;
use App\Models\Student;
use Carbon\Carbon;
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
        $data = $iTbl->where('quiz_student.is_deleted', 0)->skip(request('offset'))->take(request('limit'))->groupBy('quiz_student.student_id')->get();

        // dd(DB::getQueryLog());

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function print() {
        $datetime = Carbon::parse(request('datetime'));

        $student = Student::find(request('student_id'))->first();

        $quizStudent = QuizStudent::where(['quiz_student.student_id' => request('student_id')])
                                ->whereMonth('quiz_student.created_at', $datetime)
                                ->whereYear('quiz_student.created_at', $datetime)
                                ->leftJoin('student', 'student.id', '=', 'quiz_student.student_id')
                                ->leftJoin('quiz', 'quiz.id', '=', 'quiz_student.quiz_id')
                                ->get();

        $countAbsen = AbsenType::all()->map(function($row) use($datetime){
            $query = Absen::where(['absen_type_id' => $row->id, 'student_id' => request('student_id')])
                        ->whereMonth('created_at', $datetime)
                        ->whereYear('created_at', $datetime)
                        ->count();

            $result = (object) [
                "absen_type_name" => $row->name_absen_type,
                "count" => $query,
            ];

            return $result;
        });

        return view('report_print', compact('quizStudent', 'countAbsen', 'student'));
    }
}
