<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenantsRequest extends FormRequest
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
            "phone_number" =>  [
                            'required',
                            'min:8',
                            'max:15',
                            Rule::unique('tenants')
                                ->where('phone_number', $this->phone_number)
                                ->where('first_name', $this->first_name)
                                ->where('last_name', $this->last_name)
                        ],
            "profession"=>"nullable",
            "gender"=>"nullable",
            "nationality"=>"nullable",
            "marital_status"=>"nullable",
        ];
    }
}
