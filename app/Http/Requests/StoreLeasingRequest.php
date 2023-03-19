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
            "started_on"=>"required|date",
            "ended_on"=>"nullable|date",
            "amount"=>"required",
            "payment_deadline_day"=>"required",
            "currency"=>"nullable",
            "is_active"=>"nullable",
        ];
    }
}
