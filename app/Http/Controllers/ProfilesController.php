<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{   
    
    public function show(User $user) 
    {
        // dd('hello');
        // return $user;
        return response()->json(['profileUser'=> $user]);
    }
}
