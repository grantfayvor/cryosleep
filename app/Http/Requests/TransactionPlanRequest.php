<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPlanRequest extends FormRequest
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
            "name" => $this->name,
            "max_amount" => $this->max_amount,
            "min_amount" => $this->min_amount,
            "profit" => $this->profit,
            "investment_duration" => $this->investment_duration
        ];
    }
}
