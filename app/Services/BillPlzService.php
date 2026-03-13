<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillPlzService
{
    private string $apiKey;
    private string $collectionId;
    private string $xSignature;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey       = config('qline.billplz.api_key');
        $this->collectionId = config('qline.billplz.collection_id');
        $this->xSignature   = config('qline.billplz.x_signature');
        $this->apiUrl       = config('qline.billplz.api_url');
    }

    // ── Create Bill ───────────────────────────────────────────────
    public function createBill(
        string $name,
        string $email,
        int $amountCents,
        string $description,
        string $redirectUrl,
        string $callbackUrl,
        string $reference,
    ): array {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->post("{$this->apiUrl}/bills", [
                'collection_id'        => $this->collectionId,
                'email'                => $email,
                'mobile'               => null,
                'name'                 => $name,
                'amount'               => $amountCents,
                'callback_url'         => $callbackUrl,
                'description'          => $description,
                'redirect_url'         => $redirectUrl,
                'reference_1_label'    => 'Reference',
                'reference_1'          => $reference,
            ]);

        if (!$response->successful()) {
            Log::error('BillPlz createBill failed', ['response' => $response->json()]);
            throw new \RuntimeException('Failed to create BillPlz bill: ' . $response->body());
        }

        return $response->json();
    }

    // ── Verify X-Signature ────────────────────────────────────────
    public function verifySignature(array $data, string $signature): bool
    {
        // BillPlz X-Signature verification
        // Concatenate specific fields in order
        if (isset($data['transaction_id'])) {
            // Callback (POST)
            $source = implode('|', [
                $data['id'],
                $data['collection_id'],
                $data['paid'] ?? '',
                $data['state'] ?? '',
                $data['amount'] ?? '',
                $data['paid_amount'] ?? '',
                $data['due_at'] ?? '',
                $data['email'] ?? '',
                $data['mobile'] ?? '',
                $data['name'] ?? '',
                $data['url'] ?? '',
                $data['reference_1_label'] ?? '',
                $data['reference_1'] ?? '',
                $data['reference_2_label'] ?? '',
                $data['reference_2'] ?? '',
                $data['transaction_id'] ?? '',
                $data['transaction_status'] ?? '',
            ]);
        } else {
            // Redirect (GET)
            $source = implode('|', [
                $data['billplz']['id'],
                $data['billplz']['paid'],
            ]);
        }

        $expected = hash_hmac('sha256', $source, $this->xSignature);

        return hash_equals($expected, $signature);
    }

    // ── Get Bill ──────────────────────────────────────────────────
    public function getBill(string $billId): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->get("{$this->apiUrl}/bills/{$billId}");

        return $response->json();
    }
}