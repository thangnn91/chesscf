<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserLogQueue implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $userLog;

    /**
     * Create a new job instance.
     *
     * @return void
     */
     public function __construct(array $userLog = null) {
        $this->userLog = $userLog;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        //Save log to db
        try {
            DB::table('dbo_user_log')->insert(
                    ['username' => $this->userLog[0], 'action' => $this->userLog[1], 'description' => $this->userLog[2], 'created_at' => date('Y-m-d H:i:s')]
            );
        } catch (Exception $ex) {
            Log::channel('daily')->debug($ex->getMessage());
        }
    }

}
