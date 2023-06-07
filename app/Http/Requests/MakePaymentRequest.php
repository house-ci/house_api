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
            "paymentAmount"=>"required|numeric|min:500",
            "payer"=>"nullable|string",
            "rentId"=>"nullable|string",
            "paymentDate"=>"nullable|date_format:Y-m-d H:i:s",
        ];
    }
}
