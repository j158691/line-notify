<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LineNotifyService;
use App\Services\UserService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class LineNotifyController extends Controller
{
    /**
     * @var bool
     */
    protected $sslVerify;
    /**
     * @var LineNotifyService
     */
    protected $lineNotifyService;
    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(
        LineNotifyService $lineNotifyService,
        UserService $userService
    )
    {
        $this->lineNotifyService = $lineNotifyService;
        $this->userService = $userService;
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function getAuthorize()
    {
        try {
            $url = $this->lineNotifyService->getAuthorizeCodeUrl();

            return redirect($url);
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse|Redirector
     * @throws GuzzleException
     */
    public function getOauthToken(Request $request)
    {
        try {
            $code = $request->get('code');

            $resultLineNotify = $this->lineNotifyService->postOauthToken($code);
            $access_token = Arr::get($resultLineNotify, 'access_token', '');

            $user_id = Auth::id();
            $resultUser = User::query()->where('id', $user_id)->first();
            $resultUser->line_notify = $access_token;
            $resultUser->save();

            return redirect(env('APP_URL', '').'/memo');
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function deleteRevoke(Request $request)
    {
        try {

            // $this->lineNotifyService->postRevoke($lineNotify);
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @throws GuzzleException
     */
    public function sendNotify(Request $request)
    {
        try {

            $message = "\n我就跟你說ˋˊ";
            $line_notify = 'nNhmxibQH48wpLRGee98NldH5XXzwwedjKzkSnN8isj';
            $this->lineNotifyService->postNotify($message, $line_notify);
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
