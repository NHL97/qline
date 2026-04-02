<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class QLineLogger
{
    private static function log(string $level, string $event, array $context = []): void
    {
        Log::channel('qline')->{$level}($event, array_merge([
            'timestamp' => now()->toIso8601String(),
            'event'     => $event,
        ], $context));
    }

    // ── Queue Events ──────────────────────────────────────────────
    public static function queueOpened(int $businessId, string $businessName): void
    {
        static::log('info', 'queue.opened', [
            'business_id'   => $businessId,
            'business_name' => $businessName,
        ]);
    }

    public static function queuePaused(int $businessId, string $businessName): void
    {
        static::log('info', 'queue.paused', [
            'business_id'   => $businessId,
            'business_name' => $businessName,
        ]);
    }

    public static function queueClosed(int $businessId, string $businessName): void
    {
        static::log('info', 'queue.closed', [
            'business_id'   => $businessId,
            'business_name' => $businessName,
        ]);
    }

    public static function customerJoined(int $businessId, string $waId, string $ticketCode, int $position): void
    {
        static::log('info', 'customer.joined', [
            'business_id' => $businessId,
            'wa_id'       => $waId,
            'ticket_code' => $ticketCode,
            'position'    => $position,
        ]);
    }

    public static function customerCalled(int $businessId, string $ticketCode): void
    {
        static::log('info', 'customer.called', [
            'business_id' => $businessId,
            'ticket_code' => $ticketCode,
        ]);
    }

    public static function customerDone(int $businessId, string $ticketCode, ?int $waitMinutes, ?int $serviceMinutes): void
    {
        static::log('info', 'customer.done', [
            'business_id'     => $businessId,
            'ticket_code'     => $ticketCode,
            'wait_minutes'    => $waitMinutes,
            'service_minutes' => $serviceMinutes,
        ]);
    }

    public static function customerSkipped(int $businessId, string $ticketCode): void
    {
        static::log('info', 'customer.skipped', [
            'business_id' => $businessId,
            'ticket_code' => $ticketCode,
        ]);
    }

    public static function customerCancelled(int $businessId, string $ticketCode, string $source): void
    {
        static::log('info', 'customer.cancelled', [
            'business_id' => $businessId,
            'ticket_code' => $ticketCode,
            'source'      => $source, // 'customer' or 'staff'
        ]);
    }

    // ── WhatsApp Events ───────────────────────────────────────────
    public static function waSent(string $waId, string $template, int $businessId): void
    {
        static::log('info', 'wa.sent', [
            'wa_id'       => $waId,
            'template'    => $template,
            'business_id' => $businessId,
        ]);
    }

    public static function waFailed(string $waId, string $template, string $error): void
    {
        static::log('error', 'wa.failed', [
            'wa_id'    => $waId,
            'template' => $template,
            'error'    => $error,
        ]);
    }

    public static function waDuplicate(string $messageId): void
    {
        static::log('warning', 'wa.duplicate_webhook', [
            'message_id' => $messageId,
        ]);
    }

    public static function waRateLimited(string $waId, int $businessId): void
    {
        static::log('warning', 'wa.rate_limited', [
            'wa_id'       => $waId,
            'business_id' => $businessId,
        ]);
    }

    // ── Payment Events ────────────────────────────────────────────
    public static function paymentCreated(int $businessId, string $type, float $amount, string $billId): void
    {
        static::log('info', 'payment.created', [
            'business_id' => $businessId,
            'type'        => $type,
            'amount'      => $amount,
            'bill_id'     => $billId,
        ]);
    }

    public static function paymentConfirmed(int $businessId, int $paymentId, float $amount): void
    {
        static::log('info', 'payment.confirmed', [
            'business_id' => $businessId,
            'payment_id'  => $paymentId,
            'amount'      => $amount,
        ]);
    }

    public static function paymentFailed(int $businessId, string $reason): void
    {
        static::log('error', 'payment.failed', [
            'business_id' => $businessId,
            'reason'      => $reason,
        ]);
    }

    // ── Auth Events ───────────────────────────────────────────────
    public static function businessRegistered(int $businessId, string $businessName, string $ownerEmail): void
    {
        static::log('info', 'business.registered', [
            'business_id'   => $businessId,
            'business_name' => $businessName,
            'owner_email'   => $ownerEmail,
        ]);
    }

    // ── Error Events ──────────────────────────────────────────────
    public static function error(string $event, string $message, array $context = []): void
    {
        static::log('error', $event, array_merge(['message' => $message], $context));
    }
}