<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordUpdateController extends Controller
{
    public function create()
    {
        return view('admin.password-update.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required|password:admin',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()]
        ]);

        return response()->report(
            Auth::user()->forceFill([
                'password' => Hash::make($validated['password']),
                'remember_token' => Str::random(60),
            ])->save(),
            'Password updated successful'
        );
    }
}
