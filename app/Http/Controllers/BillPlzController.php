<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\BillPlzService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\QLineLogger;

class BillPlzController extends Controller
{
    // ── Redirect URL (GET) — customer returns from BillPlz ───────
    public function redirect(Request $request)
    {
        $billplzId   = $request->get('billplz')['id'] ?? null;
        $paid        = $request->get('billplz')['paid'] ?? 'false';
        $signature   = $request->get('billplz')['x_signature'] ?? '';

        if (!$billplzId) {
            return redirect()->route('filament.business.pages.subscription-billing')
                ->with('error', 'Invalid payment response.');
        }

        // Verify signature
        if (!app(BillPlzService::class)->verifySignature($request->all(), $signature)) {
            Log::warning('BillPlz redirect invalid signature', ['bill_id' => $billplzId]);
            return redirect()->route('filament.business.pages.subscription-billing')
                ->with('error', 'Invalid payment signature.');
        }

        if ($paid === 'true') {
            return redirect()->route('filament.business.pages.subscription-billing')
                ->with('success', 'Payment successful! Your subscription is now active.');
        }

        return redirect()->route('filament.business.pages.subscription-billing')
            ->with('error', 'Payment was not completed. Please try again.');
    }

    // ── Callback URL (POST) — BillPlz server notifies payment ────
    public function callback(Request $request)
    {
        $data      = $request->all();
        $signature = $data['x_signature'] ?? '';

        // Verify signature
        if (!app(BillPlzService::class)->verifySignature($data, $signature)) {
            Log::warning('BillPlz callback invalid signature');
            return response('Invalid signature', 403);
        }

        $billId    = $data['id'] ?? null;
        $paid      = $data['paid'] ?? 'false';
        $reference = $data['reference_1'] ?? null;

        if (!$billId || !$reference) {
            return response('Missing data', 400);
        }

        // reference format: payment_{payment_id}
        $paymentId = str_replace('payment_', '', $reference);
        $payment   = Payment::find($paymentId);

        if (!$payment) {
            Log::warning('BillPlz callback payment not found', ['reference' => $reference]);
            return response('Payment not found', 404);
        }

        if ($paid === 'true' && $payment->status !== 'paid') {
            // Mark payment as paid
            $payment->update([
                'status'    => 'paid',
                'reference' => $billId,
                'paid_at'   => now(),
            ]);

            // Activate subscription
            $subscription = Subscription::find($payment->subscription_id);
            if ($subscription) {
                $subscription->update(['status' => 'active']);

                // Open queue if it was closed due to expired subscription
                $business = Business::find($payment->business_id);
                if ($business && $business->isClosed()) {
                    $business->update(['queue_status' => 'open']);
                }
            }

            Log::info('BillPlz payment activated', ['payment_id' => $paymentId]);
            QLineLogger::paymentConfirmed($payment->business_id, $payment->id, $payment->amount);
        }

        return response('OK', 200);
    }
}