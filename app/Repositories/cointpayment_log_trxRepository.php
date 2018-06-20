<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 9:57 AM
 */

namespace App\Repositories;


use Harrison\CoinPayment\Entities\cointpayment_log_trx;

class cointpayment_log_trxRepository {

    protected $model;

    public function __construct(cointpayment_log_trx $model)
    {
        $this->model = $model;
    }

    public function getUserConfirmedTransactions($userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereNotNull('confirmation_at')
            ->get();
    }

    public function getUserTransactions($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

}