<?php

namespace App\Jobs;

use App\Models\WhatsappMessage;
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
            Log::warning('WhatsApp not configured — skipping message', [
                'template' => $this->template,
                'wa_id' => $this->waId,
            ]);

            return;
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
            'payload' => $response->json(),
        ]);

        if (! $response->successful()) {
            Log::error('WhatsApp send failed', [
                'template' => $this->template,
                'wa_id' => $this->waId,
                'response' => $response->json(),
            ]);
            $this->fail('WhatsApp API error: '.$response->body());
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendWhatsAppMessage permanently failed', [
            'template' => $this->template,
            'wa_id' => $this->waId,
            'error' => $e->getMessage(),
        ]);
    }
}
