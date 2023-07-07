<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('signed');
    }

    public function __invoke(User $user, Request $request)
    {
        abort_if($request->missing('cid'), 401);
        $cid = $request->get('cid');

        abort_if(Cache::get($cid) !== $user->id, 401);

        Auth::guard()->login($user, false);
        return redirect(RouteServiceProvider::HOME);
    }
}
