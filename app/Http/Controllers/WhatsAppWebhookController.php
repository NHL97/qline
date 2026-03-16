<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessQueueJoin;
use App\Jobs\UpdateWhatsAppMessageStatus;
use App\Models\CustomerFeedback;
use App\Models\QueueEntry;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    // Meta webhook verification — GET request
    public function verify(Request $request)
    {
        $verifyToken = config('qline.meta.verify_token');

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $request->get('hub_verify_token') === $verifyToken
        ) {
            return response($request->get('hub_challenge'), 200);
        }

        return response('Forbidden', 403);
    }

    // Meta webhook events — POST request
    public function handle(Request $request)
    {
        // Validate HMAC signature
        if (! $this->validateSignature($request)) {
            Log::warning('WhatsApp webhook invalid signature');

            return response('Forbidden', 403);
        }

        $payload = $request->json()->all();

        // Process each entry
        foreach (data_get($payload, 'entry', []) as $entry) {
            foreach (data_get($entry, 'changes', []) as $change) {
                $value = data_get($change, 'value');

                // Handle inbound messages
                foreach (data_get($value, 'messages', []) as $message) {
                    $this->handleInboundMessage($message, $value);
                }

                // Handle status updates
                foreach (data_get($value, 'statuses', []) as $status) {
                    $this->handleStatusUpdate($status);
                }
            }
        }

        // Always return 200 to Meta
        return response('OK', 200);
    }

    private function handleInboundMessage(array $message, array $value): void
    {
        $waId = data_get($message, 'from');
        $type = data_get($message, 'type');
        $body = data_get($message, 'text.body', '');

        Log::info('WhatsApp inbound', ['wa_id' => $waId, 'body' => $body]);

        // Log inbound message
        WhatsappMessage::create([
            'business_id' => null, // resolved in job
            'queue_entry_id' => null,
            'wa_id' => $waId,
            'direction' => 'inbound',
            'body' => $body,
            'message_id' => data_get($message, 'id'),
            'status' => 'delivered',
            'payload' => $value,
        ]);

        // Check if message starts with JOIN
        if (strtoupper(substr(trim($body), 0, 4)) === 'JOIN') {
            $parts = explode(' ', trim($body), 2);
            $joinCode = strtoupper(trim($parts[1] ?? ''));

            if (! empty($joinCode)) {
                ProcessQueueJoin::dispatch($waId, $joinCode, $value);
            }
        }

        // Check if message is a rating reply (1-5)
        if (is_numeric(trim($body)) && in_array((int) trim($body), [1, 2, 3, 4, 5])) {
            $this->handleFeedback($waId, (int) trim($body));

            return;
        }

    }

    private function handleStatusUpdate(array $status): void
    {
        $messageId = data_get($status, 'id');
        $newStatus = data_get($status, 'status'); // sent, delivered, read, failed

        if ($messageId && $newStatus) {
            UpdateWhatsAppMessageStatus::dispatch($messageId, $newStatus);
        }
    }

    private function validateSignature(Request $request): bool
    {
        $appSecret = config('qline.meta.app_secret');

        // Skip validation if app secret not configured yet
        if (empty($appSecret)) {
            return true;
        }

        $signature = $request->header('X-Hub-Signature-256');

        if (! $signature) {
            return false;
        }

        $expected = 'sha256='.hash_hmac('sha256', $request->getContent(), $appSecret);

        return hash_equals($expected, $signature);
    }

    private function handleFeedback(string $waId, int $rating): void
    {
        // Find the most recent done entry for this wa_id
        $entry = QueueEntry::where('wa_id', $waId)
            ->where('status', 'done')
            ->whereDoesntHave('feedback')
            ->latest('done_at')
            ->first();

        if (! $entry) {
            Log::info('Feedback received but no matching entry', ['wa_id' => $waId]);

            return;
        }

        CustomerFeedback::create([
            'business_id' => $entry->business_id,
            'queue_entry_id' => $entry->id,
            'wa_id' => $waId,
            'rating' => $rating,
        ]);

        Log::info('Feedback saved', ['wa_id' => $waId, 'rating' => $rating]);
    }
}
