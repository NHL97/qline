<?php

namespace App\Jobs;

use App\Models\WhatsappMessage;
use App\Services\QLineLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 30; // seconds between retries

    public int $timeout = 30;

    public function __construct(
        public string $waId,
        public string $template,
        public array $variables,
        public int $businessId,
        public ?int $queueEntryId = null,
    ) {}

    public function handle(): void
    {
        $phoneNumberId = config('qline.meta.phone_number_id');
        $accessToken = config('qline.meta.access_token');
        $apiVersion = config('qline.meta.api_version');

        if (empty($accessToken) || empty($phoneNumberId)) {
            QLineLogger::waFailed(
                $this->waId,
                $this->template,
                'WhatsApp not configured — META_ACCESS_TOKEN or META_PHONE_NUMBER_ID missing'
            );

            return; // Don't retry — credentials won't appear by themselves
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->waId,
            'type' => 'template',
            'template' => [
                'name' => $this->template,
                'language' => ['code' => 'en'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => collect($this->variables)->map(fn ($v) => [
                            'type' => 'text',
                            'text' => (string) $v,
                        ])->values()->toArray(),
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", $payload);

        $messageId = $response->json('messages.0.id');

        // Log the message
        WhatsappMessage::create([
            'business_id' => $this->businessId,
            'queue_entry_id' => $this->queueEntryId,
            'wa_id' => $this->waId,
            'direction' => 'outbound',
            'template' => $this->template,
            'message_id' => $messageId,
            'status' => $response->successful() ? 'sent' : 'failed',
            'payload' => array_merge($response->json(), ['variables' => $this->variables]),
        ]);

        if (! $response->successful()) {
            QLineLogger::waFailed($this->waId, $this->template, $response->body());

            // Throw so job retries (up to $tries times)
            throw new \RuntimeException('WhatsApp API error: '.$response->body());
        }

        QLineLogger::waSent($this->waId, $this->template, $this->businessId);
    }

    public function failed(\Throwable $e): void
    {
        QLineLogger::waFailed($this->waId, $this->template, $e->getMessage());

        // Mark the message as failed in DB if it was logged
        WhatsappMessage::where('wa_id', $this->waId)
            ->where('template', $this->template)
            ->where('status', 'sent')
            ->latest()
            ->first()
            ?->update(['status' => 'failed']);
    }
}
