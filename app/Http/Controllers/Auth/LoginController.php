<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {

        //sign user in
        $credentials = $request->only('loginID', 'password');
        
        $success = Auth::attempt($credentials);

        // attempt only checks with email, pwd. so i manually check the username/password combination
        if ($success){
            return redirect()->route('home');
        } else {
            
            $user = User::where('username', $credentials['loginID'])->first();
            if ($user && Hash::check($credentials['password'], $user->password)){
                Auth::login($user);
                return redirect()->route('home');
            }

        }

        // If it reaches here, means user with credentials not found. Throw error back to the blade template
        throw ValidationException::withMessages([
            'invalid' => 'Oops! We couldn’t find a match. Please check your credentials and try again.'
        ]);
    }
}
