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
use App\Utility\CookieHelper;

class CrawlVietlotQueue implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $dialDay;
    protected $dialCode;
    protected $cookieHelper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $dialDay, string $code) {
        $this->dialDay = $dialDay;
        $this->dialCode = $code;
        $this->cookieHelper = new CookieHelper();
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
            Log::channel('daily')->info('Hello. CrawlVietlotQueue. Day: ' . $number);
            if (strpos($this->dialDay, (string) $number) === false) {
                Log::channel('daily')->info('Not dial day: ' . $number);
                return;
            }
            $cookie_header = array("X-AjaxPro-Method:ServerSideDrawResult");
            $requestData = '{"ORenderInfo":{"SiteId":"main.frontend.vi","SiteAlias":"main.vi","UserSessionId":"","SiteLang":"vi","IsPageDesign":false,"ExtraParam1":"","ExtraParam2":"","ExtraParam3":"","PathRoot":"D:\\Portal\\Vietlott\\Frontend\\Web","HttpRoot":"http://10.98.20.20","MediaPathRoot":"D:\\Portal\\Vietlott\\Media","HttpMediaPathRoot":"https://media.vietlott.vn","TempPathRoot":"","HttpTempPathRoot":"","SiteURL":"","WebPage":null,"WebPathRoot":"D:\\Portal\\Vietlott\\Frontend\\Web","WebHttpRoot":"http://10.98.20.20","SiteName":"Vietlott","OrgPageAlias":null,"PageAlias":null,"FullPageAlias":null,"RefKey":null,"System":0},"Key":"7660ed41","GameDrawId":"","ArrayNumbers":[["","","","","","","","","","","","","","","","","",""],["","","","","","","","","","","","","","","","","",""],["","","","","","","","","","","","","","","","","",""],["","","","","","","","","","","","","","","","","",""],["","","","","","","","","","","","","","","","","",""],["","","","","","","","","","","","","","","","","",""]],"CheckMulti":false,"PageIndex":0}';
            $hasToday = false;
            while (true) {
                $html = $this->cookieHelper->curl_json('https://vietlott.vn/ajaxpro/Vietlott.PlugIn.WebParts.Game645CompareWebPart,Vietlott.PlugIn.WebParts.ashx', $requestData, '', '', '', $cookie_header);
                $responseData = json_decode($html);
                $htmlDom = new \Htmldom($responseData->value->HtmlContent);
                $isFirst = true;
                foreach ($htmlDom->find('table tr') as $tr) {
                    try {
                        if ($tr->parent->tag != 'tbody')
                            continue;
                        if ($isFirst) { // our workaround
                            $day = $tr->find('td', 0)->plaintext;
                            if (trim($day) === date('d/m/Y')) {
                                $hasToday = true;
                            }
                            //Check ngày đã tồn tại trong db
                            $firstDayExist = DB::table('dbo_lottery_result')->where(DB::raw("TRIM(day)"), trim($day))->where('code', $this->dialCode)->first();
                            if (!$firstDayExist) {
                                $result = $tr->find('td', 2);
                                $resultNumber = '';
                                foreach ($result->find('.bong_tron') as $span) {
                                    $resultNumber = $resultNumber . trim($span->plaintext) . '|';
                                }
                                DB::table('dbo_lottery_result')->insert(
                                        ['day' => $day, 'result' => rtrim($resultNumber, "|"), 'created_at' => date('Y-m-d 18:00:00', strtotime(str_replace('/', '-', $day))), 'code' => $this->dialCode]
                                );
                            } else
                                $isFirst = false;
                        } else
                            break;
                    } catch (Exception $ex) {
                        Log::channel('daily')->debug($ex->getMessage());
                    }
                }
                //Da co ngay hom nay hoac time hien tai >19h00 ma ko lay dc ket qua thi break
                if ($hasToday || (strtotime($today . ' 19:00:00') - time()) < 0) {
                    break;
                }
                sleep(100);
            }
        } catch (Exception $ex) {
            Log::channel('daily')->debug($ex->getMessage());
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception) {
        Log::channel('daily')->debug($exception->getMessage());
    }

}
