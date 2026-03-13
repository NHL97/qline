<x-filament-panels::page>

    <div class="max-w-lg mx-auto">
        <div class="p-8 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 text-center">

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Queue QR Code</h2>
            <p class="text-sm text-gray-500 mb-6">
                Customers scan this to join your queue via WhatsApp
            </p>

            @if($qrImageUrl)
                <div class="flex justify-center mb-6">
                    <img src="{{ $qrImageUrl }}" alt="QR Code" class="w-64 h-64 border border-gray-200 rounded-lg" />
                </div>

                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs text-gray-400 mb-1">Join URL</p>
                    <p class="text-sm font-mono text-gray-700 dark:text-gray-300 break-all">{{ $qrCode->url }}</p>
                </div>

                <div class="flex gap-3 justify-center">
                    <a href="{{ $qrImageUrl }}" download="qrcode-{{ auth()->user()->business->slug }}.png"
                       class="px-4 py-2 text-sm font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 cursor-pointer border-0">
                        ↓ Download PNG
                    </a>
                    <button wire:click="generate"
                            class="px-4 py-2 text-sm font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 cursor-pointer border-0">
                        ↺ Regenerate
                    </button>
                </div>

            @else
                <div class="py-12 text-gray-400">
                    <div class="text-6xl mb-4">⬜</div>
                    <p class="text-sm mb-6">No QR code generated yet</p>
                    <button wire:click="generate"
                            class="px-6 py-3 font-bold rounded-xl bg-green-600 text-white hover:bg-green-700 cursor-pointer border-0">
                        Generate QR Code
                    </button>
                </div>
            @endif

        </div>
    </div>

</x-filament-panels::page>