<x-filament-panels::page>
    <style>
        .settings-page {
            max-width: 640px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding-top: 8px;
        }

        .settings-card {
            background: var(--color-background-primary, white);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px 24px;
        }

        .settings-card-muted {
            background: var(--color-background-secondary, #f9fafb);
            border: 0.5px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 20px 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .card-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(20, 184, 166, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .card-icon svg {
            width: 14px;
            height: 14px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 500;
            color: var(--color-text-primary, #111827);
            margin: 0;
        }

        .card-sub {
            font-size: 12px;
            color: var(--color-text-secondary, #6b7280);
            margin: 0;
        }

        .field-grid-2 {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .field-grid-3 {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .field-full {
            grid-column: 1 / -1;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .field label {
            font-size: 11px;
            font-weight: 500;
            color: var(--color-text-secondary, #6b7280);
            letter-spacing: 0.05em;
        }

        .field input {
            height: 36px;
            padding: 0 12px;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            background: var(--color-background-primary, white);
            color: var(--color-text-primary, #111827);
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            box-sizing: border-box;
            width: 100%;
        }

        .field input:focus {
            border-color: #14B8A6;
            box-shadow: 0 0 0 2.5px rgba(20, 184, 166, 0.18);
        }

        .field .hint {
            font-size: 11px;
            color: var(--color-text-tertiary, #9ca3af);
        }

        .settings-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0 16px;
            height: 34px;
            border-radius: 8px;
            background: #14B8A6;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
        }

        .settings-btn:hover {
            background: #0f9e8e;
        }

        .settings-btn:active {
            transform: scale(0.97);
        }

        .settings-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0 16px;
            height: 34px;
            border-radius: 8px;
            background: transparent;
            color: var(--color-text-secondary, #6b7280);
            font-size: 13px;
            font-weight: 500;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: background 0.15s;
        }

        .settings-btn-ghost:hover {
            background: var(--color-background-secondary, #f3f4f6);
        }

        .readonly-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .readonly-label {
            font-size: 11px;
            color: var(--color-text-tertiary, #9ca3af);
            margin-bottom: 4px;
        }

        .mono-badge {
            font-family: monospace;
            font-size: 13px;
            font-weight: 500;
            color: #14B8A6;
            background: rgba(20, 184, 166, 0.08);
            border: 0.5px solid rgba(20, 184, 166, 0.25);
            border-radius: 8px;
            padding: 3px 10px;
            display: inline-block;
            letter-spacing: 0.03em;
        }

        /* QR section */
        .qr-row {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .qr-preview {
            flex-shrink: 0;
            width: 88px;
            height: 88px;
            border-radius: 10px;
            border: 1.5px solid rgba(20, 184, 166, 0.25);
            background: #f0fdfa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .qr-preview img {
            width: 80px;
            height: 80px;
            display: block;
        }

        .qr-preview-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .qr-preview-empty svg {
            width: 24px;
            height: 24px;
            opacity: 0.3;
        }

        .qr-preview-empty span {
            font-size: 10px;
            color: #9ca3af;
        }

        .qr-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .qr-action-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .qr-desc {
            font-size: 12px;
            color: var(--color-text-secondary, #6b7280);
            line-height: 1.5;
        }
    </style>

    <div class="settings-page">

        {{-- Business Details --}}
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round">
                        <rect x="2" y="2" width="12" height="12" rx="2" />
                        <path d="M5 6h6M5 8.5h4" />
                    </svg>
                </div>
                <div>
                    <p class="card-title">Business details</p>
                    <p class="card-sub">Your public profile information</p>
                </div>
            </div>

            <div class="field-grid-2">
                <div class="field field-full">
                    <label>BUSINESS NAME</label>
                    <input wire:model="name" type="text" placeholder="Your business name" />
                    @error('name')
                        <span style="color:#ef4444;font-size:11px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label>PHONE</label>
                    <input wire:model="phone" type="text" placeholder="+60 12-345 6789" />
                </div>
                <div class="field">
                    <label>POS CODE</label>
                    <input wire:model="pos_code" type="number" placeholder="25000" />
                </div>
                <div class="field field-full">
                    <label>ADDRESS</label>
                    <input wire:model="address" type="text" placeholder="No 1, Jalan Contoh..." />
                </div>
                <div class="field">
                    <label>CITY</label>
                    <input wire:model="city" type="text" placeholder="Kuantan" />
                </div>
                <div class="field">
                    <label>STATE</label>
                    <input wire:model="state" type="text" placeholder="Pahang" />
                </div>
                
            </div>

            <button wire:click="saveBusinessDetails" class="settings-btn">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 8l4 4 8-8" />
                </svg>
                Save details
            </button>
        </div>

        {{-- Queue Settings --}}
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round">
                        <path d="M8 2v4M8 14v-4M2 8h4M14 8h-4" />
                        <circle cx="8" cy="8" r="2" />
                    </svg>
                </div>
                <div>
                    <p class="card-title">Queue settings</p>
                    <p class="card-sub">Takes effect on next queue open or reset</p>
                </div>
            </div>

            <div class="field-grid-3">
                <div class="field">
                    <label>TICKET PREFIX</label>
                    <input wire:model="queue_prefix" type="text" maxlength="5" placeholder="Q" />
                    <span class="hint">Q → Q001, A → A001</span>
                    @error('queue_prefix')
                        <span style="color:#ef4444;font-size:11px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label>DAILY LIMIT</label>
                    <input wire:model="daily_limit" type="number" min="1" max="500" placeholder="100" />
                    <span class="hint">Max 500 entries/day</span>
                    @error('daily_limit')
                        <span style="color:#ef4444;font-size:11px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label>NOTIFY BEFORE</label>
                    <input wire:model="notify_turns_before" type="number" min="1" max="5"
                        placeholder="3" />
                    <span class="hint">Turns before their number/Max 5</span>
                    @error('notify_turns_before')
                        <span style="color:#ef4444;font-size:11px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button wire:click="saveQueueSettings" class="settings-btn">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 8l4 4 8-8" />
                </svg>
                Save queue settings
            </button>
        </div>

        {{-- QR Code --}}
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round">
                        <rect x="2" y="2" width="5" height="5" rx="1" />
                        <rect x="9" y="2" width="5" height="5" rx="1" />
                        <rect x="2" y="9" width="5" height="5" rx="1" />
                        <path d="M9 9h1M12 9h2M9 12v2M12 11h1v1M14 13v1h-2" />
                    </svg>
                </div>
                <div>
                    <p class="card-title">QR code</p>
                    <p class="card-sub">Let customers scan to join your queue</p>
                </div>
            </div>

            <div class="qr-row">

                {{-- Small QR preview --}}
                <div class="qr-preview">
                    @if ($qrImageUrl)
                        <img src="{{ $qrImageUrl }}" alt="QR Code" />
                    @else
                        <div class="qr-preview-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                <path d="M14 14h2M18 14h2M14 18v2M18 17v1h-1M20 19v1h-2" />
                            </svg>
                            <span>No QR yet</span>
                        </div>
                    @endif
                </div>

                <div class="qr-actions">
                    <p class="qr-desc">
                        @if ($qrImageUrl)
                            QR code is ready. Print the card to display at your counter, or regenerate if the link has
                            changed.
                        @else
                            Generate a QR code so customers can scan and join your queue via WhatsApp.
                        @endif
                    </p>
                    <div class="qr-action-row">
                        @if ($qrImageUrl)
                            {{-- Print button — opens print page and fires print dialog automatically --}}
                            <button class="settings-btn"
                                onclick="var w=window.open('{{ route('print.qr', auth()->user()->business->slug) }}','_blank'); w.addEventListener('load',function(){ w.print(); });">
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="1" width="10" height="5" rx="1" />
                                    <path
                                        d="M3 6H2a1 1 0 00-1 1v5a1 1 0 001 1h1v-3h10v3h1a1 1 0 001-1V7a1 1 0 00-1-1h-1" />
                                    <rect x="3" y="10" width="10" height="5" rx="1" />
                                </svg>
                                Print card
                            </button>
                            <button wire:click="generate" class="settings-btn-ghost">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                    <path d="M1 8a7 7 0 1 0 1.5-4.3" />
                                    <path d="M1 2v3h3" />
                                </svg>
                                Regenerate
                            </button>
                        @else
                            <button wire:click="generate" class="settings-btn">
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round">
                                    <rect x="2" y="2" width="5" height="5" rx="1" />
                                    <rect x="9" y="2" width="5" height="5" rx="1" />
                                    <rect x="2" y="9" width="5" height="5" rx="1" />
                                </svg>
                                Generate QR code
                            </button>
                        @endif
                    </div>
                    <div class="readonly-grid">
                        <div>
                            <p class="readonly-label">Join code</p>
                            <span class="mono-badge">{{ auth()->user()->business->join_code }}</span>
                        </div>
                        <div>
                            <p class="readonly-label">Slug</p>
                            <span class="mono-badge">{{ auth()->user()->business->slug }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-filament-panels::page>
