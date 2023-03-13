<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            "name" =>  [
                'required',
                'min:3',
                'max:120',
                Rule::unique('real_estates')
                    ->where('owner_id', $this->owner->id)
            ],
            "city_id" => "required|exists:cities,id",
            "property_type_id" => "required|exists:property_types,id",
            "lot" => "nullable|string|min:2|max:50",
            "block" => "nullable|string|min:2|max:50",
            "description" => "nullable|string|min:3|max:120",
            "number_of_floor" => "nullable|numeric|min:1|max:300",
        ];
    }
}
