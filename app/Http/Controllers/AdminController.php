<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;
class AdminController extends Controller
{
    //
    public function showAdminLoginForm()
    {
        if(!auth()->user()){
            return view('auth.admin.login');
        } else{
            return redirect()->route('admin.dashboard');
        }
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                Auth::user()->api_token = Str::random(60); // Regenerate API token
                Auth::user()->save();
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You are not authorized to access this area.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function adminDashboard()
    {
        if(auth()->user()->role == "admin"){
            return view('auth.admin.dashboard');
        } else{
            return redirect()->route('login');
        }
    }
    public function showProfile()
    {
        $admin = Auth::user();
        return view('auth.admin.profile', compact('admin'));
    }
    public function adminLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.dashboard');
    }
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

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
            $imageFileName = $request->admin_image; 
        }
        $admin->name = $request->name;
        $admin->profile_image = $imageFileName;
        $admin->save();
        
        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully.');
    }
}
