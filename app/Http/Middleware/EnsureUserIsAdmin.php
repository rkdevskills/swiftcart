<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            abort(403);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}
