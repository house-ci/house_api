<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\RealEstateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'shouldBeOwner'], (function () {
    Route::get('/me', [OwnerController::class, 'show']);
    Route::resource('/real_estates', RealEstateController::class);;
}));

Route::resource('/property_types', PropertyTypeController::class);
Route::resource('/countries', CountryController::class);
Route::get('/countries/{id}/cities', [CityController::class, 'index']);
Route::resource('/owners', OwnerController::class);


Route::fallback(function () {
    abort(404, ApiResponse::NOTFOUND);
});
