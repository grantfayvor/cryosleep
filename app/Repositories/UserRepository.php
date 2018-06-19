<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:19 PM
 */

namespace App\Repositories;


use App\User;

class UserRepository extends BaseRepository{

    public function __construct(User $user) {
        $this->model = $user;
    }

    public function getUserConfirmedTransactions($userId)
    {
        return $this->model->coinpayment_transactions()->where('user_id', $userId)
            ->whereNotNull('confirmation_at')
            ->get();
    }
}