<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showAuthPage() {
        return view('welcome');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Login par sirf tab time start ho agar server Online hai
            if (Auth::user()->status == 'Online') {
                TimeLog::updateOrCreate(
                    ['user_id' => Auth::id(), 'log_date' => now()->toDateString()],
                    ['login_at' => now()] 
                );
            }
            
            session()->flash('tracking_started', 'Welcome back!');
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'Online', // Default online
        ]);
        
        Auth::login($user);
        
        TimeLog::create([
            'user_id' => $user->id,
            'log_date' => now()->toDateString(),
            'login_at' => now()
        ]);

        return redirect('/dashboard');
    }

    public function logout(Request $request) {
        $user = Auth::user();
        if ($user) {
            $log = TimeLog::where('user_id', $user->id)->where('log_date', now()->toDateString())->first();
            if ($log && $log->login_at) {
                $seconds = abs(now()->diffInSeconds($log->login_at));
                $log->total_seconds += $seconds;
                $log->logout_at = now();
                $log->login_at = null; 
                $log->save();
            }
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // --- YE RHA MAIN UPDATE SERVER BUTTON KA ---
    public function toggleStatus() {
        $user = User::find(Auth::id());
        $newStatus = ($user->status == 'Online') ? 'Offline' : 'Online';
        $user->status = $newStatus;
        $user->save();

        $log = TimeLog::where('user_id', $user->id)
                    ->where('log_date', now()->toDateString())
                    ->first();

        if ($newStatus == 'Offline') {
            // Agar Offline kiya toh time "Pause" kardo
            if ($log && $log->login_at) {
                $seconds = abs(now()->diffInSeconds($log->login_at));
                $log->total_seconds += $seconds;
                $log->login_at = null; // Time ruk gaya
                $log->save();
            }
        } else {
            // Agar Online kiya toh time "Resume" kardo
            if (!$log) {
                TimeLog::create([
                    'user_id' => $user->id,
                    'log_date' => now()->toDateString(),
                    'login_at' => now()
                ]);
            } else {
                $log->login_at = now(); // Wahin se start jahan ruka tha
                $log->save();
            }
        }

        return back();
    }

    public function update(Request $request) {
        $user = User::find(Auth::id()); 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return back()->with('success', 'Profile Updated!');
    }
}