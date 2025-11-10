<?php

namespace WhitePage\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DynamicRouting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is(['admin', 'backend']) || $request->is(['admin/*', 'backend/*'])) {

            // check if logged
            return $next($request);
        }

        abort(404);
    }
}
