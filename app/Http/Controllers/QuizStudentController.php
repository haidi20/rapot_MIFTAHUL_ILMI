<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizStudent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class QuizStudentController extends Controller
{

    public function index() {
        $action = route('quizStudent.store');
        $quiz = Quiz::where('is_deleted', 0)->get();
        $classRoom = ClassRoom::where('is_deleted', 0)->get();

        return view('quiz_student', compact('action', 'classRoom', 'quiz'));
    }

    public function ajaxRead() {
        DB::enableQueryLog();

        $total = 0;
        $iTbl = (object) [];
        $data = [];

        if(request('quis_id') != null) {
            $iTbl = QuizStudent::orderBy('name_quiz');
            $iTbl = $iTbl->join('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
            // $iTbl = $iTbl->join('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
            $iTbl = $iTbl->join('student', 'student.id', '=', 'quiz.student_id');

            if(request("search") != null) {
                //FILTER BELUM BERHASIL
                $iTbl = $iTbl->where('is_deleted', 0)
                            // ->where('name_class_room', 'like', '%'.request('search').'%')
                            ->where('name_student', 'like', '%'.request('search').'%')
                            ->orWhere('name_quiz', 'like', '%'.request('search').'%');
            }

            $data = $iTbl->where('quiz.is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();
            $total = $iTbl->count();

            $data = $data->map(function($row) {
                $row->absens = DB::table('absen')->where('quiz_id', $row->id)->get();

                return $row;
            });
        }

        // dd(DB::getQueryLog());

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = QuizStudent::orderBy('name_quiz');
        $iTbl = $iTbl->join('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
        // $iTbl = $iTbl->join('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
        $iTbl = $iTbl->join('student', 'student.id', '=', 'quiz.student_id');

        if(request("search") != null) {
           $iTbl = $iTbl->where('is_deleted', 0)
                        // ->where('name_class_room', 'like', '%'.request('search').'%')
                        ->where('name_student', 'like', '%'.request('search').'%')
                        ->orWhere('name_quiz', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->where('is_deleted', 0)->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function store() {
        // return request()->all();

        try {
            DB::beginTransaction();

           foreach(request('students') as $index => $item) {
                $quizStudent = DB::table("quiz_student");
                $id = (string) Uuid::generate();

                $quizStudent->insert(array(
                    "id" => $id,
                    "class_room_id" => request('class_room_id'),
                    "student_id" => $item,
                    "quiz_id" => request('quiz_id'),
                    "created_at" => Carbon::now(),
                ));
           }

            // foreach (request('students') as $index => $item) {
            //     $absen = DB::table('absen');
            //     $absen->insert(array(
            //         "id" => $idAbsen,
            //         "quiz_id" => $id,
            //         "student_id" => $item,
            //         "created_at" => Carbon::now(),
            //     ));
            // }

            flash_message('message', 'success', 'check', 'Data telah dibuat');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            flash_message('message', 'danger', 'close', 'Data gagal di dibuat');
        }

        return redirect()->route('quizStudent');
    }

    // public function update($id) {
    //     try {
    //         DB::beginTransaction();

    //         $quizStudent = DB::table("quiz_student")->where('id', $id);

    //         $quizStudent->update(array(
    //             "quiz_id" => request('quiz_id'),
    //             "updated_at" => Carbon::now(),
    //         ));

    //         flash_message('message', 'info', 'check', 'Data telah disimpan');
    //         DB::commit();
    //         // all good
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         flash_message('message', 'danger', 'close', 'Data gagal di disimpan');
    //     }

    //     return redirect()->route('quizStudent');
    // }

    public function delete($id) {
        $quizStudent = DB::table("quiz_student")->where('id', $id);

         $quizStudent->update(array(
            "is_deleted" => 1,
        ));

        flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('quizStudent');
    }
}
