<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Str;
use Auth;
class UserController extends Controller
{
    //
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,user',
            'profile_image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('users'), $imageName);
            $imageFileName = $imageName;
        } else {
            $imageFileName = 'default.jpg'; 
        }
        $user_data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_image' => $imageFileName,
            'api_token' => Str::random(60),
        ]);
        if($request->role == 'admin'){
            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } else{
            return redirect()->route('user.login')->with('success', 'Registration successful! Please login.');
        }
    }
    public function showLoginForm() {
        
        if(!auth()->user()){
            return view('auth.user.login');
        } else{
            return redirect()->route('auth.user.dashboard');
        }
    }
    public function userLogin(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'user') {
                Auth::user()->api_token = Str::random(60); 
                Auth::user()->save();
                return redirect()->route('auth.user.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You are not authorized to access this area.',
                ]);
            }
        } else {
            // If authentication fails, show error message
            return back()->withErrors([
                'email' => 'Invalid credentials. Please try again.',
            ]);
        }
    }
    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.user.profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        // Update name
        
        
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('users'), $imageName);
            $imageFileName = $imageName;
        } else {
            $imageFileName = $request->user_image; 
        }
        $user->name = $request->name;
        $user->profile_image = $imageFileName;
        $user->save();
        
        return redirect()->route('auth.user.dashboard')->with('success', 'Profile updated successfully.');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
    public function userDashboard()
    {
        if(auth()->user()->role == 'user'){
            return view('auth.user.dashboard');
        } else{
            return view('auth.user.login');
        }
    }
}
