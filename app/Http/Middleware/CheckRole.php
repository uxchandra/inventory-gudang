<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    $user = auth()->user();

    if (!$user) {
        abort(403, 'Unauthorized action: User not authenticated.');
    }

    \Log::info('User Role: ', [$user->role->role]);

    if ($user->role && in_array($user->role->role, $roles)) {
        return $next($request);
    }

    abort(403, 'Unauthorized action: Role not authorized.');
}

}
