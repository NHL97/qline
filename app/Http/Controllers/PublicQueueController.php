<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\QueueEntry;
use App\Services\QueueService;
use Illuminate\Http\Request;

class PublicQueueController extends Controller
{
    // TV Display — /q/{slug}/tv
    public function tv(string $slug)
    {
        $business = Business::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $current = QueueEntry::where('business_id', $business->id)
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        $next = QueueEntry::where('business_id', $business->id)
            ->where('status', 'waiting')
            ->orderBy('position')
            ->take(5)
            ->get();

        return view('public.tv', compact('business', 'current', 'next'));
    }

    // Customer Status Page — /q/{slug}/status/{entryId}
    public function status(string $slug, int $entryId)
    {
        $business = Business::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $entry = QueueEntry::where('id', $entryId)
            ->where('business_id', $business->id)
            ->firstOrFail();

        $positionInfo = app(QueueService::class)->getPositionInfo($entry);

        return view('public.status', compact('business', 'entry', 'positionInfo'));
    }

    // Cancel from status page
    public function cancel(string $slug, int $entryId)
    {
        $business = Business::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $entry = QueueEntry::where('id', $entryId)
            ->where('business_id', $business->id)
            ->where('status', 'waiting')
            ->firstOrFail();

        app(QueueService::class)->cancel($entry);

        return redirect()->route('public.status', [$slug, $entryId])
            ->with('message', 'You have cancelled your queue spot.');
    }
}
