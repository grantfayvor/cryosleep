<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:24 PM
 */

namespace App\Repositories;


use App\Models\TransactionType;

class TransactionTypeRepository extends BaseRepository{

    public function __construct(TransactionType $transactionType) {
        $this->model = $transactionType;
    }
}