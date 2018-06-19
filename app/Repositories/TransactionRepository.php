<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/16/2018
 * Time: 7:18 PM
 */

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function getAll($n = null, $url = null, array $fields = null)
    {
        return $this->model->with(['transaction_plan', 'transaction_type'])->orderBy('updated_at', 'desc')->get();
    }

    public function getByParam($param, $value)
    {
        return $this->model->with(['transaction_plan', 'transaction_type'])
            ->where($param, $value)->orderBy('updated_at', 'desc')->get();
    }
}