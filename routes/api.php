<?php

use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\RealEstateController;
use Illuminate\Http\Request;
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
    Route::get('/real_estates', [RealEstateController::class, 'index']);
    Route::get('/real_estates/{id}', [RealEstateController::class, 'show']);
}));

Route::get('/property_types', [PropertyTypeController::class, 'index']);
Route::get('/countries', [\App\Http\Controllers\CountryController::class, 'index']);
Route::get('/countries/{id}/cities', [\App\Http\Controllers\CityController::class, 'index']);
Route::post('/owners', [OwnerController::class, 'store'])->name('create.owners');
