<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            if ($request->expectsJson()) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
