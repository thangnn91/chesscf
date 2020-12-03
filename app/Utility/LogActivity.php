<?php

namespace App\Utility;

use Illuminate\Support\Facades\DB;

class LogActivity
{
    public static function add_log($actor, $description, $old_data = null, $new_data = null)
    {
        try {
            $log = [];
            $log['actor'] = $actor;
            $log['description'] = $description;
            $log['old_data'] = $old_data;
            $log['new_data'] = $new_data;
            DB::table('dbo_log')->insert($log);
        } catch (\Exception $ex) {
        }
    }
}
