<?php

use App\Http\Controllers\Api\CommentsApiController;
use App\Http\Controllers\Api\DisasterApiController;
use App\Http\Controllers\Api\FirebaseApiController;
use App\Http\Controllers\Api\LandingApiPageController;
use App\Http\Controllers\Api\LoginGoogleApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\OtpApiController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReportsApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\CctvDataController;
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

Route::middleware('api')->group(function () {
    Route::post('/user/login', [UserApiController::class, 'login']);
    Route::post('/user/send-otp', [OtpApiController::class, 'sendOtp']);
    Route::post('/user/resend-otp', [OtpApiController::class, 'resendOtp']);
    Route::post('/user/register', [UserApiController::class, 'store']);
    Route::post('/page/logingoogle', [LoginGoogleApiController::class, 'loginGoogle']);

    Route::post('send-fcm-notification', [FirebaseApiController::class, 'sendFCMNotification']);

    // CCTV API - Public endpoints (no auth required for testing)
    Route::prefix('cctv')->group(function () {
        Route::get('/dashboard', [CctvDataController::class, 'getDashboard']); // Gabungkan semua
        Route::get('/latest', [CctvDataController::class, 'getLatest']);
        Route::get('/all', [CctvDataController::class, 'getAll']);
        Route::get('/history', [CctvDataController::class, 'history']);
        Route::get('/status', [CctvDataController::class, 'getStatus']);
        Route::get('/image', [CctvDataController::class, 'showImage']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/page/konsultasi', [LandingApiPageController::class, 'KonsultasiGemini']);
    Route::post('/page/konsultasi-text', [LandingApiPageController::class, 'KonsultasiGeminiText']);
    Route::get('/page/consultation-disaster', [LandingApiPageController::class, 'konsultasiDisaster']);
    Route::prefix('/reports')->group(function () {
        Route::get('/', [ReportsApiController::class, 'index']);
        Route::post('/store', [ReportsApiController::class, 'store']);
        Route::get('/get', [ReportsApiController::class, 'index']);
        Route::get('/get/{id}', [ReportsApiController::class, 'getById']);
        Route::post('/update/{id}', [ReportsApiController::class, 'update']);
        Route::post('/delete/{id}', [ReportsApiController::class, 'destroy']);
        Route::post('/get-locations', [DisasterApiController::class, 'getLocationReports']);
    });
    Route::prefix('/news')->group(function () {
        Route::get('/', [NewsApiController::class, 'index']);
        Route::post('/store', [NewsApiController::class, 'store']);
        Route::get('/recommendation', [NewsApiController::class, 'getRecommendation']);
        Route::get('/{slug}', [NewsApiController::class, 'getById']);
    });
    Route::prefix('/comments')->group(function () {
        Route::get('/', [CommentsApiController::class, 'index']);
        Route::post('/store', [CommentsApiController::class, 'store']);
        Route::put('/update/{id}', [CommentsApiController::class, 'update']);
        Route::delete('/delete/{id}', [CommentsApiController::class, 'destroy']);
        Route::get('/{id}', [CommentsApiController::class, 'getById']);
        Route::get('/news/{newsId}', [CommentsApiController::class, 'getByNewsId']);
    });
    Route::prefix('/user')->group(function () {
        Route::get('/profile', [ProfileController::class, 'profile']);
        Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
        Route::post('/profile/reset-password', [ProfileController::class, 'resetPassword']);
        Route::get('/{id}', [ProfileController::class, 'getUserById']);
    });
    Route::prefix('/disaster')->group(function () {
        Route::post('/warning', [DisasterApiController::class, 'getWarning']);
        Route::get('/category', [DisasterApiController::class, 'getDisasterCategory']);
    });
});
