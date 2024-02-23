<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => '/', 'namespace' => 'App\Http\Controllers'], function ($router) {
        $router->post('/createOrUpdateOrderSummary')->uses('EyezApi@createOrUpdateOrderSummary')->name('api.createOrUpdateOrderSummary');
        $router->post('/orderCreate')->uses('EyezApi@orderCreate')->name('api.createOrder');
        $router->post('/orderBulkImport')->uses('EyezApi@orderBulkImport')->name('api.bulkImportOrders');
        $router->post('/getStores')->uses('EyezApi@getStores')->name('api.getStores');
    });
});
