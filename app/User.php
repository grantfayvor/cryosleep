<?php

namespace App;

use App\Models\Transaction;
use app\Models\CryptoAccount;
use Harrison\CoinPayment\Entities\CoinPaymentuserRelation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use Notifiable, CoinPaymentuserRelation, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'username', 'password', 'secret_question', 'secret_answer', 'referral_code', 'auto_withdraw'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'secret_answer'
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function crypto_account()
    {
        return $this->hasOne(CryptoAccount::class);
    }
}
