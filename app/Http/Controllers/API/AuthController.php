<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HelperApi, ImageProcessing;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);//login, register methods won't go through the api guard
    }

    public function login(Request $request)
    {
        try {

            $user = \App\User::where('email', $request->email)->first();
            if ($user && (!Hash::check($request->password, $user->password))) {
                return $this->onError(500, 'invalid password');
            }
            if (!$user){
                return $this->onError(500, 'invalid data');
            }

            $token = JWTAuth::fromUser($user);

            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
            return $this->onSuccessWithToken(200, __('site.login_user'), $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $rules= [
                'email' => ['sometimes', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'min:6'],
            ];

            $validator = Validator::Make($request->all(), $rules);

            if ($validator->fails()){
                return $this->onError(500, 'validation  error');
            }

            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
            $token = JWTAuth::fromUser($user);
            return $this->onSuccessWithToken(200, __('site.create_user'), $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

}
