<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AuthController extends Controller
{
    public function login()
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $email = $request->get('email');
        $password = $request->get('password');
        $user = User::where('email', '=', $email)->first();

        if (!$user || !\Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 400);
        }

        $expiration = Carbon();
        $expiration->addHour(2);
        $user->api_token = sha1(str_random(32)) . '.' . sha1(str_random(32));
        $user->api_token_expiration = $expiration->format('Y-m-d H:i:s');
        $user->save();

        return [
            'api_token' => $user->api_token,
            'api_token_expiration' => $user->api_token_expiration
        ];
    }

    public function refreshToken()
    {
        $user = \Auth::user();
        $expiration = new \Carbon\Carbon();
        $expiration->addHour(2);
        $user->api_token = sha1(str_random(32)) . '.' . sha1(str_random(32));
        $user->api_token_expiration = $expiration->format('Y-m-d H:i:s');
        $user->save();
        return [
            'api_token' => $user->api_token,
            'api_token_expiration' => $user->api_token_expiration
        ];
    }
}
