<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeasingRequest extends FormRequest
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
            "started_on"=>"nullable|date",
            "ended_on"=>"nullable|date",
            "amount"=>"nullable",
            "type"=>"nullable",
            "is_penalized"=>"nullable|boolean",
            "payment_deadline_day"=>"nullable",
            "currency"=>"nullable",
            "is_active"=>"nullable",
        ];
    }
}
