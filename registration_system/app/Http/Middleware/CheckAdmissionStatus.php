<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmissionStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'student') {
            $student = $user->student;

            if (!$student || !$student->isAdmitted()) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your application is still pending review. You will be able to access the portal once an administrator approves your admission.'
                ]);
            }
        }

        return $next($request);
    }
}
