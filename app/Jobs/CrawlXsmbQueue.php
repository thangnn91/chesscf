<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Jobs;

/**
 * Description of CrawlVietlotQueue
 *
 * @author ONECONDUCK
 */
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrawlXsmbQueue implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $dialDay;
    protected $dialCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $dialDay, string $code) {
        $this->dialDay = $dialDay;
        $this->dialCode = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        //Save log to db
        try {
            $today = date("Y-m-d");
            $number = date('N', strtotime($today)) + 1;
            Log::channel('daily')->info('Hello. CrawlXsmbQueue. Day: ' . $number);
            if (strpos($this->dialDay, (string) $number) === false) {
                Log::channel('daily')->info('Not dial day: ' . $number);
                return;
            }

            $hasToday = false;
            while (true) {
                $xml = simplexml_load_file('https://xskt.com.vn/rss-feed/mien-bac-xsmb.rss');
                foreach ($xml as $entry) {
                    foreach ($entry->item as $item) {

                        $matches = array();
                        if (preg_match('/(\d{1}|\d{2})-(\d{1}|\d{2})-\d{4}/', (string) $item->link, $matches)) {
                            //dosth
                            $matchDay = trim($matches[0]);
                            $day = date('d/m/Y', strtotime($matchDay));
                            if ($day === date('d/m/Y')) {
                                $hasToday = true;
                            }

                            $dayExist = DB::table('dbo_lottery_result')->where(DB::raw("TRIM(day)"), $day)->where('code', $this->dialCode)->first();
                            if (!$dayExist) {
                                $result = (string) $item->description;
                                $arrayResult = explode(":", $result);
                                $myArray = array();
                                if (count($arrayResult) === 9) {
                                    for ($i = 0; $i < 9; $i++) {
                                        if ($i > 0) {
                                            $itemResult = $arrayResult[$i];
                                            $object = new \stdClass();
                                            $object->prize = ($i - 1);
                                            $object->result = preg_replace('/\s+/', '', $i === 8 ? $itemResult : substr($itemResult, 0, -1));
                                            $myArray[] = $object;
                                        }
                                    }
                                    DB::table('dbo_lottery_result')->insert(
                                            ['day' => $day, 'result' => json_encode($myArray), 'created_at' => date('Y-m-d 18:00:00', strtotime(str_replace('/', '-', $day))), 'code' => $this->dialCode]
                                    );
                                }
                            }
                        }
                    }
                }

                if ($hasToday || (strtotime($today . ' 19:00:00') - time()) < 0) {
                    break;
                }
                sleep(100);
            }
        } catch (Exception $ex) {
            Log::channel('daily')->debug($ex->getMessage());
        }
    }

}
