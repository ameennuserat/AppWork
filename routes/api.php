<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientServiceController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WorkerReviewController;
use App\Http\Controllers\AdminDashboard\AdminNotification;
use App\Http\Controllers\AdminDashboard\PostStatusController;
use App\Http\Controllers\WorkerProfileController;

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


Route::prefix('auth')->group(function () {
    Route::controller(AdminController::class)->prefix('admin')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });
    Route::controller(WorkerController::class)->prefix('worker')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
    });
    Route::controller(ClientController::class)->prefix('client')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });
});


Route::controller(PostController::class)->prefix('worker/post')->group(function () {
    Route::post('/add', 'storepost')->middleware('auth:worker');
    Route::get('/allpost', 'allpost')->middleware('auth:admin');
    Route::get('/approved', 'approved');
    Route::get('/getpost/{id}', 'getpost');
});


Route::prefix('admin')->group(function () {
    Route::controller(PostStatusController::class)->prefix('/post')->group(function () {
        Route::post('/changestatus', 'changestatus')->middleware('auth:admin');

    });
});


Route::prefix('/order')->group(function () {
    Route::controller(ClientServiceController::class)->group(function () {
        Route::prefix('client')->group(function () {
            Route::post('/addorder', 'addorder')->middleware('auth:client');
        });
        Route::prefix('worker')->group(function () {
            Route::get('/orderpend ', 'orderpend')->middleware('auth:worker');
            Route::get('/update/{id}', 'update')->middleware('auth:worker');
        });
    });
    Route::controller(WorkerReviewController::class)->group(function () {
        Route::prefix('client')->group(function () {
            Route::post('/addreview', 'store')->middleware('auth:client');
        });
        Route::prefix('post')->group(function () {
            Route::get('/postrate/{id}', 'postrate');
        });
    });

});
Route::controller(WorkerProfileController::class)->prefix('worker')->group(function (){
    Route::get('profile','userProfile');
    Route::get('edit','edit')->middleware('auth:worker');
    Route::post('update','update')->middleware('auth:worker');
    Route::delete('deletepost/{id}','deletepost')->middleware('auth:worker');
    Route::delete('deleteAllPost','deleteAllPost')->middleware('auth:worker');
});



Route::controller(AdminNotification::class)->middleware('auth:admin')->prefix('admin/dashboard')->group(function () {
    Route::get('/notification', 'allnotification');
    Route::get('/unread', 'unread');
    Route::get('/readall', 'readall');
    Route::get('/readone/{id}', 'readone');
    Route::delete('/deleteall', 'deleteall');
    Route::delete('/deleteone/{id}', 'deleteone');
});
