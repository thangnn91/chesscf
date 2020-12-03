<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Utility\CookieHelper;
use App\Utility\StringHelper;

/**
 * Description of AwardService
 * Trao giải, service chạy vào 19h00 hàng ngày
 * @author ONECONDUCK
 */
class AwardService extends Command {

    //put your code here
    protected $signature = 'AwardService:tracking';
    protected $description = 'Award service';

    protected $stringHelper;
    
    public function __construct() {
        parent::__construct();
        $this->stringHelper = new StringHelper();
    }

    public function handle() {

        $listPlayer = DB::table('dbo_lottery')->where('is_checked', 0)->get();

        if ($listPlayer->count() > 0) {
            $prizeConfig = [
                (object) [
                    'desc' => 'Giải ĐB',
                    'value' => -1
                ],
                (object) [
                    'desc' => 'Giải nhất',
                    'value' => -2
                ],
                (object) [
                    'desc' => 'Giải nhì',
                    'value' => 5000000
                ],
                (object) [
                    'desc' => 'Giải ba',
                    'value' => 2000000
                ],
                (object) [
                    'desc' => 'Giải tư',
                    'value' => 400000
                ],
                (object) [
                    'desc' => 'Giải năm',
                    'value' => 200000
                ],
                (object) [
                    'desc' => 'Giải sáu',
                    'value' => 100000
                ],
                (object) [
                    'desc' => 'Giải bảy',
                    'value' => 40000
                ]
            ];

            $prizeLotoConfig = [
                (object) [
                    'code' => Config::get('constants.demienbac_code'),
                    'price' => 1000,
                    'value' => 70000
                ],
                (object) [
                    'code' => Config::get('constants.lomienbac_code'),
                    'price' => 20000,
                    'value' => 80000
                ],
                (object) [
                    'code' => Config::get('constants.lomienbac2_code'),
                    'price' => 1000,
                    'value' => 10000
                ],
                (object) [
                    'code' => Config::get('constants.lomienbac3_code'),
                    'price' => 1000,
                    'value' => 40000
                ],
                (object) [
                    'code' => Config::get('constants.lomienbac4_code'),
                    'price' => 1000,
                    'value' => 100000
                ]
            ];

            $listResult = array();
            foreach ($listPlayer as $item) {
                $day = $item->day;
                $code = $item->code;
                if ($code === Config::get('constants.demienbac_code') || $code === Config::get('constants.lomienbac_code') || $code === Config::get('constants.lomienbac2_code') ||
                        $code === Config::get('constants.lomienbac3_code') || $code === Config::get('constants.lomienbac4_code'))
                    $code = Config::get('constants.xsmb_code');

                $objectKey = trim($day) . $code;
                $elemment = $this->checkObjectExist($listResult, $objectKey);
                if (!$elemment) {
                    $record = DB::table('dbo_lottery_result')->where(DB::raw("TRIM(day)"), trim($day))->where('code', $code)->first();
                    if (!$record || !$record->result) {
                        Log::channel('daily')->info('!$record || !$record->result. Day:' . $day);
                        continue;
                    }
                    //xu ly push vao mang
                    $currentObject = new \stdClass();
                    $currentObject->key = $objectKey;
                    $currentObject->result = $record->result;
                    if ($code === Config::get('constants.xsmb_code')) {
                        $resultData = json_decode($record->result, true);
                        $perfectPrize = '';
                        $prize = array();
                        foreach ($resultData as $item2) {
                            $itemResult = $item2['result'];
                            $itemPrize = $item2['prize'];
                            if (strpos($itemResult, '-') === false) {
                                if ($itemPrize === 0)
                                    $perfectPrize = substr($itemResult, -2);
                                else {
                                    $prize[] = substr($itemResult, -2);
                                }
                            } else {
                                $arrayPrize = explode("-", $itemResult);
                                foreach ($arrayPrize as $item3) {
                                    $prize[] = substr($item3, -2);
                                }
                            }
                        }
                        $currentObject->perfect_result = $perfectPrize;
                        $prize[] = $perfectPrize;
                        $currentObject->loto_result = $prize;
                    }
                    $listResult[] = $currentObject;
                    $elemment = $currentObject;
                }


                if ($item->code === Config::get('constants.vietlot_code')) {
                    $this->awardVietlot($item, $elemment->result);
                } else if ($item->code === Config::get('constants.xsmb_code')) {
                    $this->awardXsmb($item, $elemment->result, $prizeConfig);
                }
                //Loto
                else {
                    $this->awardLoto($item, $elemment->loto_result, $currentObject->perfect_result, $prizeLotoConfig);
                }
            }
        }
    }

