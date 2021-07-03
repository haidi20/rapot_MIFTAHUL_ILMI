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
}
