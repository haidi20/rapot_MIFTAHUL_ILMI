<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Absen;
use App\Models\LogError;
use App\Models\QuizDate;
use App\Models\QuizStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class AbsenController extends Controller
{
    public function ajaxRead() {
        DB::enableQueryLog();
        $data = [];
        $iTbl = [];
        $total = 0;

        if(request('quiz_id') != null) {
            $iTbl = Absen::where('quiz_id', request('quiz_id'))
                        ->leftJoin('student', function($join) {
                            $join->on("student.id", '=', 'absen.student_id');
                        })
                        ->orderBy('student.name_student');

            if(request("search") != null) {
                $iTbl = $iTbl->where('absen.is_deleted', 0)
                            ->where('name_quiz', 'like', '%'.request('search').'%');
            }

            $total = $iTbl->count();
            $data = $iTbl->where('absen.is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();
        }

        // dd(DB::getQueryLog());

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxSave() {
        // return request()->all();

        try {
            DB::beginTransaction();

            $quizDate = QuizDate::where(['class_room_id' => request('class_room_id'), "quiz_id" => request('quiz_id')])
                                        ->get();

            $quizStudent = QuizStudent::find(request('dataQuizStudent')['id']);
            $quizStudent->value = request('dataQuizStudent')['value'];
            $quizStudent->grade = request('dataQuizStudent')['grade'];
            $quizStudent->note = request('dataQuizStudent')['note'];
            $quizStudent->save();

            foreach($quizDate as $index => $item) {
                // $checkDataAbsen =
                $item->absen_type_id = null;

                foreach (request('dataAbsen') as $key => $dataAbsen) {
                    if($dataAbsen['date_absen_id'] == $item->id) {
                        $item->absen_type_id = $dataAbsen['absen_type_id'];
                    }

                    $item->student_id = $dataAbsen['student_id'];
                    $item->quiz_student_id = $dataAbsen['quiz_student_id'];
                }

                Absen::updateOrCreate(
                    [
                        "student_id" => $item->student_id,
                        "quiz_student_id" => $item->quiz_student_id,
                        "date_absen_id" => $item->id,
                    ],
                    [
                        "absen_type_id" => $item->absen_type_id,
                    ]
                );
            }

            // flash_message('message', 'success', 'check', 'Data telah dibuat');

            DB::commit();

            return $this->responseWithSuccess('Data berhasil diperbaharui', "ok");
            // return $this->responseWithSuccess($combineData, "ok");
            // all good
        } catch (\Exception $e) {
            DB::rollback();

            LogError::insert([
                "message" => $e,
                "fitur" => "AbsenController@ajaxSave",
                "created_at" => Carbon::now(),
            ]);

            // flash_message('message', 'danger', 'close', 'Data gagal di dibuat' . $e);
            return $this->responseWithError('Data gagal diperbaharui', "error");
            // return $this->responseWithError($e, "error");
        }
    }
}
