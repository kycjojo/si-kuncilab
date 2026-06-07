<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role === 'murid' && $request->user()->is_blocked) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda diblokir karena poin pelanggaran mencapai batas maksimum (>= 5). Hubungi Admin atau Pengawas.',
            ]);
        }

        return $next($request);
    }
}
