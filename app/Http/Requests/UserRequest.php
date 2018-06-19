<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "full_name" => $this->full_name,
            "email" => $this->email,
            "username" => $this->username,
            "secret_question" => $this->secret_question,
            "secret_answer" => $this->secret_answer,
            "crypto_account_id" => $this->crypto_account_id,
            "password" => $this->password
        ];
    }
}
