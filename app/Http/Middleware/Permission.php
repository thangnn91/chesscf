<?php

namespace App\Http\Middleware;

use Closure;

class Permission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (\Auth::guard('user')->check()) {
            if (\Cache::get('system_maintain')) {
                \Auth::guard('user')->logout();
                $request->session()->invalidate();
                if ($request->ajax()) {
                    return response()->json(['error' => 'Error msg'], 503); // Status code here
                }
                return redirect()->route('maintain');
            }
            return $next($request);
        }
        return redirect()->route('home');
    }

}
