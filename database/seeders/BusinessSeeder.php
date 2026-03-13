<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use App\Models\QueueEntry;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'owner@warungahmad.com')->first();
        $staff = User::where('email', 'staff@warungahmad.com')->first();

        // Create test business
        $business = Business::create([
            'user_id'             => $owner->id,
            'name'                => 'Warung Ahmad',
            'slug'                => 'warung-ahmad',
            'join_code'           => 'WARUNG-AHMAD',
            'phone'               => '0123456789',
            'address'             => 'No 12 Jalan Mawar',
            'city'                => 'Temerluh',
            'state'               => 'Pahang',
            'is_active'           => true,
            'queue_status'        => 'open',
            'queue_prefix'        => 'Q',
            'current_number'      => 0,
            'daily_limit'         => 100,
            'entries_today'       => 0,
            'notify_turns_before' => 3,
            'last_reset_at'       => now(),
        ]);

        // Assign business_id to owner and staff
        $owner->update(['business_id' => $business->id]);
        $staff->update(['business_id' => $business->id]);

        // Seed some waiting entries for testing
        $entries = [
            ['wa_id' => '60111111111', 'ticket_number' => 1, 'ticket_code' => 'Q001', 'position' => 1],
            ['wa_id' => '60122222222', 'ticket_number' => 2, 'ticket_code' => 'Q002', 'position' => 2],
            ['wa_id' => '60133333333', 'ticket_number' => 3, 'ticket_code' => 'Q003', 'position' => 3],
            ['wa_id' => null,          'ticket_number' => 4, 'ticket_code' => 'Q004', 'position' => 4], // anonymous
            ['wa_id' => '60155555555', 'ticket_number' => 5, 'ticket_code' => 'Q005', 'position' => 5],
        ];

        foreach ($entries as $entry) {
            QueueEntry::create([
                'business_id'   => $business->id,
                'wa_id'         => $entry['wa_id'],
                'ticket_number' => $entry['ticket_number'],
                'ticket_code'   => $entry['ticket_code'],
                'status'        => 'waiting',
                'source'        => $entry['wa_id'] ? 'whatsapp' : 'manual',
                'position'      => $entry['position'],
                'joined_at'     => now()->subMinutes(20 - ($entry['position'] * 3)),
            ]);
        }

        // Update current_number and entries_today to match seeded entries
        $business->update([
            'current_number' => 5,
            'entries_today'  => 5,
        ]);
    }
}