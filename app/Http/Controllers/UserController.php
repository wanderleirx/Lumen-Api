<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\Notification;

class UserController extends Controller
{

    public function getAuthenticatedUser()
    {
        return $request->user();
    }
}
