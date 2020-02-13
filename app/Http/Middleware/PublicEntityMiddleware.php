<?php

namespace App\Http\Middleware;

use Closure;

class PublicEntityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dd($request->slug);
        return $next($request);
    }
}
