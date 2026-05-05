<?php

namespace Modules\OnlineRegistration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Modules\OnlineRegistration\Http\Requests\OnlineRegistrationOtpRequest;
use Modules\OnlineRegistration\Http\Requests\OnlineRegistrationRequest;
use Modules\OnlineRegistration\Models\OnlineRegistration;
use Modules\OnlineRegistration\Notifications\OnlineRegistrationOtpNotification;

class OnlineRegistrationController extends Controller
{
    public function create()
    {
        return Inertia::render('OnlineRegistration/Register');
    }

    public function store(OnlineRegistrationRequest $request): RedirectResponse
    {
        $registration = OnlineRegistration::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'visit_date' => $request->visit_date,
            'notes' => $request->notes,
            'status' => 'pending',
            'otp_code' => str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'otp_sent_at' => now(),
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Notification::route('mail', $registration->email)
            ->notify(new OnlineRegistrationOtpNotification($registration));

        return redirect()->route('online-registration.verify.form', ['id' => $registration->id]);
    }

    public function verifyForm(Request $request)
    {
        $registration = OnlineRegistration::findOrFail($request->query('id'));

        if ($registration->isVerified()) {
            return redirect()->route('online-registration.success', ['id' => $registration->id]);
        }

        return Inertia::render('OnlineRegistration/Verify', [
            'registration' => [
                'id' => $registration->id,
                'name' => $registration->name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'visit_date' => $registration->visit_date->toDateString(),
            ],
        ]);
    }

    public function verify(OnlineRegistrationOtpRequest $request): RedirectResponse
    {
        $registration = OnlineRegistration::findOrFail($request->registration_id);

        if ($registration->otp_expires_at->isPast()) {
            return redirect()->route('online-registration.verify.form', ['id' => $registration->id])
                ->with('error', 'The OTP code has expired. Please submit a new registration if needed.');
        }

        if ($request->otp_code !== $registration->otp_code) {
            return redirect()->route('online-registration.verify.form', ['id' => $registration->id])
                ->with('error', 'The OTP code is invalid.');
        }

        $registration->update([
            'verified_at' => now(),
            'status' => 'verified',
        ]);

        return redirect()->route('online-registration.success', ['id' => $registration->id]);
    }

    public function success(Request $request)
    {
        $registration = OnlineRegistration::findOrFail($request->query('id'));

        return Inertia::render('OnlineRegistration/Success', [
            'registration' => [
                'name' => $registration->name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'visit_date' => $registration->visit_date->toDateString(),
                'department' => $registration->department,
            ],
        ]);
    }
}
