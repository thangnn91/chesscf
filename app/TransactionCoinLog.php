<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TransactionCoinLog extends Eloquent {

    protected $connection = 'mongodb';
    protected $collection = 'transaction_coin_log';
    protected $fillable = [
        'system_address', 'customer_address', 'total', 'created_time'
    ];
}
