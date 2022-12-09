<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRealEstateRequest extends FormRequest
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
            "name" => "required|string|min:3|max:120",
            "city_id" => "required|exists:cities,id",
            "property_type_id" => "required|exists:property_types,id",
            "lot" => "string|min:2|max:50",
            "block" => "string|min:2|max:50",
            "description" => "string|min:3|max:120"
        ];
    }
}
