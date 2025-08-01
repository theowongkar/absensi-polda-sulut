<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->employee && $user->employee->status !== 'Aktif') {
                Auth::logout();

                return redirect()->route('login')->withErrors([
                    'email' => 'Status pegawai tidak aktif, silahkan hubungi admin.',
                ]);
            }
        }

        return $next($request);
    }
}
