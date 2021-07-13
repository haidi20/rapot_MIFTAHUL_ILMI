<?php

namespace App\Http\Controllers;

use App\Models\AbsenType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class MasterAbsenTypeController extends Controller
{

    public function index() {
        $action = route('masterAbsenType.store');

        return view('master_absen_type', compact('action'));
    }

    public function ajaxRead() {
        $iTbl = AbsenType::orderBy('name_absen_type');

        if(request("search") != null) {
            $iTbl = $iTbl->where('is_deleted', 0)
                        ->Where('name_absen_type', 'like', '%'.request('search').'%')
                        ->orWhere('description', 'like', '%'.request('search').'%');
        }

        $total = $iTbl->count();
        $data = $iTbl->where('is_deleted', 0)->skip(request('offset'))->take(request('limit'))->get();

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = AbsenType::where('is_deleted', 0)->orderBy('name_absen_type');

        if(request("search") != null) {
           $iTbl = $iTbl->orWhere('name_absen_type', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->where('is_deleted', 0)->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function create() {
        return $this->form();
    }

    public function edit($id) {
        return $this->form($id);
    }

    private function form($id = null) {

        $absenType = AbsenType::where('id', $id)->first();
        if($absenType)
		{
			$action = route('masterAbsenType.update',$id);
			session()->flash('_old_input', $absenType);
		}else {
            $action = route('masterAbsenType.store');
        }

		// $categories = $this->category->pluck('name', 'id');
		return view('master_absen_type_form', compact('action'));
    }

    public function store() {
        // return request()->all();

        try {
            DB::beginTransaction();

            $absenType = DB::table("absen_type");
            $id = (string) Uuid::generate();

            $absenType->insert(array(
                "id" => $id,
                "name_absen_type" => request('name_absen_type'),
                "description" => request('description'),
                "created_at" => Carbon::now(),
            ));

            flash_message('message', 'success', 'check', 'Data telah dikirim');

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            flash_message('message', 'danger', 'close', 'Data gagal dikirim');
        }

        return redirect()->route('masterAbsenType');
    }

    public function update($id) {
        try {
            DB::beginTransaction();

            $absenType = DB::table("absen_type")->where('id', $id);

            $absenType->update(array(
                "name_absen_type" => request('name_absen_type'),
                "description" => request('description'),
                "updated_at" => Carbon::now(),
            ));

            flash_message('message', 'info', 'check', 'Data telah diperbaharui');
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            flash_message('message', 'danger', 'close', 'Data gagal diperbaharui');
        }

        return redirect()->route('masterAbsenType');
    }

    public function delete($id) {
        $absenType = DB::table("absen_type")->where('id', $id);

         $absenType->update(array(
            "is_deleted" => 1,
        ));

        flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('masterAbsenType');
    }
}
