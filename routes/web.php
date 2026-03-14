<?php

use App\Http\Controllers\BillPlzController;
use App\Http\Controllers\PublicQueueController;
use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public queue pages
Route::prefix('q/{slug}')->group(function () {
    Route::get('/tv', [PublicQueueController::class, 'tv'])->name('public.tv');
    Route::get('/status/{entryId}', [PublicQueueController::class, 'status'])->name('public.status');
    Route::post('/cancel/{entryId}', [PublicQueueController::class, 'cancel'])->name('public.cancel');
});

// WhatsApp Webhook
Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify'])->name('webhook.whatsapp.verify');
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])->name('webhook.whatsapp.handle');

// BillPlz
Route::get('/payment/return', [BillPlzController::class, 'redirect'])->name('billplz.redirect');
Route::post('/payment/callback', [BillPlzController::class, 'callback'])->name('billplz.callback');
