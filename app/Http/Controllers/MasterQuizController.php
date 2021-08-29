<?php

namespace App\Http\Controllers;

use App\Models\LogError;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class MasterQuizController extends Controller
{

    public function index() {
        $action = route('masterQuiz.store');

        return view('master_quiz', compact('action'));
    }

    public function ajaxRead() {
        $iTbl = Quiz::orderBy('name_quiz');

        if(request("search") != null) {
            $iTbl = $iTbl->where('is_deleted', 0)
                        ->where('name_quiz', 'like', '%'.request('search').'%');
        }

        $total = $iTbl->count();
        $data = $iTbl->where('is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = Quiz::orderBy('name_quiz');

        if(request("search") != null) {
           $iTbl = $iTbl->where('is_deleted', 0)
                        ->where('name_quiz', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->where('is_deleted', 0)->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function store() {
        // return request()->all();

        try {
            DB::beginTransaction();

            $quiz = DB::table("quiz");
            $id = (string) Uuid::generate();

            $quiz->insert(array(
                "id" => $id,
                // "name_quiz" => request('name_quiz'),
                "description" => request('description'),
                "created_at" => Carbon::now(),
            ));

            $this->flash_message('message', 'success', 'check', 'Data telah dibuat');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();

            LogError::insert([
                "message" => $e,
                "fitur" => "MasterQuizController@store",
                "created_at" => Carbon::now(),
            ]);

            $this->flash_message('message', 'danger', 'close', 'Data gagal dibuat');
        }

        return redirect()->route('masterQuiz');
    }

    public function update($id) {
        try {
            DB::beginTransaction();

            $quiz = DB::table("quiz")->where('id', $id);

            $quiz->update(array(
                // "name_quiz" => request('name_quiz'),
                "description" => request('description'),
                "updated_at" => Carbon::now(),
            ));

            $this->flash_message('message', 'info', 'check', 'Data telah diperbaharui');
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            $this->flash_message('message', 'danger', 'close', 'Data gagal diperbaharui');
        }

        return redirect()->route('masterQuiz');
    }

    public function delete($id) {
        $quiz = DB::table("quiz")->where('id', $id);

         $quiz->update(array(
            "is_deleted" => 1,
        ));

        $this->flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('masterQuiz');
    }
}
