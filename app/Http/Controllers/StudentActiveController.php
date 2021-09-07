<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentActiveController extends Controller
{
    public function index() {
        $action = route('quizStudent.store');
        $quiz = Quiz::where('is_deleted', 0)->get();
        $classRoom = ClassRoom::where('is_deleted', 0)->get();

        return view('student_active', compact('action', 'classRoom', 'quiz'));
    }

    public function ajaxRead() {
        $offset = request('offset');

        $iTbl = QuizStudent::select('quiz_student.*', 'student.name_student', 'student.nis', 'class_room.name_class_room')
                ->where('student.is_deleted', 0)
                ->orderBy('class_room.name_class_room');
        // $iTbl = $iTbl->leftJoin('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
        $iTbl = $iTbl->leftJoin('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
        $iTbl = $iTbl->leftJoin('student', 'student.id', '=', 'quiz_student.student_id');

        $iTbl = $iTbl->whereMonth('quiz_student.created_at', '=', Carbon::parse(request('datetime'))->format('m'))
                    ->whereYear('quiz_student.created_at', '=', Carbon::parse(request('datetime'))->format('Y'));

        if(request("search") != null) {
            $iTbl = $iTbl->where('quiz_student.is_deleted', 0)
                    ->where(function($q){
                        $q->orWhere('class_room.name_class_room', 'LIKE', '%'.request('search').'%')
                        ->orWhere('student.nis', 'LIKE', '%'.request('search').'%')
                        ->orWhere('student.name_student', 'LIKE', '%'.request('search').'%');
                    });
        }



        $iTbl = $iTbl->groupBy('quiz_student.class_room_id', 'quiz_student.student_id');

        $total = $iTbl->count();

        $data = $iTbl->where('quiz_student.is_deleted', 0)->skip($offset)->take(request('limit'))->get();

        return response()->json([
            "rows" => $data, "data" => $iTbl, "total" => $total,
            "offset" => $offset, "limit" => request('limit'),
            "search" => request('search'), "datetime" => Carbon::parse(request('datetime'))->format('Y-m'),
        ]);
    }
}
