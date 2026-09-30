<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDeposit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Exclude common users or admins from deposit requirement
        if ($user && ! $user->hasRole('administrator') && ($user->hasRole('driver') || $user->hasRole('merchant'))) {
            // If they are not subscribed (haven't paid deposit)
            if (! $user->is_subscribed) {
                // To prevent redirect loop if the route itself is subscription or other allowed routes
                $allowedRoutes = ['subscription', 'wallet', 'logout', 'profile.edit', 'profile.update', 'profile.destroy', 'verification', 'driver.verification', 'dashboard', 'home'];

                if (! in_array($request->route()->getName(), $allowedRoutes)) {
                    return redirect()->route('subscription')->with('error', 'Wajib menyelesaikan Deposit Jaminan untuk mengakses fitur aplikasi.');
                }
            }
        }

        return $next($request);
    }
}
