<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class SignInController extends Controller
{
    public function __invoke(UserRequest $request)
    {
        if (!$token = auth()->attempt($request->only('email','password'))){
            return response()->json(['message' => 'email and password wrong'], 401);
        }

        return response(compact('token'));
    }
}
