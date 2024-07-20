<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Str;
use Auth;
class RegisterController extends Controller
{
    //
    // public function showRegistrationForm()
    // {
    //     return view('auth.register');
    // }
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Allow nullable for API, if not provided, use default.jpg
        ]);

        if ($validator->fails()) {
            // Handle validation errors for API
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('users'), $imageName);
            $imageFileName = $imageName;
        } else {
            $imageFileName = 'default.jpg'; 
        }
       $user_data =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_image' => $imageFileName,
            'api_token' => Str::random(60),
        ]);
        // $token = $this->generateToken();
        return response()->json(['message' => 'User registered successfully','user' => $user_data], 201);
    }
    public function loginUser(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        
        // dd('ecefv');
        if ($validator->fails()) {
            // Handle validation errors for API
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'user') {
                $user->api_token = Str::random(60); // Regenerate API token
                $user->save();
                return response()->json(['message' => 'User logged in successfully', 'loginuser'=>$user], 200);
            } elseif ($user->role === 'admin') {
                $user->api_token = Str::random(60); // Regenerate API token
                $user->save();
                return response()->json(['message' => 'Admin logged in successfully','loginuser'=>$user], 200);
            } else {
                Auth::logout();
                return response()->json(['error' => 'You are not authorized to access this area.'], 403);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials. Please try again.'], 401);
        }
    }
    public function showProfile()
    {
        $admin = Auth::user(); 
        return response()->json(['admin' => $admin], 200);
    }
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Auth::user();

        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('users'), $imageName);

            // Delete the previous profile image if it exists and is not the default image
            if ($admin->profile_image && $admin->profile_image !== 'default.jpg') {
                $imagePath = public_path('users') . '/' . $admin->profile_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $admin->profile_image = $imageName;
        }

        // Update admin name if provided
        if ($request->has('name')) {
            $admin->name = $request->name;
        }

        $admin->save();

        return response()->json(['message' => 'Profile updated successfully', 'admin' => $admin], 200);
    }
    public function logoutUser(Request $request)
    {
        
        $user = Auth::user();
        $user->api_token = null; // Invalidate the API token
        $user->save();
        return response()->json(['message' => 'Logout successful.'], 200);
    }
}