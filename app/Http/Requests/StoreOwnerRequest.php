<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
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
            "full_name" => "string|min:2|max:120",
            "email" => "required|email",
            "phone_number" => "unique:owners,phone_number|min:8|max:20|nullable",
            "identifier" => "string"
        ];
    }
}
