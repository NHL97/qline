<x-filament-panels::page>

    <div style="max-width:640px; space-y:16px;">

        {{-- Summary --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
            <div style="padding:24px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; text-align:center;">
                <div style="font-size:3rem; font-weight:900; color:#eab308;">{{ $avgRating }}</div>
                <div style="font-size:13px; color:#6b7280; margin-top:4px;">Average Rating</div>
                <div style="font-size:20px; margin-top:8px;">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= round($avgRating) ? '★' : '☆' }}
                    @endfor
                </div>
            </div>
            <div style="padding:24px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; text-align:center;">
                <div style="font-size:3rem; font-weight:900; color:#111827;">{{ $totalFeedback }}</div>
                <div style="font-size:13px; color:#6b7280; margin-top:4px;">Total Reviews</div>
            </div>
        </div>

        {{-- Rating Breakdown --}}
        <div style="padding:24px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; margin-bottom:16px;">
            <h2 style="font-size:15px; font-weight:700; color:#111827; margin-bottom:16px;">Rating Breakdown</h2>
            @foreach([5,4,3,2,1] as $star)
                @php
                    $count = $ratingCounts[$star];
                    $pct = $totalFeedback > 0 ? round(($count / $totalFeedback) * 100) : 0;
                @endphp
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                    <span style="font-size:13px; color:#374151; width:16px;">{{ $star }}</span>
                    <span style="font-size:14px; color:#eab308;">★</span>
                    <div style="flex:1; background:#f3f4f6; border-radius:999px; height:8px;">
                        <div style="width:{{ $pct }}%; background:#eab308; border-radius:999px; height:8px;"></div>
                    </div>
                    <span style="font-size:12px; color:#9ca3af; width:32px;">{{ $count }}</span>
                </div>
            @endforeach
        </div>

        {{-- Recent Feedback --}}
        <div style="padding:24px; background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
            <h2 style="font-size:15px; font-weight:700; color:#111827; margin-bottom:16px;">Recent Feedback</h2>

            @forelse($recentFeedback as $feedback)
                <div style="padding:12px 0; border-bottom:1px solid #f3f4f6;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
                        <div style="font-size:18px; color:#eab308;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $feedback['rating'] ? '★' : '☆' }}
                            @endfor
                        </div>
                        <span style="font-size:12px; color:#9ca3af;">
                            {{ \Carbon\Carbon::parse($feedback['created_at'])->diffForHumans() }}
                        </span>
                    </div>
                    @if($feedback['comment'])
                        <p style="font-size:13px; color:#374151;">{{ $feedback['comment'] }}</p>
                    @endif
                    <p style="font-size:12px; color:#9ca3af; margin-top:4px;">
                        Ticket: {{ $feedback['queue_entry']['ticket_code'] ?? '—' }}
                    </p>
                </div>
            @empty
                <div style="text-align:center; padding:24px 0; color:#9ca3af; font-size:14px;">
                    No feedback yet. Feedback is collected automatically via WhatsApp after each visit.
                </div>
            @endforelse
        </div>

    </div>

</x-filament-panels::page>