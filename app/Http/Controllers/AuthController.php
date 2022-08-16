<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
