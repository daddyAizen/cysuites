<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Assuming User has a 'role' relationship and Role has a 'name' column
        if (!in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
