<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  app('auth')->factory()->getTTL() * 60
        ], 200);
    }

    protected function responseWithSuccess($data, $remark = null)
    {
        return response()->json([
            "status" => true,
            "data" => $data,
            "remark" => $remark,
        ], 200);
    }

    protected function responseWithError($data, $remark = null)
    {
        return response()->json([
            "status" => false,
            "data" => $data,
            "remark" => $remark,
        ], 500);
    }

    protected function flash_message($session, $type='info', $icon='', $messages='', $close=true)
    {
        $button = ($close) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '';
        $icon = ($icon != '') ? $this->fa($icon).'&nbsp; ' : '';

        session()->flash($session, '<div class="alert alert-dismissible alert-'.$type.'">
                                        '.$icon.' '.$messages.' '.$button.'
                                    </div>');
    }

    protected function fa($icon='pencil', $addClass='', $style='')
    {
        return '<i class="fa fa-'.$icon.' '.$addClass.'" style="'.$style.'"></i>';
    }
}
