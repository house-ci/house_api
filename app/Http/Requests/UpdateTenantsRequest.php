<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantsRequest extends FormRequest
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
            "first_name"=>"required|max:100",
            "last_name"=>"required|max:150",
            "document_type"=>"nullable",
            "document_id"=>"nullable",
            "profession"=>"nullable",
            "gender"=>"nullable",
            "nationality"=>"nullable",
            "marital_status"=>"nullable",
        ];
    }
}
