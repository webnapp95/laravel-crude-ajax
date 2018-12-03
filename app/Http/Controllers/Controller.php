<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseError($params = []) {
        return response()->json([
            'code' => -1,
            'msg' => 'Error',
            'data' => $params
        ]);
    }

    public function responseSuccess($params = []) {
        return response()->json([
            'code' => 0,
            'msg' => 'Success',
            'data' => $params
        ]);
    }
}
