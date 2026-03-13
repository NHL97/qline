<x-filament-panels::page>

    <div class="max-w-2xl space-y-6">

        {{-- Current Plan --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Plan</h2>

            @if($hasActive)
                <div class="flex items-center justify-between p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-600 text-white">ACTIVE</span>
                            <span class="font-bold text-gray-900 dark:text-white capitalize">
                                {{ $activeSubscription['type'] }} Plan
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            Valid until
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($activeSubscription['expires_at'])->format('d M Y') }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($activeSubscription['expires_at'])->diffInDays(now()) }} days remaining
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-green-600">
                            {{ $activeSubscription['type'] === 'daily' ? 'RM 12' : 'RM 300' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            per {{ $activeSubscription['type'] === 'daily' ? 'day' : 'month' }}
                        </p>
                    </div>
                </div>
            @else
                <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-600 text-white">INACTIVE</span>
                        <span class="font-bold text-gray-900 dark:text-white">No Active Subscription</span>
                    </div>
                    <p class="text-sm text-gray-500">Your queue is currently locked. Subscribe to accept customers.</p>
                </div>
            @endif
        </div>

        {{-- Plans --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Available Plans</h2>

            <div class="grid grid-cols-2 gap-4">
                {{-- Daily --}}
                <div class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 text-center">
                    <p class="font-bold text-gray-900 dark:text-white mb-1">Daily</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1">RM 12</p>
                    <p class="text-xs text-gray-400 mb-3">per day · 1,000 entries/day</p>
                    <p class="text-xs text-gray-500 mb-4">Perfect for events, pop-ups, and pasar malam</p>
                    <button class="w-full py-2 text-sm font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 cursor-not-allowed border-0">
                        Coming Soon
                    </button>
                </div>

                {{-- Monthly --}}
                <div class="p-4 rounded-lg border-2 border-green-500 text-center relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 text-xs font-bold rounded-full bg-green-600 text-white">POPULAR</span>
                    <p class="font-bold text-gray-900 dark:text-white mb-1">Monthly</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1">RM 300</p>
                    <p class="text-xs text-gray-400 mb-3">per month · 1,000 entries/day</p>
                    <p class="text-xs text-gray-500 mb-4">For clinics, banks, and regular businesses</p>
                    <button class="w-full py-2 text-sm font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 cursor-not-allowed border-0">
                        Coming Soon
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center mt-4">
                Payment via FPX (BillPlz) · Contact us to subscribe manually for now
            </p>
        </div>

        {{-- Payment History --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment History</h2>

            @forelse($recentPayments as $payment)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ ucfirst($payment['method'] ?? 'FPX') }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $payment['paid_at'] ? \Carbon\Carbon::parse($payment['paid_at'])->format('d M Y H:i') : '—' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900 dark:text-white">RM {{ number_format($payment['amount'], 2) }}</p>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">Paid</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-400 text-sm">No payment history yet</div>
            @endforelse
        </div>

    </div>

</x-filament-panels::page>