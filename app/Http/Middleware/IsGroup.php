<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $groups): Response
    {
        $request->user()
            || abort(401, 'Unauthorized');

        $isGroup = $request->user()->usergroup;
        $inGroup = array_map('trim', explode(',', $groups));
        abort_unless(in_array($isGroup, $inGroup, true), 403, 'Oops! Доступ запрещен.');
        return $next($request);
    }
}
