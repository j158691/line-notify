<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param  UserService  $userService
     */
    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function postUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $account = $request->get('account');
            $password = $request->get('password');

            $validator = Validator::make($request->all(), [
                'account' => 'unique:users,account',
            ]);

            if ($validator->fails()) {
                $http_code = 400;
                return response()->json([
                    'status' => $http_code,
                    'message' => $validator->messages()
                ], $http_code);
            }

            $this->userService->createUser(
                $account,
                Hash::make($password),
                ''
            );

            $credentials = $request->only('account', 'password');
            Auth::attempt($credentials);
            $user = $request->user();
            Auth::login($user);

            $http_code = 200;
            $message = 'success';
            DB::commit();
        } catch (\Exception $exception) {
            $http_code = 500;
            $message = $exception->getMessage();
            DB::rollBack();
        }

        return response()->json([
            'status' => $http_code,
            'message' => $message
        ], $http_code);
    }
}
