<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('account', 'password');

            if (Auth::attempt($credentials)) {
                $user = $request->user();
                Auth::login($user);

                return response()->json([
                    'status'  => 200,
                    'message' => 'success',
                ]);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json([
            'status' => 401,
            'error' => 'Unauthorized',
        ], 401);
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();

            return response()->json([
                'status'  => 200,
                'message' => 'Logged out successfully',
            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            return response()->json([
                'status' => 401,
                'error' => 'Unauthorized',
            ], 401);
        }
    }
}
