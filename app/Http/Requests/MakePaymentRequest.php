<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakePaymentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "amount"=>"required|numeric|min:100",
            "payer"=>"nullable|string",
            "rent_id"=>"nullable|string",
            "date"=>"nullable|date_format:Y-m-d H:i:s",
        ];
    }
}
