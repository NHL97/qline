<?php

use App\Http\Controllers\BillPlzController;
use App\Http\Controllers\PublicQueueController;
use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['waNumber' => config('qline.wa_number')]);
})->name('home');

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

Route::get('/print/qr/{slug}', [App\Http\Controllers\PublicQueueController::class, 'printQr'])
    ->name('print.qr')
    ->middleware('auth');

Route::get('/print/ticket/{entry}', function (\App\Models\QueueEntry $entry) {
    $business = $entry->business;
    $positionInfo = app(\App\Services\QueueService::class)->getPositionInfo($entry);
    return view('public.print-ticket', compact('entry', 'business', 'positionInfo'));
})->middleware(['auth'])->name('print.ticket');

// Business Registration
Route::get('/register', [App\Http\Controllers\BusinessRegistrationController::class, 'show'])->name('register')->middleware('guest');
Route::post('/register', [App\Http\Controllers\BusinessRegistrationController::class, 'store'])->name('register.store')->middleware('guest');

Route::get('/q/{slug}/waiting', function (string $slug) {
    $business = \App\Models\Business::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    $waiting = \App\Models\QueueEntry::where('business_id', $business->id)
        ->where('status', 'waiting')
        ->orderBy('position')
        ->take(10)
        ->get(['ticket_code', 'position']);

    return response()->json([
        'waiting' => $waiting,
        'count'   => $waiting->count(),
    ]);
})->name('queue.waiting');