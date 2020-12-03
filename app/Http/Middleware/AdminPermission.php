<?php

namespace App\Http\Middleware;

use Closure;
use App\Admin;

class AdminPermission
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
        if (\Auth::guard('admin')->check() && \Auth::guard('admin')->user()->active && \Auth::guard('admin')->user()->admin) {
            return $next($request);
        }
        return redirect()->route('index.admin');
    }
}
