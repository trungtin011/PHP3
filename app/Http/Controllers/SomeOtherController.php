<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatHistory;

class SomeOtherController extends Controller
{
    public function someView()
    {
        $chatHistory = Auth::check() ? ChatHistory::where('user_id', Auth::id())->latest()->take(20)->get() : [];
        return view('some.view', compact('chatHistory'));
    }

}