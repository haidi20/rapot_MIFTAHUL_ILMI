<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\LogError;
use App\Models\Quiz;
use App\Models\QuizDate;
use App\Models\Student;
use App\Models\QuizStudent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class QuizStudentController extends Controller
{

    public function index() {
        $action = route('quizStudent.store');
        $quiz = Quiz::where('is_deleted', 0)->get();
        $student = Student::where('is_deleted', 0)->get();
        $classRoom = ClassRoom::where('is_deleted', 0)->get();
        // return $totalDataQuizStudent = QuizStudent::where('is_deleted', 0)->count();

        return view('quiz_student', compact('action', 'classRoom', 'quiz', 'student'));
    }

    public function ajaxRead() {
        // DB::enableQueryLog();

        $total = 0;
        $iTbl = (object) [];
        $data = [];
        $quizDate = [];
        $offset = request('offset');

        if(request('quiz_id') != null && request('class_room_id') != null) {
            $quizDate = QuizDate::where(['quiz_id' => request('quiz_id'), 'class_room_id' => request('class_room_id')])
                                ->whereMonth('created_at', Carbon::parse(request('datetime')))
                                ->whereYear('created_at', Carbon::parse(request('datetime')))
                                ->get();
            $iTbl = QuizStudent::select('quiz_student.*', 'student.name_student', 'quiz.name_quiz', 'class_room.name_class_room')
                                ->where('student.is_deleted', 0)
                                ->orderBy('quiz.name_quiz');
            $iTbl = $iTbl->leftJoin('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
            $iTbl = $iTbl->leftJoin('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
            $iTbl = $iTbl->leftJoin('student', 'student.id', '=', 'quiz_student.student_id');

            $iTbl = $iTbl->where([
                'quiz_id' => request('quiz_id'),
                'class_room_id' => request('class_room_id'),
            ])->whereMonth('quiz_student.created_at', Carbon::parse(request('datetime')))
            ->whereYear('quiz_student.created_at', Carbon::parse(request('datetime')));

            $total = $iTbl->count();

            if(request("search") != null) {
                $iTbl = $iTbl->where('quiz_student.is_deleted', 0)
                            // ->where('name_class_room', 'like', '%'.request('search').'%')
                            ->where('name_student', 'like', '%'.request('search').'%');
            }

            $data = $iTbl->where('quiz_student.is_deleted', 0)->skip($offset)->take(request('limit'))->get();

            $data = $data->map(function($row) {
                $row->absens = QuizDate::leftjoin('absen', 'quiz_date.id', '=', 'absen.date_absen_id')
                                        ->where('absen.student_id', $row->student_id)
                                        ->where('absen.quiz_student_id', $row->id)
                                        ->whereMonth('quiz_date.created_at', Carbon::now())
                                        ->whereYear('quiz_date.created_at', Carbon::now())
                                        ->orderBy('date')
                                        ->get();

                return $row;
            });
        }

        // dd(DB::getQueryLog());

        return response()->json([
                                    "rows" => $data, "data" => $iTbl, "total" => $total,
                                    "offset" => $offset, "limit" => request('limit'),
                                    "search" => request('search'), "quizDate" => $quizDate,
                                ]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = QuizStudent::orderBy('name_quiz');
        $iTbl = $iTbl->join('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
        // $iTbl = $iTbl->join('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
        $iTbl = $iTbl->join('student', 'student.id', '=', 'quiz.student_id');

        if(request("search") != null) {
            $iTbl = $iTbl->where('quiz_student.is_deleted', 0)
                        // ->where('name_class_room', 'like', '%'.request('search').'%')
                        ->where('name_student', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->where('is_deleted', 0)->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function ajaxReadDateAbsen() {
        // show data date absen
    }

    public function store() {
        // return request()->all();

        $checkDataQuizStudent = QuizStudent::where([
                'class_room_id' => request('class_room_id'),
                'quiz_id' => request('quiz_id'),
            ])
            ->whereMonth('created_at', Carbon::now())
            ->whereYear('created_at', Carbon::now())
            ->first();

        if($checkDataQuizStudent) {
            $this->flash_message('message', 'danger', 'close', 'Maaf, kelas dan kuis sudah ada');

            return redirect()->route('quizStudent');
        }

        if($this->is_array_empty(request('students'))) {
            $this->flash_message('message', 'danger', 'close', 'Maaf, harus ada peserta');

            return redirect()->route('quizStudent');
        }

        if($this->is_array_empty(request('date_absen'))) {
            $this->flash_message('message', 'danger', 'close', 'Maaf, inputan pertemuan tidak boleh kosong');

            return redirect()->route('quizStudent');
        }

        if(request('class_room_id') == null) {
            $this->flash_message('message', 'danger', 'close', 'Maaf, harus pilih kelas');

            return redirect()->route('quizStudent');
        }

        if(request('quiz_id') == null) {
            $this->flash_message('message', 'danger', 'close', 'Maaf, harus pilih kuis');

            return redirect()->route('quizStudent');
        }

        try {
            DB::beginTransaction();

            foreach(request('students') as $index => $item) {
                $checkStudentOtherClass = quizStudent::where(['student_id' => $item, "quiz_id" => request('quiz_id')])->first();

                if($checkStudentOtherClass) {
                    $this->flash_message('message', 'danger', 'close', 'Maaf, peserta ini sudah ada di kelas lain');

                    return redirect()->route('quizStudent');
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

            foreach(request('date_absen') as $index => $item) {
                if($item != null) {
                    $id = (string) Uuid::generate();

                    DB::table("quiz_date")
                    ->insert([
                        "id" => $id,
                        "quiz_id" => request('quiz_id'),
                        "class_room_id" => request('class_room_id'),
                        "date" => $item,
                        "created_at" => Carbon::now(),
                    ]);
                }
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

        return redirect()->route('quizStudent');
    }

    public function delete($id) {
        $quizStudent = DB::table("quiz_student")->where('id', $id);

        $quizStudent->update([
            "is_deleted" => 1,
        ]);

        $this->flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('quizStudent');
    }

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
