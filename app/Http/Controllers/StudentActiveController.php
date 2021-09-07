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

class StudentActiveController extends Controller
{
    public function index() {
        $action = route('studentActive.storeStudent');
        $quiz = Quiz::where('is_deleted', 0)->get();
        $student = Student::where('is_deleted', 0)->get();
        $classRoom = ClassRoom::where('is_deleted', 0)->get();

        return view('student_active', compact('action', 'classRoom', 'quiz', 'student'));
    }

    public function ajaxRead() {
        $total = 0;
        $iTbl = (object) [];
        $data = [];
        $offset = request('offset');

        if(request('quiz_id') != null && request('class_room_id') != null) {
            $iTbl = QuizStudent::select('quiz_student.*', 'student.name_student', 'student.nis', 'class_room.name_class_room')
                                ->where('student.is_deleted', 0)
                                ->orderBy('class_room.name_class_room');
            // $iTbl = $iTbl->leftJoin('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
            $iTbl = $iTbl->leftJoin('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
            $iTbl = $iTbl->leftJoin('student', 'student.id', '=', 'quiz_student.student_id');

            $iTbl = $iTbl->whereMonth('quiz_student.created_at', '=', Carbon::parse(request('datetime'))->format('m'))
                        ->whereYear('quiz_student.created_at', '=', Carbon::parse(request('datetime'))->format('Y'));

            // if(request('quiz_id')){
                // $iTbl = $iTbl->where('quiz_id', request('quiz_id'));
            // }
            // if(request('class_room_id')){
                // $iTbl = $iTbl->where('class_room_id', request('class_room_id'));
            // }

            $iTbl = $iTbl->where('quiz_id', request('quiz_id'));
            $iTbl = $iTbl->where('class_room_id', request('class_room_id'));

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

        }

        return response()->json([
            "rows" => $data, "data" => $iTbl, "total" => $total,
            "offset" => $offset, "limit" => request('limit'),
            "search" => request('search'),
        ]);
    }

    public function storeStudent() {

        if($this->is_array_empty(request('students'))) {
            $this->flash_message('message', 'danger', 'close', 'Maaf, harus ada peserta');

            return redirect()->route('quizStudent');
        }

        try {
            DB::beginTransaction();

            foreach(request('students') as $index => $item) {
                $checkStudentOtherClass = quizStudent::where([
                    'student_id' => $item,
                    "quiz_id" => request('quiz_id'),
                    "class_room_id" => request('class_room_id')
                ])
                ->whereMonth('quiz_student.created_at', '=', Carbon::parse(request('datetime'))->format('m'))
                ->whereYear('quiz_student.created_at', '=', Carbon::parse(request('datetime'))->format('Y'))
                ->first();

                if($checkStudentOtherClass) {
                    $this->flash_message('message', 'danger', 'close', 'Maaf, peserta ini sudah ada');

                    return redirect()->route('studentActive.index');
                }
            }

            foreach(request('students') as $index => $item) {
                $quizStudent = DB::table("quiz_student");
                $id = (string) Uuid::generate();

                $quizStudent->insert(array(
                    "id" => $id,
                    "quiz_id" => request('quiz_id'),
                    "class_room_id" => request('class_room_id'),
                    "student_id" => $item,
                    "created_at" => Carbon::now(),
                ));
            }

            $this->flash_message('message', 'success', 'check', 'Data berhasil dikirim');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            LogError::insert([
                "message" => $e,
                "fitur" => "QuizStudentController@store",
                "created_at" => Carbon::now(),
            ]);

            $this->flash_message('message', 'danger', 'close', 'Data gagal dikirim');
        }

        return redirect()->route('studentActive.index');
    }


    // helper
    function is_array_empty($arr){
        if(is_array($arr)){
            foreach($arr as $value) {
                if($value == null || $value == ""){
                    return true;
                    break;//stop the process we have seen that at least 1 of the array has value so its not empty
                }
            }

            return false;
        }
    }
}
