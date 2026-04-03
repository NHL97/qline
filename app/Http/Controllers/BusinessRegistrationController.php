<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Business;
use App\Models\User;
use App\Services\QLineLogger;
use App\Services\QRCodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BusinessRegistrationController extends Controller
{
    public function show()
    {
        return view('public.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'postcode' => 'nullable|string|max:6',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        [$user, $business] = DB::transaction(function () use ($request) {
            // Generate unique slug and join code
            $slug = Str::slug($request->business_name);
            $baseSlug = $slug;
            $i = 1;
            while (Business::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$i++;
            }

            $joinCode = strtoupper(Str::random(6));
            while (Business::where('join_code', $joinCode)->exists()) {
                $joinCode = strtoupper(Str::random(6));
            }

            // Create user first (no business_id yet)
            $user = User::create([
                'business_id' => null,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'business_owner',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create business with user_id
            $business = Business::create([
                'user_id' => $user->id,
                'name' => $request->business_name,
                'slug' => $slug,
                'join_code' => $joinCode,
                'phone' => $request->phone,
                'postcode' => $request->postcode,
                'city' => $request->city,
                'state' => $request->state,
                'is_active' => true,
                'queue_status' => 'closed',
                'queue_prefix' => 'Q',
                'daily_limit' => 100,
                'notify_turns_before' => 3,
                'entries_today' => 0,
            ]);

            // Link user to business
            $user->update(['business_id' => $business->id]);

            // Auto-generate QR code
            app(QRCodeGeneratorService::class)->generateForBusiness($business);

            Auth::login($user);
            QLineLogger::businessRegistered($business->id, $business->name, $user->email);

            return [$user, $business];
        });

        // Now send email outside transaction
        Mail::to($user->email)->queue(new WelcomeMail($user, $business));

        return redirect('/business');
    }
}
