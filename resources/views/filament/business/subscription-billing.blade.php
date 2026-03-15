<x-filament-panels::page>
<style>
  .sub-page { max-width: 640px; display: flex; flex-direction: column; gap: 16px; padding-top: 8px; }
  .sub-card { background: var(--color-background-primary, white); border: 0.5px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 20px 24px; }
  .sub-card-title { font-size: 15px; font-weight: 500; color: var(--color-text-primary, #111827); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
  .sub-card-icon { width: 28px; height: 28px; border-radius: 8px; background: rgba(20,184,166,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .sub-card-icon svg { width: 14px; height: 14px; }

  /* Alert banners */
  .alert-success { padding: 11px 16px; background: rgba(20,184,166,0.08); color: #0f766e; border-radius: 10px; font-size: 13px; border: 0.5px solid rgba(20,184,166,0.2); display: flex; align-items: center; gap: 8px; }
  .alert-error { padding: 11px 16px; background: rgba(239,68,68,0.06); color: #b91c1c; border-radius: 10px; font-size: 13px; border: 0.5px solid rgba(239,68,68,0.15); display: flex; align-items: center; gap: 8px; }

  /* Current plan */
  .plan-active { display: flex; align-items: center; justify-content: space-between; padding: 16px; border-radius: 10px; background: rgba(20,184,166,0.06); border: 1px solid rgba(20,184,166,0.2); }
  .plan-inactive { display: flex; align-items: center; justify-content: space-between; padding: 16px; border-radius: 10px; background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.18); }
  .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 6px; }
  .badge-active { background: #14B8A6; color: #fff; }
  .badge-inactive { background: #ef4444; color: #fff; }
  .plan-name { font-size: 14px; font-weight: 500; color: var(--color-text-primary, #111827); }
  .plan-meta { font-size: 12px; color: #6b7280; margin-top: 3px; }
  .plan-meta span { font-weight: 600; color: var(--color-text-primary, #111827); }
  .plan-meta-muted { font-size: 12px; color: #9ca3af; margin-top: 3px; }
  .days-pill { display: inline-flex; align-items: center; gap: 4px; margin-top: 8px; font-size: 11px; color: #0f766e; background: rgba(20,184,166,0.08); padding: 2px 9px; border-radius: 999px; }
  .plan-price-big { font-size: 22px; font-weight: 700; color: #14B8A6; }
  .plan-price-sub { font-size: 11px; color: #9ca3af; margin-top: 2px; text-align: right; }

  /* Plan cards grid */
  .plans-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .plan-card { border: 0.5px solid rgba(0,0,0,0.1); border-radius: 10px; padding: 20px 16px 16px; display: flex; flex-direction: column; align-items: center; text-align: center; position: relative; }
  .plan-card-featured { border: 1.5px solid #14B8A6; }
  .plan-popular-badge { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); padding: 2px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; background: #14B8A6; color: #fff; white-space: nowrap; letter-spacing: 0.04em; }
  .plan-card-name { font-size: 13px; font-weight: 500; color: var(--color-text-primary, #111827); margin-bottom: 8px; }
  .plan-card-price { font-size: 28px; font-weight: 700; color: var(--color-text-primary, #111827); line-height: 1; }
  .plan-card-period { font-size: 11px; color: #9ca3af; margin: 5px 0 8px; }
  .plan-card-desc { font-size: 12px; color: #6b7280; margin-bottom: 16px; line-height: 1.5; flex: 1; }
  .btn-primary { width: 100%; padding: 9px; font-size: 13px; font-weight: 600; border-radius: 8px; background: #14B8A6; color: #fff; border: none; cursor: pointer; transition: background 0.15s; }
  .btn-primary:hover { background: #0f9e8e; }
  .btn-ghost { width: 100%; padding: 9px; font-size: 13px; font-weight: 600; border-radius: 8px; background: transparent; color: #14B8A6; border: 1px solid rgba(20,184,166,0.3); cursor: pointer; transition: background 0.15s; }
  .btn-ghost:hover { background: rgba(20,184,166,0.06); }
  .plans-note { font-size: 11px; color: #9ca3af; text-align: center; margin-top: 12px; }

  /* Payment history */
  .payment-row { display: flex; align-items: center; justify-content: space-between; padding: 11px 0; border-bottom: 0.5px solid rgba(0,0,0,0.06); }
  .payment-row:last-child { border-bottom: none; padding-bottom: 0; }
  .payment-method { font-size: 13px; font-weight: 500; color: var(--color-text-primary, #111827); }
  .payment-date { font-size: 11px; color: #9ca3af; margin-top: 2px; }
  .payment-amount { font-size: 14px; font-weight: 600; color: var(--color-text-primary, #111827); text-align: right; }
  .paid-badge { display: inline-block; margin-top: 3px; padding: 1px 8px; border-radius: 999px; font-size: 10px; font-weight: 600; background: rgba(20,184,166,0.1); color: #0f766e; }
  .empty-state { text-align: center; padding: 28px 0; color: #9ca3af; font-size: 13px; }
</style>

<div class="sub-page">

  {{-- Session alerts --}}
  @if(session('success'))
    <div class="alert-success">
      <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8l4 4 8-8"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert-error">
      <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 11v.5"/></svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Current Plan --}}
  <div class="sub-card">
    <div class="sub-card-title">
      <div class="sub-card-icon">
        <svg viewBox="0 0 16 16" fill="none" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round">
          <rect x="1" y="4" width="14" height="9" rx="2"/>
          <path d="M1 7h14"/>
        </svg>
      </div>
      Current plan
    </div>

    @if($hasActive)
      <div class="plan-active">
        <div>
          <div class="badge badge-active">ACTIVE</div>
          <div class="plan-name">{{ ucfirst($activeSubscription['type']) }} Plan</div>
          <div class="plan-meta">Valid until <span>{{ \Carbon\Carbon::parse($activeSubscription['expires_at'])->format('d M Y') }}</span></div>
          <div class="days-pill">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="8" r="6"/><path d="M8 5v3l2 2"/></svg>
            {{ \Carbon\Carbon::parse($activeSubscription['expires_at'])->diffInDays(now()) }} days remaining
          </div>
        </div>
        <div>
          <div class="plan-price-big">{{ $activeSubscription['type'] === 'daily' ? 'RM 12' : 'RM 300' }}</div>
          <div class="plan-price-sub">per {{ $activeSubscription['type'] === 'daily' ? 'day' : 'month' }}</div>
        </div>
      </div>
    @else
      <div class="plan-inactive">
        <div>
          <div class="badge badge-inactive">INACTIVE</div>
          <div class="plan-name">No active subscription</div>
          <div class="plan-meta-muted" style="font-size:12px;color:#9ca3af;margin-top:3px;">Your queue is currently locked. Subscribe to accept customers.</div>
        </div>
      </div>
    @endif
  </div>

  {{-- Available Plans --}}
  <div class="sub-card">
    <div class="sub-card-title">
      <div class="sub-card-icon">
        <svg viewBox="0 0 16 16" fill="none" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round">
          <path d="M8 1l1.8 3.6L14 5.6l-3 2.9.7 4.1L8 10.5l-3.7 2.1.7-4.1-3-2.9 4.2-.6z"/>
        </svg>
      </div>
      Available plans
    </div>

    <div class="plans-grid">
      <div class="plan-card">
        <div class="plan-card-name">Daily</div>
        <div class="plan-card-price">RM 15</div>
        <div class="plan-card-period">per day · 500 entries</div>
        <div class="plan-card-desc">Perfect for events, pop-ups, and pasar malam</div>
        <button wire:click="subscribe('daily')" class="btn-ghost">Subscribe — RM 15</button>
      </div>

      <div class="plan-card plan-card-featured">
        <div class="plan-popular-badge">POPULAR</div>
        <div class="plan-card-name">Monthly</div>
        <div class="plan-card-price">RM 400</div>
        <div class="plan-card-period">per month · 500 entries</div>
        <div class="plan-card-desc">For clinics, banks, and regular businesses</div>
        <button wire:click="subscribe('monthly')" class="btn-primary">Subscribe — RM 400</button>
      </div>
    </div>

    <p class="plans-note">Payment via FPX (BillPlz) · Contact us to subscribe manually for now</p>
  </div>

  {{-- Payment History --}}
  <div class="sub-card">
    <div class="sub-card-title">
      <div class="sub-card-icon">
        <svg viewBox="0 0 16 16" fill="none" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round">
          <path d="M2 4h12M2 8h8M2 12h5"/>
        </svg>
      </div>
      Payment history
    </div>

    @forelse($recentPayments as $payment)
      <div class="payment-row">
        <div>
          <div class="payment-method">{{ ucfirst($payment['method'] ?? 'FPX') }}</div>
          <div class="payment-date">{{ $payment['paid_at'] ? \Carbon\Carbon::parse($payment['paid_at'])->format('d M Y · H:i') : '—' }}</div>
        </div>
        <div class="payment-amount">
          RM {{ number_format($payment['amount'], 2) }}
          <br><span class="paid-badge">Paid</span>
        </div>
      </div>
    @empty
      <div class="empty-state">No payment history yet</div>
    @endforelse
  </div>

</div>

</x-filament-panels::page>