    //$item: row dang duyet
    //$record: row ket qua map voi ban ghi dang duyet
    private function awardVietlot($item, $record) {
        $arrayResult = explode('|', $record);
        if (count($arrayResult) !== 6) {
            Log::channel('daily')->info('count($arrayResult) !== 6. Day:' . $item->day);
            return;
        }

        $arrayPicked = explode('|', $item->picked_number);

        $wow = 0;
        foreach ($arrayPicked as $picked) {
            if (in_array($picked, $arrayResult))
                $wow++;
        }
        //Trao giai
        //bingo
        if ($wow === 6) {
            //Cho outmoney = -1, nhung ko + so du, de check tay
            DB::table('dbo_lottery')->where('id', $item->id)->update(['is_checked' => 1, 'out_money' => -1]);
        } else if ($wow === 5) {
            //10tr
            try {
                DB::beginTransaction();
                DB::table('dbo_lottery')->where('id', $item->id)->update(['is_checked' => 1, 'out_money' => 10000000]);
                $oldBalance = DB::table('dbo_balance')->where('userid', $item->userid)->first();
                if (!$oldBalance) {
                    Log::channel('daily')->info('$oldBalance not found. userid:' . $item->userid);
                    DB::commit();
                } else {
                    //Tao payment order
                    DB::table('dbo_payment_order')->insert([
                        'userid' => $item->userid,
                        'status' => Config::get('constants.payment_confirm'),
                        'serviceid' => Config::get('constants.payment_service_id'),
                        'productcode' => 'ltr',
                        'totalproduct' => 10000000 / 1000,
                        'grandamount' => 10000000,
                        'memory_id' => $this->stringHelper->randomString(),
                        'confirmation' => 0,
                        'client_ip' => '::cron::award_vietlot',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('dbo_balance')->where('userid', $item->userid)->update(['realbalance' => ($oldBalance->realbalance + 10000000)]);
                    DB::commit();
                }
            } catch (Exception $ex) {
                Log::channel('daily')->debug($ex->getMessage());
                DB::rollback();
            }
        } else if ($wow === 4) {
            //300k
            try {
                DB::beginTransaction();
                DB::table('dbo_lottery')->where('id', $item->id)->update(['is_checked' => 1, 'out_money' => 300000]);
                $oldBalance = DB::table('dbo_balance')->where('userid', $item->userid)->first();
                if (!$oldBalance) {
                    Log::channel('daily')->info('$oldBalance not found. userid:' . $item->userid);
                    DB::commit();
                } else {
                    DB::table('dbo_payment_order')->insert([
                        'userid' => $item->userid,
                        'status' => Config::get('constants.payment_confirm'),
                        'serviceid' => Config::get('constants.payment_service_id'),
                        'productcode' => 'ltr',
                        'totalproduct' => 300000 / 1000,
                        'grandamount' => 300000,
                        'memory_id' => $this->stringHelper->randomString(),
                        'confirmation' => 0,
                        'client_ip' => '::cron::award_vietlot',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('dbo_balance')->where('userid', $item->userid)->update(['realbalance' => ($oldBalance->realbalance + 300000)]);
                    DB::commit();
                }
            } catch (Exception $ex) {
                Log::channel('daily')->debug($ex->getMessage());
                DB::rollback();
            }
        } else if ($wow === 3) {
            //30k
            try {
                DB::beginTransaction();
                DB::table('dbo_lottery')->where('id', $item->id)->update(['is_checked' => 1, 'out_money' => 30000]);
                $oldBalance = DB::table('dbo_balance')->where('userid', $item->userid)->first();
                if (!$oldBalance) {
                    Log::channel('daily')->info('$oldBalance not found. userid:' . $item->userid);
                    DB::commit();
                } else {
                    DB::table('dbo_payment_order')->insert([
                        'userid' => $item->userid,
                        'status' => Config::get('constants.payment_confirm'),
                        'serviceid' => Config::get('constants.payment_service_id'),
                        'productcode' => 'ltr',
                        'totalproduct' => 30000 / 1000,
                        'grandamount' => 30000,
                        'memory_id' => $this->stringHelper->randomString(),
                        'confirmation' => 0,
                        'client_ip' => '::cron::award_vietlot',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('dbo_balance')->where('userid', $item->userid)->update(['realbalance' => ($oldBalance->realbalance + 30000)]);
                    DB::commit();
                }
            } catch (Exception $ex) {
                Log::channel('daily')->debug($ex->getMessage());
                DB::rollback();
            }
        } else {
            DB::table('dbo_lottery')->where('id', $item->id)->update(['is_checked' => 1]);
        }
    }

    private function awardXsmb($row, $record, $prizeConfig) {

        $resultData = json_decode($record, true);

        $checked = false;
        $perfectPrize = '';
        foreach ($resultData as $item) {
            $itemResult = $item['result'];
            $itemPrize = $item['prize'];
            //ko bao gom - thi so sanh luon (giai dac biet va giai nhat)
            if (strpos($itemResult, '-') === false) {
                if ($itemPrize === 0)
                    $perfectPrize = substr($itemResult, -2);

                if ($itemResult === $row->picked_number) {
                    //Ko tra thuong ngay ma de admin check roi tra thuong = tay
                    DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1, 'out_money' => $prizeConfig[$itemPrize]->value]);
                    $checked = true;
                }
            } else {
                $arrayPrize = explode("-", $itemResult);
                if (strlen($arrayPrize[0]) === strlen($row->picked_number)) {
                    if (in_array($row->picked_number, $arrayPrize)) {
                        //Cap nhat va tra giai

                        try {
                            DB::beginTransaction();
                            DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1, 'out_money' => $prizeConfig[$itemPrize]->value]);
                            $oldBalance = DB::table('dbo_balance')->where('userid', $row->userid)->first();
                            if (!$oldBalance) {
                                Log::channel('daily')->info('$oldBalance not found. userid:' . $row->userid);
                                DB::commit();
                            } else {
                                DB::table('dbo_payment_order')->insert([
                                    'userid' => $row->userid,
                                    'status' => Config::get('constants.payment_confirm'),
                                    'serviceid' => Config::get('constants.payment_service_id'),
                                    'productcode' => 'ltr',
                                    'totalproduct' => $prizeConfig[$itemPrize]->value / 1000,
                                    'grandamount' => $prizeConfig[$itemPrize]->value,
                                    'memory_id' => $this->stringHelper->randomString(),
                                    'confirmation' => 0,
                                    'client_ip' => '::cron::award_xsmb',
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                                DB::table('dbo_balance')->where('userid', $row->userid)->update(['realbalance' => ($oldBalance->realbalance + $prizeConfig[$itemPrize]->value)]);
                                DB::commit();
                            }
                            $checked = true;
                        } catch (Exception $ex) {
                            Log::channel('daily')->debug($ex->getMessage());
                            DB::rollback();
                        }
                    }
                } else {
                    $splitPickedNumber = substr($row->picked_number, (strlen($arrayPrize[0]) * -1));
                    if (in_array($splitPickedNumber, $arrayPrize)) {
                        //Cap nhat va tra giai
                        try {
                            DB::beginTransaction();
                            DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1, 'out_money' => $prizeConfig[$itemPrize]->value]);
                            $oldBalance = DB::table('dbo_balance')->where('userid', $row->userid)->first();
                            if (!$oldBalance) {
                                Log::channel('daily')->info('$oldBalance not found. userid:' . $row->userid);
                                DB::commit();
                            } else {
                                DB::table('dbo_payment_order')->insert([
                                    'userid' => $row->userid,
                                    'status' => Config::get('constants.payment_confirm'),
                                    'serviceid' => Config::get('constants.payment_service_id'),
                                    'productcode' => 'ltr',
                                    'totalproduct' => $prizeConfig[$itemPrize]->value / 1000,
                                    'grandamount' => $prizeConfig[$itemPrize]->value,
                                    'memory_id' => $this->stringHelper->randomString(),
                                    'confirmation' => 0,
                                    'client_ip' => '::cron::award_xsmb',
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                                DB::table('dbo_balance')->where('userid', $row->userid)->update(['realbalance' => ($oldBalance->realbalance + $prizeConfig[$itemPrize]->value)]);
                                DB::commit();
                            }
                            $checked = true;
                        } catch (Exception $ex) {
                            Log::channel('daily')->debug($ex->getMessage());
                            DB::rollback();
                        }
                    }
                }
            }
        }
        //Trung voi 2 so cuoi giai dac biet
        if (substr($row->picked_number, -2) === $perfectPrize) {
            //Cap nhat va tra giai
            Log::channel('daily')->info('Trung giai phu cua giai dac biet>> id:' . $row->id);
            try {
                DB::beginTransaction();
                DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1, 'out_money' => $prizeConfig[7]->value]);
                $oldBalance = DB::table('dbo_balance')->where('userid', $row->userid)->first();
                if (!$oldBalance) {
                    Log::channel('daily')->info('$oldBalance not found. userid:' . $row->userid);
                    DB::commit();
                } else {
                    DB::table('dbo_payment_order')->insert([
                        'userid' => $row->userid,
                        'status' => Config::get('constants.payment_confirm'),
                        'serviceid' => Config::get('constants.payment_service_id'),
                        'productcode' => 'ltr',
                        'totalproduct' => $prizeConfig[7]->value / 1000,
                        'grandamount' => $prizeConfig[7]->value,
                        'memory_id' => $this->stringHelper->randomString(),
                        'confirmation' => 0,
                        'client_ip' => '::cron::award_xsmb',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('dbo_balance')->where('userid', $row->userid)->update(['realbalance' => ($oldBalance->realbalance + $prizeConfig[7]->value)]);
                    DB::commit();
                }
                $checked = true;
            } catch (Exception $ex) {
                Log::channel('daily')->debug($ex->getMessage());
                DB::rollback();
            }
        }

        if (!$checked) {
            DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1]);
        }
    }

    private function awardLoto($row, $result, $perfectResult, $prizeConfig) {

        $collectPrize = collect($prizeConfig);
        $totalBingo = 0;
        if ($row->code === Config::get('constants.demienbac_code')) {
            //Trung de, trao giai
            if ($row->picked_number === $perfectResult) {
                $objectPrize = $collectPrize->where('code', $row->code)->first();
                if ($objectPrize) {
                    $point = $row->in_money / $objectPrize->price;
                    $totalBingo = $point * $objectPrize->value;
                }
            }
        } else if ($row->code === Config::get('constants.lomienbac_code')) {
            //Trung lo trao giai
            $pickedNumber = $row->picked_number;
            $count = collect($result)->filter(function ($object) use ($pickedNumber) {
                        return $object === $pickedNumber;
                    })->count();
            //Trao giai
            if ($count > 0) {
                $objectPrize = $collectPrize->where('code', $row->code)->first();
                if ($objectPrize) {
                    $point = $row->in_money / $objectPrize->price;
                    $totalBingo = $count * $point * $objectPrize->value;
                }
            }
        } else if ($row->code === Config::get('constants.lomienbac2_code')) {
            //Trung lo trao giai
            $arrayPickedNumber = explode('|', $row->picked_number);
            $filtered = array_diff($arrayPickedNumber, $result);
            //Trao giai
            if (!count($filtered)) {
                $objectPrize = $collectPrize->where('code', $row->code)->first();
                if ($objectPrize) {
                    $point = $row->in_money / $objectPrize->price;
                    $totalBingo = $point * $objectPrize->value;
                }
            }
        }
        if ($totalBingo > 0) {
            $this->updateAwardDb($row, $totalBingo);
        } else {
            DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1]);
        }
    }

