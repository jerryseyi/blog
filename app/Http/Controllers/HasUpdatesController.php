<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class HasUpdatesController extends Controller
{
    public function index(Thread $thread) {
        // dd($thread->hasUpdatesFor(auth()->user()));
        dd(auth()->user());
        return true;
    }
}
