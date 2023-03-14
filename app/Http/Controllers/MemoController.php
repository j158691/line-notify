<?php

namespace App\Http\Controllers;

use App\Services\MemoService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemoController extends Controller
{
    /**
     * @var MemoService
     */
    protected $memoService;

    /**
     * MemoController constructor.
     *
     * @param  MemoService  $memoService
     */
    public function __construct(
        MemoService $memoService
    )
    {
        $this->memoService = $memoService;
    }

    public function postMemo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event' => 'required|string',
                'notify_time' => 'required|string'
            ]);
            if ($validator->fails()) {
                $http_code = 400;
                return response()->json([
                    'status' => $http_code,
                    'message' => $validator->messages()
                ], $http_code);
            }

            $event = $request->get('event');
            $notify_time = $request->get('notify_time');
            $user_id = Auth::id();

            $this->memoService->createMemo(
                $user_id,
                $event,
                Carbon::parse($notify_time)->toDateTimeString()
            );

            $http_code = 200;
            $message = 'success';
        } catch (\Exception $exception) {
            $http_code = 500;
            $message = $exception->getMessage();
        }

        return response()->json([
            'status' => $http_code,
            'message' => $message
        ], $http_code);
    }
}
