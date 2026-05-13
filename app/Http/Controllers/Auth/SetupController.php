<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SetupController extends Controller
{
    public function showForm()
    {
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login')->with('info', 'Admin already exists. Please log in.');
        }
        return view('auth.setup');
    }

    public function create(Request $request)
    {
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:255|unique:users',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Admin account created! Welcome to QAMS.');
    }
}
