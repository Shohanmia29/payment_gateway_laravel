<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Events\UserActivated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ChecksPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    use ChecksPermission;

    protected $permissionPrefix = 'user';

    protected $mapExtraActionPermission = [
        'portal' => 'user-read',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(User::when($request->filled(['start_date', 'end_date']), function ($query) use ($request) {
                try {
                    $query->whereBetween('created_at', [
                        Carbon::make($request->get('start_date'))->startOfDay(),
                        Carbon::make($request->get('end_date'))->endOfDay()
                    ]);
                } catch (\Exception $exception){}
            }))->addIndexColumn()->toJson();
        }

        return view('admin.user.index');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(User $user, Request $request)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:199'],
            'phone' => ['required', 'string', 'max:199', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        return response()->report($user->forceFill($validated)->update(), 'User updated successfully');
    }

    public function destroy(User $user)
    {
        return response()->report($user->delete(), 'User deleted successfully');
    }

    public function portal(User $user)
    {
        abort_if(!Auth::user()->isA('admin'), 403);
        $cid = uniqid();
        Cache::put($cid, $user->id, 60);
        $url = URL::temporarySignedRoute(
            'portal', now()->addMinute(), ['user' => $user->id, 'cid' => $cid]
        );
        return <<<HTML
<body style="padding: 2rem;">
Open <a href="$url" target="_blank">$url</a> in incognito window.
<script type="text/javascript">
window.onblur = function() {
  window.close();
}
</script>
</body>
HTML;
    }
}
