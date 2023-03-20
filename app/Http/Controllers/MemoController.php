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

    public function getMemos()
    {
        try {
            $user_id = Auth::id();

            // 取得要被發的事件
            $notify_time = Carbon::now()->addHour()->toDateTimeString();
            $resultMemos = $this->memoService->getMemosForRecord($user_id, $notify_time);
            $memos = $resultMemos->sortBy('notify_time')->map(function ($memo) {
                return [
                    'id'          => $memo->id,
                    'event'       => $memo->event,
                    'notify_time' => $memo->notify_time,
                ];
            })->toArray();

            return view('memos', ['memos' => $memos]);
        } catch (\Exception $exception) {
            $http_code = 500;
            $message = $exception->getMessage();
        }

        return response()->json([
            'status' => $http_code,
            'message' => $message
        ], $http_code);
    }

    public function deleteMemo(Request $request)
    {
        try {
            $memo_id = $request->get('memo_id');

            $user_id = Auth::id();

            // 確認是本人的才能刪
            $resultMemo = $this->memoService->getMemoForUser($memo_id, $user_id);
            if (empty($resultMemo)) {
                return response()->json([
                    'status' => 403,
                    'message' => '不要刪別人的'
                ], 403);
            }

            // 刪除
            $resultMemo->delete();

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
