<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    /**
     * CommonController constructor.
     */
    public function __construct(
    )
    {
    }

    /**
     * 取得伺服器時間
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getServerTime(Request $request)
    {
        try {
            $server_time = Carbon::now()->toDateTimeString();

            $http_code = 200;
            $message = $server_time;
        } catch (Exception $exception) {
            $http_code = 500;
            $message = $exception->getMessage();
        }

        return response()->json([
            'status' => $http_code,
            'message' => $message
        ], $http_code);
    }
}
