<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Closure;

class Tenant
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
        $tenants = Config::get('constants.tenant');
        $urlParts = explode('.', $_SERVER['HTTP_HOST']);
        $subdomain = $urlParts[0];
        if (in_array($subdomain, $tenants)) {
            $databaseName = Config::get('database.connections');
            $currentDbName = $databaseName['mysql']['database'];
            if ($currentDbName !== $subdomain) {
                Config::set("database.connections.mysql", [
                    "driver" => "mysql",
                    "charset" => "utf8mb4",
                    "collation" => "utf8mb4_unicode_ci",
                    "prefix_indexes" => true,
                    "host" => $databaseName['mysql']['host'],
                    "database" => $subdomain,
                    "username" => $databaseName['mysql']['username'],
                    "password" => $databaseName['mysql']['password']
                ]);
            }
        } else
            $subdomain = 'default';
        //Cache thông tin db config
        if (!$request->session()->has('dbo_system_config_data') || $request->session()->get('dbo_system_config_data')['tenant']->value !== $subdomain) {
            $configs = DB::table('dbo_system_config')->get()->keyBy('key');
            $request->session()->put('dbo_system_config_data', $configs);
        }
        return $next($request);
    }
}
