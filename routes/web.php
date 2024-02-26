<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SyncOpretailController;
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
        $router->get('/importOrders')->uses('ImportController@ordersView')->name('view.orders-import');

        $router->get('/view-report/{store}')->uses('ExportDataController@view')->name('report.view.test');

        $router->get('/export-report/{store}')->uses('ExportDataController@exportReport')->name('report.export');
        $router->post('/import-orders')->uses('ImportController@orders')->name('orders.import');
        $router->get('/import-orders/get-status')->uses('ImportController@getStatus')->name('orders.import.status');
        $router->get('/import-orders/clean-status')->uses('ImportController@cleanStatus')->name('orders.import.clean-status');

        $router->get('/download-file/{file}')->uses('ImportController@downloadFile')->name('orders.import.sample');
    });

    Route::group(['middleware' => ['role:admin'], 'namespace' => 'App\Http\Controllers'], function ($router) {
        $router->get('/users/')->uses('AdminController@users')->name('admin.users');
        $router->get('/users/{user}')->uses('AdminController@singleUser')->name('admin.single-user');
        $router->get('/users/{user}/sync-store')->uses('AdminController@syncStore')->name('admin.sync-store');
        $router->post('/users/{user}/delete')->uses('AdminController@destroyProfile')->name('admin.profile.destroy');
        $router->get('/dashboard/{user}', [IndexController::class, 'adminShow'])->name('profile.dashboard.view');

    });

    Route::get('/user/isAdmin', [ProfileController::class, 'getUserRoles'])->name('user.roles');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-other', [ProfileController::class, 'updateOther'])->name('profile.update.other');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/sync_store', [SyncOpretailController::class, 'view'])->name('profile.sync-opretail');
    Route::post('/profile/sync_store/{store}', [SyncOpretailController::class, 'start'])->name('profile.sync.start');

    Route::get('/jobs/progress/{batchId}', [SyncOpretailController::class, 'getProgress'])->name('jobs.progress');

    Route::post('/profile_opretail', [ProfileController::class, 'opretailUpdate'])->name('profile.opretail.update');
    Route::post('/profile_settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::post('/profile_generate_token', [ProfileController::class, 'generateApiToken'])->name('profile.generate-api-token');
});

require __DIR__.'/auth.php';
