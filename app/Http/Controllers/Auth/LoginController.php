<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {

        //sign user in
        $credentials = $request->only('username', 'password');
        Auth::attempt($credentials);
        

        //redirect
        return redirect()->route('home');
    }
}
