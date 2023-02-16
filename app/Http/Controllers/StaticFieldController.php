<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StorePropertyTypeRequest;
use App\Http\Requests\UpdatePropertyTypeRequest;
use App\Models\Commands\PropertyType;
use Illuminate\Http\JsonResponse;

class StaticFieldController extends Controller
{
    public function documentTypes()
    {
        $documentTypes = [
            __("National ID"),
            __("Passport"),
            __("Driver's license"),
            __("Identity attestation"),
            __("Resident card"),
            __("Resident permit"),
            __("Permanent resident card"),
            __("Temporary resident card"),
        ];
        return response()->json(ApiResponse::getRessourceSuccess(200, $documentTypes));
    }

    public function gender()
    {
        $genders = [
            __('Male'),
            __('Female'),
            __('Other'),
        ];
        return response()->json(ApiResponse::getRessourceSuccess(200, $genders));
    }

    public function maritalStatus()
    {
        $maritalStatus = [
            __('Single'),
            __('Married'),
            __('In couple'),
        ];
        return response()->json(ApiResponse::getRessourceSuccess(200, $maritalStatus));
    }




}
