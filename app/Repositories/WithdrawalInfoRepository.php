<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 8:34 AM
 */

namespace App\Repositories;


use App\Models\WithdrawalInfo;

class WithdrawalInfoRepository extends BaseRepository {

    public function __construct(WithdrawalInfo $info)
    {
        $this->model = $info;
    }

    public function getByParam($param, $value)
    {
        return $this->model->with('user')
            ->where($param, $value)
            ->get();
    }

}