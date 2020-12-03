<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Utility\CookieHelper;
use App\Jobs\CrawlVietlotQueue;
use App\Jobs\CrawlXsmbQueue;

class LotteryService extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LotteryService:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lottery service';
    protected $cookieHelper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->cookieHelper = new CookieHelper();
    }

    /**
     * Execute the console command.
     * Can cai dat supervisor de run queue
     * link hd: https://fideloper.com/ubuntu-beanstalkd-and-laravel4 =>recommend
     * https://www.phpflow.com/misc/linux-misc/configure-supervisord-linux-laravel-jobs-queue/
     * https://blog.whabash.com/posts/installing-supervisor-manage-laravel-queue-processes-ubuntu
     * https://upnrunn.com/blog/2018/09/how-to-set-up-job-queue-in-laravel/
     * @return mixed
     */
    public function handle() {

        //Lấy danh sách xo so config
        $listPlayer = DB::table('dbo_lottery_config')->get();
        foreach ($listPlayer as $config) {
            if ($config->lottery_code === Config::get('constants.vietlot_code')) {
                dispatch((new CrawlVietlotQueue($config->dial_day, $config->lottery_code))->onQueue('vietlot'));
                //CrawlVietlotQueue::dispatch($config->dial_day);
            } else if ($config->lottery_code === Config::get('constants.xsmb_code')) {
                dispatch((new CrawlXsmbQueue($config->dial_day, $config->lottery_code))->onQueue('xsmb'));
            }
        }
    }

}
