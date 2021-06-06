<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Absen;
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

            $data = $iTbl->where('absen.is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();
            $total = $iTbl->count();
        }

        // dd(DB::getQueryLog());

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }
}
