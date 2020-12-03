<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

//use App\Services\BankService;

class LuckyNumberJob extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LuckyNumberJob:tracking';
    protected $redis;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Session lucky game';
//    protected $listPmAccount;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->redis = Redis::connection();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        while (true) {
            //Tao moi 1 session
            $sessionKey = dechex( microtime(true) * 1000 ) . bin2hex( random_bytes(8) );
            Log::channel('daily')->info($sessionKey);
            $this->redis->set('lucky_session', $sessionKey, 'EX', 70);
            sleep(60);
            //Xu ly tra giai
            $this->award();
        }
    }

    private function award() {
        //Tra giai
        sleep(2);
        //clear session
    }

}
