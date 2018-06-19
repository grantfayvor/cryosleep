<?php

/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:11 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPlan extends Model
{
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}


