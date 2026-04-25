<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Redirect users who must change their password before accessing any page.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->must_change_password) {
            // Allow access to the password change page, logout, and profile update
            $allowed = [
                'password.force_change',
                'password.force_change.update',
                'logout',
            ];

            if (!in_array($request->route()->getName(), $allowed)) {
                return redirect()->route('password.force_change');
            }
        }

        return $next($request);
    }
}
