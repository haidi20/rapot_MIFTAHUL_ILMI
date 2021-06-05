<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class MasterClassController extends Controller
{

    public function index() {
        $action = route('masterClass.store');

        return view('master_class', compact('action'));
    }

    public function ajaxRead() {
        $iTbl = ClassRoom::where('is_deleted', 0)->orderBy('name_class_room');

        if(request("search") != null) {
            $iTbl = $iTbl->where('name_class_room', 'like', '%'.request('search').'%')
                        ->orWhere('description', 'like', '%'.request('search').'%')
                        ->orWhere('name_speaker', 'like', '%'.request('search').'%');
        }

        $total = $iTbl->count();
        $data = $iTbl->skip(request('offset'))->take(request('limit'))->get();

        return response()->json( [ "rows" => $data, "data" => $iTbl, "total" => $total, "offset" => request('offset'), "limit" => request('limit'), "search" => request('search')]);
    }

    public function ajaxReadTypeahead() {
        $iTbl = ClassRoom::where('is_deleted', 0)->orderBy('name_class_room');

        if(request("search") != null) {
            $iTbl = $iTbl->where('name_class_room', 'like', '%'.request('search').'%')
                        ->orWhere('description', 'like', '%'.request('search').'%')
                        ->orWhere('name_speaker', 'like', '%'.request('search').'%');
        }

        $iTbl = $iTbl->get();

        return response()->json( ["data" => $iTbl]);
    }

    public function create() {
        return $this->form();
    }

    public function edit($id) {
        return $this->form($id);
    }

    private function form($id = null) {

        $classRoom = ClassRoom::where('id', $id)->first();
        if($classRoom)
		{
			$action = route('masterClass.update',$id);
			session()->flash('_old_input', $classRoom);
		}else {
            $action = route('masterClass.store');
        }

		// $categories = $this->category->pluck('name', 'id');
		return view('master_class_form', compact('action'));
    }

    public function store() {
        // return request()->all();

        try {
            DB::beginTransaction();

            $classRoom = DB::table("class_room");
            $id = (string) Uuid::generate();

            $classRoom->insert(array(
                "id" => $id,
                "name_class_room" => request('name_class_room'),
                "description" => request('description'),
                "name_speaker" => request('name_speaker'),
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

        return redirect()->route('masterClass');
    }

    public function update($id) {
        try {
            DB::beginTransaction();

            $classRoom = DB::table("class_room")->where('id', $id);

            $classRoom->update(array(
                "name_class_room" => request('name_class_room'),
                "description" => request('description'),
                "name_speaker" => request('name_speaker'),
                "updated_at" => Carbon::now(),
            ));

            flash_message('message', 'info', 'check', 'Data telah disimpan');
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            flash_message('message', 'danger', 'close', 'Data gagal di disimpan');
        }

        return redirect()->route('masterClass');
    }

    public function delete($id) {
        $classRoom = DB::table("class_room")->where('id', $id);

         $classRoom->update(array(
            "is_deleted" => 1,
        ));

        flash_message('message', 'success', 'check', 'Data telah dihapus');
        return redirect()->route('masterClass');
    }
}
