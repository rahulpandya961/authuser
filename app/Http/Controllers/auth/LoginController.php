<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    //
    // public function showAdminLoginForm() {
    //     return view('auth.login');
    // }
    private function generateToken()
    {
        return sha1(time()); // Example of a simple token generation method
    }
    // public function userLogin(Request $request)
    // {
        
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
    
    //     // Attempt to authenticate the user
    //     if (Auth::attempt($credentials)) {
    //         // Check if the authenticated user has the 'user' role
    //         if (Auth::user()->role === 'user') {
    //             return response()->json(['message' => 'User registered successfully','user' => $user_data], 201);
    //         } else {
    //             // If the user does not have the 'user' role, logout and show error
    //             Auth::logout();
    //             return back()->withErrors([
    //                 'email' => 'You are not authorized to access this area.',
    //             ]);
    //         }
    //     } else {
    //         // If authentication fails, show error message
    //         return back()->withErrors([
    //             'email' => 'Invalid credentials. Please try again.',
    //         ]);
    //     }
    // }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