    private function updateAwardDb($row, $totalBingo) {
        //>10 cu thi admin xu ly
        if ($totalBingo > 10000000) {
            DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1, 'out_money' => -1]);
        } else {
            try {
                DB::beginTransaction();
                DB::table('dbo_lottery')->where('id', $row->id)->update(['is_checked' => 1, 'out_money' => $totalBingo]);
                $oldBalance = DB::table('dbo_balance')->where('userid', $row->userid)->first();
                if (!$oldBalance) {
                    Log::channel('daily')->info('$oldBalance not found. userid:' . $row->userid);
                    DB::commit();
                } else {
                    DB::table('dbo_payment_order')->insert([
                        'userid' => $row->userid,
                        'status' => Config::get('constants.payment_confirm'),
                        'serviceid' => Config::get('constants.payment_service_id'),
                        'productcode' => 'ltr',
                        'totalproduct' => $totalBingo / 1000,
                        'grandamount' => $totalBingo,
                        'memory_id' => $this->stringHelper->randomString(),
                        'confirmation' => 0,
                        'client_ip' => '::cron::award_loto',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('dbo_balance')->where('userid', $row->userid)->update(['realbalance' => ($oldBalance->realbalance + $totalBingo)]);
                    DB::commit();
                }
            } catch (Exception $ex) {
                Log::channel('daily')->debug($ex->getMessage());
                DB::rollback();
            }
        }
    }

    private function checkObjectExist($myArray, $key) {
        if (count($myArray) === 0)
            return null;
        foreach ($myArray as $element) {
            if ($element->key == $key) {
                return $element;
            }
        }
        return null;
    }

}
