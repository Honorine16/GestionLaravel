<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1.0.0')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('otp-code', [AuthController::class, 'checkOtpCode']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('create', [GroupController::class, 'store']);
        Route::post('otp', [GroupController::class, 'verifyOTP']);
        // Route::get('/groups/{groupId}', [GroupController::class, 'showMember']);
        Route::post('addMember/{groupId}', [GroupController::class, 'addMember']);
        Route::get('/groups', [GroupController::class, 'index']);



        Route::post('/sendMessage/{groupId}/send-message', [DiscussionController::class, 'sendMessage']);
        Route::post('/sendFile/{groupId}/{userId}/send-file', [DiscussionController::class, 'sendFile']);
    
        Route::get('groups/{groupId}/messages', [DiscussionController::class, 'index']);
        Route::get('groups/{groupId}', [GroupController::class, 'show']);
        Route::get('groups/{groupId}/files', [DiscussionController::class, 'showFile']);
 

    
    });
});
