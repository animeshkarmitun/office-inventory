<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('pages.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check if user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            \Log::warning('Login failed: User not found for email: ' . $request->email);
            return redirect()->route('index')->with(['message' => 'Invalid email or password', 'alert' => 'alert-danger']);
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            \Log::info('Login successful for user: ' . $user->email . ' with role: ' . $user->role);
            
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();
            
            return redirect()->route('dashboard')->with(['message' => 'Login successful', 'alert' => 'alert-success']);
        }

        \Log::warning('Login failed: Invalid password for email: ' . $request->email);
        return redirect()->route('index')->with(['message' => 'Invalid email or password', 'alert' => 'alert-danger']);
    }
}
