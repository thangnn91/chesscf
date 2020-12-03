<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdmin
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
        if (\Auth::guard('admin')->check()) {
            if (\Auth::guard('admin')->user()->active) {
                if (\Auth::guard('admin')->user()->is_super_admin)
                    return $next($request);
                return redirect()->route('error', ['back_url' => route('index.admin')]);
            }
        }
        return redirect()->route('home');
    }
}
