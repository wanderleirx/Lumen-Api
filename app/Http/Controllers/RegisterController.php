<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterController extends Controller
{

    public function index()
    {
        return 'Index';
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:16|confirmed',
            'redirect' => 'required|url'
        ]);

        $data = $request->all();
        $data['password'] = \Hash::make($data['password']);
        $user = User::create($data);
        $user->verification_token = md5(str_random(16));
        $user->save();

        $redirect = route('verification_account', [
            'token' => $user->verification_token,
            'redirect' => $request->get('redirect')
        ]);
        \Notification::send($user, new \App\Notifications\AccountCreated($user, $redirect));

        return response()->json($user, 201);
    }

    public function accountVerification(Request $request, $token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = true;
        $user->verification_token = null;
        $user->save();
        $redirect = $request->get('redirect');
        return redirect()->to($redirect);
    }

}
