<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/25/2018
 * Time: 9:23 PM
 */

namespace App\Repositories;


use App\Models\Referral;

class ReferralRepository extends BaseRepository {

    protected $model;

    public function __construct(Referral $referral)
    {
        $this->model = $referral;
    }
}