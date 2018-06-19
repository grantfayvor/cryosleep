<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalInfoRequest extends FormRequest
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

    public function getValues() {
        return [
            "user_id" => $this->user_id ?: $this->user()->id,
            "address" => $this->address,
            "amount" => $this->amount,
            "currency" => $this->currency ?: "BTC"
        ];
    }
}
