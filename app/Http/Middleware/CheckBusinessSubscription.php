<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBusinessSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->isBusinessPanel()) {
            return $next($request);
        }

        $business = $user->business;

        if (!$business) {
            return $next($request);
        }

        // Check if active subscription has expired
        $activeSubscription = $business->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '<', now()->toDateString())
            ->first();

        if ($activeSubscription) {
            // Expire it
            $activeSubscription->update(['status' => 'expired']);

            // Pause queue — don't close to avoid disrupting active customers
            if ($business->queue_status === 'open') {
                $business->update(['queue_status' => 'paused']);
            }
        }

        return $next($request);
    }
}