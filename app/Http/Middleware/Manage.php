<?php

namespace App\Http\Middleware;

use Closure;

class Manage
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
        if (\Auth::guard('admin')->check() && \Auth::guard('admin')->user()->active) {
            return $next($request);
        }
        return redirect()->route('login.admin');
    }
}
