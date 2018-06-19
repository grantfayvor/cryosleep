<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:26 PM
 */

namespace App\Repositories;


use App\Models\CryptoAccount;

class CryptoAccountRepository extends BaseRepository {

    public function __construct(CryptoAccount $cryptoAccount) {
        $this->model = $cryptoAccount;
    }
}