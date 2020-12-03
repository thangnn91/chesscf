<?php
function selected($a, $b)
{
    if ($a == $b) {
        return 'selected';
    }
    return '';
}
function sortFunction($a, $b)
{
    if (isset($a["date"]) && $b["date"]) {
        return strtotime($a["date"]) - strtotime($b["date"]);
    }
    if (isset($a["Date"]) && $b["Date"]) {
        return strtotime($a["Date"]) - strtotime($b["Date"]);
    }
    return true;
}
function stateColorDocConts($conts)
{
    $start = strtotime(date('Y-m-d 00:00:00'));
    $end = strtotime(date('Y-m-d 23:59:59'));
    $delivery_time = strtotime($conts->delivery_time);
    if ($delivery_time >= $start && $delivery_time <= $end) {
        return 'm--font-success';
    }
    if ($delivery_time > $end) {
        return 'm--font-danger';
    }
    return '';
}
function stateColorBg($status, $count = null)
{
    if ($count != null) {
        if ($status == 2) {
            return 'm--font-info';
        }
    } else {
        if ($status === 1) {
            return 'm--font-success';
        } elseif ($status === 0) {
            return 'm--font-danger';
        }
        return '';
    }
}
function clean_str($string)
{
    $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function status_advance($status)
{
    if ($status === null) {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 9pt; font-weight: bold;" class="kt-badge kt-badge--inline btn-warning kt-font-warning"> Pending </span>';
    }
    if ($status == 2) {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 9pt; font-weight: bold;" class="kt-badge kt-badge--inline btn-warning kt-font-warning"> Pending </span>';
    } elseif ($status == 1) {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 9pt; font-weight: bold;" class="kt-badge kt-badge--inline btn-success kt-font-success"> Approval </span>';
    } else {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 9pt; font-weight: bold;" class="kt-badge kt-badge--inline btn-danger  kt-font-danger"> Reject </span>';
    }
}
function conts_car($conts)
{
    if ($conts->type) {
        return ($conts->carInfo) ? $conts->carInfo->number : '';
    }
    return ($conts->carInfo) ? $conts->carInfo->carNumber : '';
}
function array_has_dupes($array)
{
    return count($array) !== count(array_unique($array));
}

function formatamount($advance)
{
    if (!$advance->vat) {
        return number_format($advance->cost);
    }
    $amount = $advance->cost + (($advance->cost / 100) * 10);
    return number_format($amount);
}

function totalAmountAdvance($doc)
{
    $total = 0;
    foreach ($doc->advance as $item) {
        $amount = $item->cost;
        if ($item->vat) {
            $amount = $item->cost + (($item->cost / 100) * 10);
        }
        $total += $amount;
    }
    return number_format($total);
}

function totalDepositRefund($doc)
{
    $total = 0;
    foreach ($doc->depositRefund as $item) {
        $total += $item->cost;
    }
    return $total;
}

function totalReimbursement($doc)
{
    return totalPriceReimbursement($doc);
}

function totalPriceReimbursementCustomer($doc)
{
    $total = 0;
    foreach ($doc->reimbursement as $item) {
        if ($item->type == true) {
            continue;
        }
        $amount = $item->cost;
        if ($item->vat) {
            $amount = $item->cost + (($item->cost / 100) * 10);
        }
        $total += $amount;
    }
    return $total;
}
function totalPriceReimbursement($doc)
{
    $total = 0;
    foreach ($doc->reimbursement as $item) {
        // if($item->type == true){
        //     continue;
        // }
        $amount = $item->cost;
        if ($item->vat) {
            $amount = $item->cost + (($item->cost / 100) * 10);
        }
        $total += $amount;
    }
    return $total;
}
function formatamountReimbursement($reimbursement, $format = true)
{
    if (!$reimbursement->vat) {
        if ($format)
            return number_format($reimbursement->cost);

        return $reimbursement->cost;
    }
    $amount = $reimbursement->cost + (($reimbursement->cost / 100) * 10);
    if ($format)
        return number_format($amount);

    return $amount;
}
function resultAdvanceReimbursement($doc)
{
    return $doc->advance()->sum('cost') - totalPriceReimbursement($doc);
}

function Revenue($doc)
{
    if ($doc->isPayCustomer == false) {
        return (DocAmount($doc) - $doc->conts->sum('cost') - $doc->cost_of_making_goods);
    }
    return (DocAmount($doc) - $doc->conts->sum('cost'));
}

function DocAmount($doc)
{
    if (!$doc->vat) {
        return $doc->cost;
    }
    $vat = ($doc->cost / 100) * 10;
    return $doc->cost -  $vat;
}


function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return '#' . random_color_part() . random_color_part() . random_color_part();
}
function customerDebtStatus($status)
{
    if ($status == 0) {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 12pt;" class="kt-badge kt-badge--inline btn-danger  kt-font-danger"> Unfinished </span>';
    }
    return '<span style="padding: 3px; color: #FFFFFF; font-size: 12pt; " class="kt-badge kt-badge--inline btn-success kt-font-success"> Finish </span>';
}
function lubricant_status($status)
{
    if ($status == 1) {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 9pt; font-weight: bold;" class="kt-badge kt-badge--inline btn-success kt-font-success"> Approval </span>';
    } else {
        return '<span style="padding: 3px; color: #FFFFFF; font-size: 9pt; font-weight: bold;" class="kt-badge kt-badge--inline btn-warning kt-font-warning"> Pending </span>';
    }
}
function getContsCarName($conts)
{
    if ($conts->type && $conts->car) {
        return $conts->car->name;
    } elseif (!$conts->type && $conts->carInfo) {
        return $conts->carInfo->carOwner;
    }
    return '';
}

