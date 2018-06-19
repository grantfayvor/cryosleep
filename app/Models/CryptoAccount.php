<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CryptoAccount extends Model {

    private $address;
    private $balance;

    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}