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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentController;
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
        Route::get('/real_estates/{realEastateId}/assets/{assetId}/rents', [RentController::class,'getRentsUnpaid']);
        Route::post('/real_estates/{realEastateId}/assets/{assetId}/rents', [PaymentController::class,'store']);
        Route::get('/real_estates/{realEastateId}/assets/{assetId}/rents/payments', [PaymentController::class,'leasingPayements']);
        Route::get('/real_estates/{realEastateId}/assets/{assetId}/rents/payments/{paymentId}', [PaymentController::class,'detailPayements']);
        Route::post('/leasings/{tenantId}/{assetId}', [LeasingController::class,'store']);
        Route::resource('/leasings', LeasingController::class);
        Route::put('/leasings/end_rental/{leasingId}', [LeasingController::class,'endRental']);
        Route::put('/leasings/{leasingId}/change_penality_mode', [LeasingController::class,'changePenalityMode']);
        Route::get('/assets/{assetId}/leasings', [LeasingController::class,'getAssetLessings']);
        Route::get('/assets/{assetId}/old_leasings', [LeasingController::class,'getAssetOldLessings']);
        Route::get('/rents/{id}/unpaid', [RentController::class,'getRentsUnpaid']);
        Route::get('/rents/{id}/paid', [RentController::class,'getRentsPaid']);
        Route::get('/payments/{leasingId}', [PaymentController::class,'leasingPayements']);
        Route::get('/payments/{rentId}/rent', [PaymentController::class,'detailPayements']);
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
