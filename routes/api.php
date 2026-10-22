<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v2')->group(function () {
    Route::get('/health', [HealthController::class, 'health'])->name('health');
}
);