function lubricant($lubricant)
{
    $cars = $lubricant->carFamily;
    if ($cars) {
        if (isset(config('lubricant.lubricant')[$cars->type][$lubricant->type])) {
            return (config('lubricant.lubricant')[$cars->type][$lubricant->type] * $lubricant->km) + $lubricant->overrun;
        }
    }

    return (0.4 * $lubricant->km) + $lubricant->overrun;
}

function totalLubricant($lubricants)
{
    $totalLubricant = 0;
    foreach ($lubricants as $lubricant) {
        $totalLubricant += lubricant($lubricant);
    }
    return $totalLubricant;
}

function payToCustomer($doc)
{
    $total = 0;
    foreach ($doc->reimbursement as $item) {
        if ($item->type === 0) {
            continue;
        }
        $amount = $item->cost;
        if ($item->vat) {
            $amount = $item->cost + (($item->cost / 100) * 10);
        }
        $total += $amount;
    }
    return $total;
}
function totalDebtCustomer($doc)
{
    $total = 0;
    if ($doc->conts) {
        foreach ($doc->conts as $contr) {
            $total += customerCost($contr);
        }
    }

    if ($doc->reimbursement) {
        foreach ($doc->reimbursement as $item) {
            if ($item->type == 0) {
                continue;
            }
            $total += formatamountReimbursement($item, false);
        }
    }
    $total += $doc->getServiceAmount();

    return $total;
}
function totalDebtCustomerOld($doc)
{
    $payToCustomer = payToCustomer($doc);
    $docCost = 0;
    if (!$doc->vat) {
        $docCost = $doc->cost;
    } else {
        $docCost =  round(($doc->cost / 1.1), 3);
    }

    return ($docCost + $payToCustomer);
}

function test($doc)
{
    $docCost =  round(($doc->cost / 1.1), 3);

    $make = totalPriceReimbursementCustomer($doc);
    $total = $docCost -  $make - $doc->conts()->sum('cost');
    return $total;
}
function totalRefund($doc)
{
    $total = 0;
    foreach ($doc->reimbursement as $item) {
        // if($item->type){
        //     continue;
        // }
        $amount = $item->cost;
        if ($item->vat) {
            $amount = $item->cost + (($item->cost / 100) * 10);
        }
        $total += $amount;
    }
    return $total;
}
function customerCost($conts)
{
    if (!$conts->customerCost) {
        return 0;
    }
    if (!$conts->customerCost->vat) {
        return $conts->customerCost->cost;
    }
    return ($conts->customerCost->cost + (($conts->customerCost->cost / 100) * 10));
}

function classBgTrPlan($contr)
{
    if (!$contr->contsCost) {
        return '';
    }
    $concartCost = $contr->contsCost()->first();
    if (!$concartCost) {
        return '';
    }
    if ($concartCost->status == 1) {
        return 'm--font-success';
    }
    return 'm--font-danger';
}


function hasApprovalPlan($contr)
{
    if (!$contr->contsCost) {
        return false;
    }
    $concartCost = $contr->contsCost()->first();
    if (!$concartCost) {
        return false;
    }
    if ($concartCost->status == 1) {
        return true;
    }
    return false;
}
function paidVendor($doc, $request = [])
{
    $totalPaid = $doc->totalHistoryPaidVendorNew($request);
    // var_dump('totalPaid',$totalPaid );
    $total =  ($doc->totalCostVendor($request) - $doc->totalPaidVendor($request));
    // var_dump('total',$total );
    if ($total < 0) {
        return ($total + $totalPaid);
    }
    return ($total - $totalPaid);
}
function firstOtherMonth($query_date, $format = 'Y-m-d')
{
    $date = new \DateTime($query_date);
    //First day of month
    $date->modify('first day of this month');
    $firstday = $date->format($format);
    return $firstday;
}

function lastOtherMonth($query_date, $format = 'Y-m-d')
{
    $date = new \DateTime($query_date);
    //First day of month
    $date->modify('last day of this month');
    $firstday = $date->format($format);
    return $firstday;
}
