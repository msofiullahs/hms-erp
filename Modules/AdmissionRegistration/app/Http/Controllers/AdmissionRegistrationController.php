<?php

namespace Modules\AdmissionRegistration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdmissionRegistration\Http\Requests\AdmissionRegistrationRequest;
use Modules\AdmissionRegistration\Models\AdmissionRegistration;

class AdmissionRegistrationController extends Controller
{
    public function index(): Response
    {
        $registrations = AdmissionRegistration::latest()->paginate(15);

        return Inertia::render('AdmissionRegistration/Index', [
            'registrations' => $registrations,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('AdmissionRegistration/Create');
    }

    public function store(AdmissionRegistrationRequest $request): RedirectResponse
    {
        $registration = AdmissionRegistration::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($registration, 201);
        }

        return redirect()
            ->route('admissionregistration.index')
            ->with('success', 'Admission registration created successfully.');
    }

    public function show(AdmissionRegistration $admissionregistration): Response
    {
        return Inertia::render('AdmissionRegistration/Show', [
            'registration' => $admissionregistration,
        ]);
    }

    public function edit(AdmissionRegistration $admissionregistration): Response
    {
        return Inertia::render('AdmissionRegistration/Edit', [
            'registration' => $admissionregistration,
        ]);
    }

    public function update(AdmissionRegistrationRequest $request, AdmissionRegistration $admissionregistration): RedirectResponse
    {
        $admissionregistration->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($admissionregistration);
        }

        return redirect()
            ->route('admissionregistration.index')
            ->with('success', 'Admission registration updated successfully.');
    }

    public function destroy(Request $request, AdmissionRegistration $admissionregistration): RedirectResponse
    {
        $admissionregistration->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Admission registration deleted successfully.']);
        }

        return redirect()
            ->route('admissionregistration.index')
            ->with('success', 'Admission registration deleted successfully.');
    }
}
