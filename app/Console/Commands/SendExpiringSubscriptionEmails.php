<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringSoonMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpiringSubscriptionEmails extends Command
{
    protected $signature   = 'qline:notify-expiring-subscriptions';
    protected $description = 'Send expiring soon emails to businesses whose subscriptions expire in 3 days';

    public function handle(): void
    {
        $subscriptions = Subscription::where('status', 'active')
            ->whereDate('expires_at', now()->addDays(3)->toDateString())
            ->with(['business.owner'])
            ->get();

        foreach ($subscriptions as $subscription) {
            $business = $subscription->business;
            $owner    = $business?->owner;

            if (!$owner) continue;

            Mail::to($owner->email)->queue(
                new SubscriptionExpiringSoonMail($owner, $business, $subscription, 3)
            );

            $this->info("Sent expiring email to {$owner->email}");
        }

        $this->info("Done — {$subscriptions->count()} emails sent.");
    }
}