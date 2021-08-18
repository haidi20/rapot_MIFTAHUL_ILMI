<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
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
            $quizDate = QuizDate::where(['quiz_id' => request('quiz_id'), 'class_room_id' => request('class_room_id')])->get();
            $iTbl = QuizStudent::select('quiz_student.*', 'student.name_student', 'quiz.name_quiz', 'class_room.name_class_room')
                                ->orderBy('quiz.name_quiz');
            $iTbl = $iTbl->leftJoin('quiz', 'quiz.id', '=', 'quiz_student.quiz_id');
            $iTbl = $iTbl->leftJoin('class_room', 'class_room.id', '=', 'quiz_student.class_room_id');
            $iTbl = $iTbl->leftJoin('student', 'student.id', '=', 'quiz_student.student_id');

            $iTbl = $iTbl->where(['quiz_id' => request('quiz_id'), 'class_room_id' => request('class_room_id')]);
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

                    if($data->get()->count() > 4) {
                        $data->update(["date" => $item]);
                    }else {
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
            }

            flash_message('message', 'success', 'check', 'Data berhasil dikirim');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

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
}
