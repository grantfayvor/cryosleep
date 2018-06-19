<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:23 PM
 */

namespace App\Repositories;


use App\Models\TransactionPlan;

class TransactionPlanRepository extends BaseRepository{

    public function __construct(TransactionPlan $transactionPlan) {
        $this->model = $transactionPlan;
    }
}