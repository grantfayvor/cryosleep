<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:09 PM
 */


namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function transaction_plan() {
        return $this->belongsTo(TransactionPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

