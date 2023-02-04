<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
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
            "number_of_rooms" => "nullable|numeric|min:1|max:120",
            "description" => "nullable|string|min:1|max:255",
            "door_number" => "required|string|min:1|max:60",
            "is_available" => "nullable|boolean",
            "amount" => "nullable|numeric",
            "currency" => "nullable|string|min:2|max:4",
            "payment_deadline_day" => "nullable|numeric|min:1|max:120",
            "extras" => "nullable|numeric|min:1|max:120",
        ];
    }
}
