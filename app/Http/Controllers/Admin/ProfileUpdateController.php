<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateController extends Controller
{
    public function create()
    {
        return view('admin.profile-update.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable|image|max:2048'
        ]);

        unset($validated['avatar']);
        if ($request->hasFile('avatar')) {
            Image::delete(Auth::user(), 'avatar');
            $validated['avatar'] = Image::store('avatar', 'admin/avatar');
        }

        return response()->report(Auth::user()->update($validated), 'Profile updated successfully');
    }
}
