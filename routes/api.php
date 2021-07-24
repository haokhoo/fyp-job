<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProfilesController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register-jobseeker', [ApiController::class, 'registerJobseeker']);
Route::post('register-employer', [ApiController::class, 'registerEmployer']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get-user', [ApiController::class, 'get_user']);
    //Profiles
    Route::get('profile', [ProfilesController::class, 'index']);
    // Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('profile', [ProfilesController::class, 'store']);
    Route::put('profile',  [ProfilesController::class, 'update']);
    Route::delete('profile',  [ProfilesController::class, 'destroy']);
});
