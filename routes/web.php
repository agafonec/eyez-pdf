<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/dashboard_default', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => '/', 'namespace' => 'App\Http\Controllers'], function($router) {
        $router->get('/')->uses('IndexController@show')->name('home');
        $router->post('/clear-summary')->uses('IndexController@clearStoreCache')->name('summary.clear-cache');

        $router->get('/dashboard')->uses('IndexController@show')->name('home.show');

        $router->post('/export-report/{store}')->uses('ExportDataController@getReportHistory')->name('report.export');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile_opretail', [ProfileController::class, 'opretailUpdate'])->name('profile.opretail.update');
    Route::post('/profile_workdays', [ProfileController::class, 'updateSettings'])->name('profile.workdays.update');
    Route::post('/profile_generate_token', [ProfileController::class, 'generateApiToken'])->name('profile.generate-api-token');
});

require __DIR__.'/auth.php';
