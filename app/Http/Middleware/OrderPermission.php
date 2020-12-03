<?php

namespace App\Http\Middleware;

use Closure;

class OrderPermission
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
            if (\Auth::guard('admin')->user()->admin)
                return $next($request);
            $group_code = \Auth::guard('admin')->user()->group_code();
            if (strpos($group_code, 'admin') !== false || strpos($group_code, 'bartender') !== false) {
                return $next($request);
            }
        }
        return redirect()->route('index.admin');
    }
}
