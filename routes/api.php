<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaticFieldController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\RealEstateController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\LeasingController;
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

Route::group(
    ['middleware' => 'shouldBeOwner'],
    (function () {
        Route::get('/me', [OwnerController::class, 'show']);
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::resource('/real_estates', RealEstateController::class);
        Route::resource('/tenants', TenantsController::class);
        Route::resource('/real_estates/{id}/assets', AssetController::class);
        Route::post('/leasings/{tenantId}/{assetId}', [LeasingController::class,'store']);
        Route::get('/leasings/{assetId}', [LeasingController::class,'getAssetLessings']);
        Route::resource('/leasings', LeasingController::class);
        Route::put('/leasings/end_rental/{leasingId}', [LeasingController::class,'endRental']);
    }));

Route::resource('/property_types', PropertyTypeController::class);
Route::get('/document_types', [StaticFieldController::class, 'documentTypes']);
Route::get('/marital_status', [StaticFieldController::class, 'maritalStatus']);
Route::get('/genders', [StaticFieldController::class, 'gender']);
Route::resource('/countries', CountryController::class);
Route::get('/countries/{id}/cities', [CityController::class, 'index']);
Route::resource('/owners', OwnerController::class);


Route::fallback(function () {
    abort(404, ApiResponse::NOTFOUND);
});
