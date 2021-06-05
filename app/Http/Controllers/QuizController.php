<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class QuizController extends Controller
{

    public function index() {
        $action = route('quiz.store');
        $classRoom = ClassRoom::where('is_deleted', 0)->get();

        return view('quiz', compact('action', 'classRoom'));
    }

    public function ajaxRead() {
        $iTbl = Quiz::orderBy('name_quiz');

        if(request("search") != null) {
            $iTbl = $iTbl->where('is_deleted', 0)
                        ->where('name_quiz', 'like', '%'.request('search').'%');
        }

        $data = $iTbl->where('is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();
        $total = $iTbl->count();

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = Quiz::orderBy('name_quiz');

        if(request("search") != null) {
           $iTbl = $iTbl->where('is_deleted', 0)
                        ->orWhere('name_quiz', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->where('is_deleted', 0)->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function store() {
        return request()->all();

        try {
            DB::beginTransaction();

            $quiz = DB::table("quiz");
            $id = (string) Uuid::generate();

            $quiz->insert(array(
                "id" => $id,
                "name_quiz" => request('name_quiz'),
                "created_at" => Carbon::now(),
            ));

            flash_message('message', 'success', 'check', 'Data telah dibuat');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            flash_message('message', 'danger', 'close', 'Data gagal di dibuat');
        }

        return redirect()->route('quiz');
    }

    public function update($id) {
        try {
            DB::beginTransaction();

            $quiz = DB::table("quiz")->where('id', $id);

            $quiz->update(array(
                "name_quiz" => request('name_quiz'),
                "updated_at" => Carbon::now(),
            ));

            flash_message('message', 'info', 'check', 'Data telah disimpan');
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            flash_message('message', 'danger', 'close', 'Data gagal di disimpan');
        }

        return redirect()->route('quiz');
    }

    public function delete($id) {
        $quiz = DB::table("quiz")->where('id', $id);

         $quiz->update(array(
            "is_deleted" => 1,
        ));

        flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('quiz');
    }
}
