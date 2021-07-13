<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class MasterStudentController extends Controller
{

    public function index() {
        $action = route('masterStudent.store');

        return view('master_student', compact('action'));
    }

    public function ajaxRead() {
        $iTbl = Student::orderBy('name_student');

        if(request("search") != null) {
            $iTbl = $iTbl->where('is_deleted', 0)
                        ->where('name_student', 'like', '%'.request('search').'%');
        }

        $total = $iTbl->count();
        $data = $iTbl->where('is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = Student::orderBy('name_student');

        if(request("search") != null) {
           $iTbl = $iTbl->where('is_deleted', 0)
                        ->where('name_student', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->where('is_deleted', 0)->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function store() {
        // return request()->all();

        try {
            DB::beginTransaction();

            $student = DB::table("student");
            $id = (string) Uuid::generate();

            $student->insert(array(
                "id" => $id,
                "name_student" => request('name_student'),
                "created_at" => Carbon::now(),
            ));

            flash_message('message', 'success', 'check', 'Data telah dibuat');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            flash_message('message', 'danger', 'close', 'Data gagal dibuat');
        }

        return redirect()->route('masterStudent');
    }

    public function update($id) {
        try {
            DB::beginTransaction();

            $student = DB::table("student")->where('id', $id);

            $student->update(array(
                "name_student" => request('name_student'),
                "updated_at" => Carbon::now(),
            ));

            flash_message('message', 'info', 'check', 'Data tellah diperbaharui');
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            flash_message('message', 'danger', 'close', 'Data gagal diperbaharui');
        }

        return redirect()->route('masterStudent');
    }

    public function delete($id) {
        $student = DB::table("student")->where('id', $id);

         $student->update(array(
            "is_deleted" => 1,
        ));

        flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('masterStudent');
    }
}
