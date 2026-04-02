<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Доступ запрещён. Требуются права администратора.',
                ], 403);
            }
            abort(403, 'Доступ запрещён. Требуются права администратора.');
        }

        return $next($request);
    }
}
