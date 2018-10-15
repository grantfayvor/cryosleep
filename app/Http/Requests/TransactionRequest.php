<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function getValues()
    {
        return [
            "transaction_plan_id" => $this->transaction_plan_id,
            "transaction_type_id" => $this->transaction_type_id,
            "amount_usd" => $this->amount_usd,
            "amount_btc" => $this->amount_btc,
            "user_id" => $this->user()->id,
            'recipient_address' => $this->recipient_address,
            'address' => $this->address,
            'withdrawal_info_id' => $this->withdral_info
        ];
    }
}
