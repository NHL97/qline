<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public queue pages
Route::prefix('q/{slug}')->group(function () {
    Route::get('/tv', [App\Http\Controllers\PublicQueueController::class, 'tv'])->name('public.tv');
    Route::get('/status/{entryId}', [App\Http\Controllers\PublicQueueController::class, 'status'])->name('public.status');
    Route::post('/cancel/{entryId}', [App\Http\Controllers\PublicQueueController::class, 'cancel'])->name('public.cancel');
});

// WhatsApp Webhook
Route::get('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'verify'])->name('webhook.whatsapp.verify');
Route::post('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'handle'])->name('webhook.whatsapp.handle');