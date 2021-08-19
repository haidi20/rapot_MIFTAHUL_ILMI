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
                                ->get();
            $iTbl = QuizStudent::select('quiz_student.*', 'student.name_student', 'quiz.name_quiz', 'class_room.name_class_room')
                                ->orderBy('quiz.name_quiz');
            $iTbl = $iTbl->leftJoin('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
            $iTbl = $iTbl->leftJoin('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
            $iTbl = $iTbl->leftJoin('student', 'student.id', '=', 'quiz_student.student_id');

            $iTbl = $iTbl->where([
                'quiz_id' => request('quiz_id'),
                'class_room_id' => request('class_room_id'),
            ])->whereMonth('quiz_student.created_at', Carbon::parse(request('datetime')));

            $total = $iTbl->count();

            if(request("search") != null) {
                $iTbl = $iTbl->where('quiz_student.is_deleted', 0)
                            // ->where('name_class_room', 'like', '%'.request('search').'%')
                            ->where('name_student', 'like', '%'.request('search').'%');
            }

            $data = $iTbl->where('quiz_student.is_deleted', 0)->skip($offset)->take(request('limit'))->get();

            $data = $data->map(function($row) {
                $row->absens = DB::select(DB::raw("
                    SELECT *, STR_TO_DATE(date, '%m/%d') as custome_date
                    FROM quiz_date
                    LEFT JOIN absen ON quiz_date.id = absen.date_absen_id
                    WHERE absen.student_id = '$row->student_id'
                    AND absen.quiz_student_id = '$row->id'
                    ORDER BY custome_date
                "));


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

        // return $this->is_array_empty(request('date_absen')) ? 'kosong' : 'isi';

        $checkDataQuizStudent = QuizStudent::where(['class_room_id' => request('class_room_id'), 'quiz_id' => request('quiz_id')])->first();

        if($checkDataQuizStudent) {
            flash_message('message', 'danger', 'close', 'Maaf, kelas dan kuis sudah ada');

            return redirect()->route('quizStudent');
        }

        if($this->is_array_empty(request('students'))) {
            flash_message('message', 'danger', 'close', 'Maaf, harus ada peserta');

            return redirect()->route('quizStudent');
        }

        if($this->is_array_empty(request('date_absen'))) {
            flash_message('message', 'danger', 'close', 'Maaf, inputan pertemuan tidak boleh kosong');

            return redirect()->route('quizStudent');
        }

        if(request('class_room_id') == null) {
            flash_message('message', 'danger', 'close', 'Maaf, harus pilih kelas');

            return redirect()->route('quizStudent');
        }

        if(request('quiz_id') == null) {
            flash_message('message', 'danger', 'close', 'Maaf, harus pilih kuis');

            return redirect()->route('quizStudent');
        }

        try {
            DB::beginTransaction();

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

                    $data = DB::table("quiz_date")
                                ->where(["quiz_id" => request('quiz_id'), "class_room_id" => request('class_room_id')]);

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

            flash_message('message', 'success', 'check', 'Data berhasil dikirim');

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

            flash_message('message', 'danger', 'close', 'Data gagal dikirim');
        }

        return redirect()->route('quizStudent');
    }

    public function delete($id) {
        $quizStudent = DB::table("quiz_student")->where('id', $id);

        $quizStudent->update(array(
            "is_deleted" => 1,
        ));

        flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('quizStudent');
    }

    // helper local
    private function getDateAbsen($data) {
        $dateNow = Carbon::now();

        $dateMonthArray = explode('/', $data);
        $day = $dateMonthArray[0];
        $month = $dateMonthArray[1];

        $date = Carbon::createFromDate($dateNow->format('Y'), $month, $day);

        return $date;
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
