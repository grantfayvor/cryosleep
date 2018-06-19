<?php

/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:10 PM
 */

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}


