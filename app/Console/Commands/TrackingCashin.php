<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

//use App\Services\BankService;

class TrackingCashin extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TrackingCashin:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tracking cashin status';

//    protected $listPmAccount;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
//        $this->listPmAccount = DB::table('dbo_bankaccount')->where('bankcode', 'pm')->orWhere('bankcode', '=', 'pme')->get();
//        foreach ($this->listPmAccount as $item) {
//            $item->password = decrypt($item->password);
//        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $count = 0;
        $interval = 15;
        while ($count < (60 / $interval)) {
            $startTime = Carbon::now();
            $this->trackingCashin();
            $endTime = Carbon::now();
            $totalDuration = $endTime->diffInSeconds($startTime);
            if ($totalDuration > 0) {
                $count += $totalDuration;
            } else {
                $count++;
            }
            sleep($interval);
        }
    }

    private function trackingCashin() {
        //Lay danh sach nap tien, trang thai la 21:PM callback success ,22:Nhận đc VND/product
        $listTracking = DB::table('dbo_payment_order')->where('serviceid', Config::get('constants.cashin_service_id'))
                        ->where('status', Config::get('constants.cashin_payment_ok'))
                        ->orWhere('status', Config::get('constants.cashin_received'))->get();
        if ($listTracking->count() > 0) {
            foreach ($listTracking as $order) {
                if ($order->status == Config::get('constants.cashin_payment_ok') && ($order->productcode === 'pm' || $order->productcode === 'pme')) {
                    $timeDiff = abs(time() - strtotime($order->created_at));
                    //Neu ma tu luc khoi tao den hien tai > 24h=86400s thi cho sang nghi van
                    if ($timeDiff > 86400) {
                        DB::table('dbo_payment_order')
                                ->where('id', $order->id)
                                ->update(['status' => Config::get('constants.cashin_pending'), 'description' => 'Timediff is too much']);
                        continue;
                    }
                    //$pmAccount = $this->listPmAccount->firstWhere('id', $order->bankaccount_id);
                    $pmAccount = DB::table('dbo_bankaccount')->where('id', $order->bankaccount_id)->first();
                    if (!$pmAccount) {
                        //Log
                        Log::channel('daily')->info("Can't find pmAccount>>order:$order->id>>bankaccountid: $order->bankaccount_id");
                        continue;
                    }

                    $pmAccount->password = decrypt($pmAccount->password);

                    $start_day = date("d", strtotime("-1 day"));
                    $start_month = date("m", strtotime("-1 day"));
                    $start_year = date("Y", strtotime("-1 day"));
                    $end_day = date("d");
                    $end_month = date("m");
                    $end_year = date("Y");
                    $f = fopen("https://perfectmoney.is/acct/historycsv.asp?startmonth=" . $start_month . "&startday=" . $start_day . "&startyear=" . $start_year . "&endmonth=" . $end_month . "&endday=" . $end_day . "&endyear=" . $end_year . "&AccountID=" . ($pmAccount->username) . "&PassPhrase=" . ($pmAccount->password), 'rb');

                    if ($f === false) {
                        Log::channel('daily')->info("bmlog: tracking >>sign invalid>>can't fopen");
                        continue;
                    }
                    // getting data to array (line per item)
                    $lines = array();
                    while (!feof($f))
                        array_push($lines, trim(fgets($f)));
                    fclose($f);

                    $result = array();
                    $n = count($lines);
                    for ($i = 1; $i < $n; $i++) {
                        $item = explode(",", $lines[$i], 10);
                        // print_r($item);
                        if (count($item) != 10)
                            continue; // line is invalid - pass to next one

                        $item_named = array();
                        $item_named['Time'] = $item[0];
                        $item_named['Type'] = $item[1];
                        $item_named['Batch'] = $item[2];
                        $item_named['Currency'] = $item[3];
                        $item_named['Amount'] = $item[4];
                        $item_named['Fee'] = $item[5];
                        $item_named['Payer Account'] = trim($item[6]);
                        $item_named['Payee Account'] = trim($item[7]);
                        $item_named['Payment ID'] = trim($item[8]);
                        $item_named['Memo'] = $item[9];

                        array_push($result, $item_named);

                        if ($item_named['Type'] == "Income" && ($order->memory_id . '.' . $order->id) == $item_named['Payment ID'] && ($order->productcode === 'pm' ? 'USD' : 'EUR') === $item_named["Currency"]) {
                            if ($order->totalproduct == $item_named['Amount']) {
                                //Nap tien cho tk, Chuyen trang thai sang 24: Nap tien thanh cong
                                try {
                                    DB::beginTransaction();
                                    //Lay so du hien 
                                    $oldBalance = DB::table('dbo_balance')->where('userid', $order->userid)->first();
                                    if (!$oldBalance) {
                                        Log::channel('daily')->info("Can't find user balance >>order:$order->id>>uid: $order->userid");
                                        break;
                                    }
                                    DB::table('dbo_balance')->where('userid', $order->userid)->update(['realbalance' => ($oldBalance->realbalance + $order->grandamount)]);

                                    //Chuyen order sang 24
                                    DB::table('dbo_payment_order')->where('id', $order->id)->update(['status' => Config::get('constants.cashin_success'),
                                        'description' => 'Cronjob cashin successfully', 'bank_batch_id' => $item_named['Batch']]);

                                    DB::commit();
                                } catch (Exception $ex) {
                                    Log::channel('daily')->info("Ex: " . $ex->getMessage());
                                    DB::rollback();
                                }

                                try {
                                    //Neu co follow
                                    $cashinUser = DB::table('dbo_user')->where('id', $order->userid)->first();
                                    if ($cashinUser) {
                                        $followId = $cashinUser->follow_id;
                                        //Lay thong tin user dc follow                                       
                                        if ($followId) {
                                            $followUser = DB::table('dbo_user')->where('ref_id', $followId)->first();
                                            //Tim trong bang ref_balance
                                            $freezeBalance = DB::table('dbo_freeze_balance')->where('user_id', $order->userid)->first();
                                            //Neu ko ton tai thi tao
                                            if (!$freezeBalance) {
                                                $bonusMoney = (int) Config::get('app.bonus_percent') * $order->grandamount / 100;
                                                DB::table('dbo_freeze_balance')->insert(
                                                        ['follow_user_id' => $followUser->id,
                                                            'user_id' => $order->userid,
                                                            'bonus_money' => $bonusMoney,
                                                            'created_at' => date('Y-m-d H:i:s'),
                                                            'description' => "Bạn nhận được " . $bonusMoney / 1000 . " LTR từ tài khoản " . $cashinUser->username,
                                                            'ref_payment_id' => $order->id
                                                        ]
                                                );
                                            }
                                        }
                                    }
                                } catch (Exception $ex) {
                                    Log::channel('daily')->debug($ex->getMessage());
                                }
                            } else {
                                //So du ko hop le, chuyen trang thai don hang sang nghi van
                                DB::table('dbo_payment_order')->where('id', $order->id)->update(['status' => Config::get('constants.cashin_pending'), 'description' => 'Amount invalid, please check again']);
                            }
                            break;
                        }
                    }
                }
            }
        }
    }

}
