<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        if (auth()->check()) {
            // Check if the user's type is admin or superadmin
            if (in_array(auth()->user()->user_type, $types)) {
                return $next($request); // User is allowed
            }
        }

        return redirect('/home'); // Redirect to home page if not allowed
    }
}
