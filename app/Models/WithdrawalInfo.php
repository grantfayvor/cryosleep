<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 8:29 AM
 */

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class WithdrawalInfo extends Model {

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}