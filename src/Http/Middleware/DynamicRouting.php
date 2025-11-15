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

            if (auth()->check()) {
                return $next($request);
            }

            return redirect()->guest(route('whitepage.login'));
        }

        abort(404);
    }
}
