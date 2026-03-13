<x-filament-panels::page>

    <div class="max-w-2xl space-y-6">

        {{-- Business Details --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Business Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Business Name</label>
                    <input wire:model="name" type="text"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input wire:model="phone" type="text"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                    <input wire:model="city" type="text"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                    <input wire:model="state" type="text"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <input wire:model="address" type="text"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
            </div>

            <button wire:click="saveBusinessDetails"
                class="px-5 py-2 text-sm font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 cursor-pointer border-0">
                Save Details
            </button>
        </div>

        {{-- Queue Settings --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Queue Settings</h2>
            <p class="text-sm text-gray-400 mb-4">Changes take effect on the next queue open/reset</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ticket Prefix</label>
                    <input wire:model="queue_prefix" type="text" maxlength="5"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                    <p class="text-xs text-gray-400 mt-1">e.g. Q → Q001, A → A001</p>
                    @error('queue_prefix') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Daily Limit</label>
                    <input wire:model="daily_limit" type="number" min="1" max="1000"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                    <p class="text-xs text-gray-400 mt-1">Max 1000 entries/day</p>
                    @error('daily_limit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notify Turns Before</label>
                    <input wire:model="notify_turns_before" type="number" min="1" max="20"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                    <p class="text-xs text-gray-400 mt-1">WA notification sent N turns before their turn</p>
                    @error('notify_turns_before') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <button wire:click="saveQueueSettings"
                class="px-5 py-2 text-sm font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 cursor-pointer border-0">
                Save Queue Settings
            </button>
        </div>

        {{-- Read-only Info --}}
        <div class="p-6 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">Read-only Info</h2>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400">Join Code</p>
                    <p class="font-mono font-bold text-gray-900 dark:text-white">{{ auth()->user()->business->join_code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Slug</p>
                    <p class="font-mono text-gray-700 dark:text-gray-300">{{ auth()->user()->business->slug }}</p>
                </div>
            </div>
        </div>

    </div>

</x-filament-panels::page>