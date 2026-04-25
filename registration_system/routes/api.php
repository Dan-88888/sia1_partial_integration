<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdmissionWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Routes for cross-system integration (Admission System → Registration System)
|--------------------------------------------------------------------------
*/

Route::prefix('admission')->group(function () {
    // Protected — requires X-API-Key header
    Route::middleware('api.webhook')->group(function () {
        Route::post('/webhook', [AdmissionWebhookController::class, 'receive']);
    });

    // Public — status check
    Route::get('/status', [AdmissionWebhookController::class, 'status']);
});
