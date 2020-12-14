<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignOutController extends Controller
{
    public function __invoke()
    {
        \auth()->logout();

        return response()->json([
            'message' => 'User Logout Successful',
            'status_code' => 200
        ],200);
    }
}
