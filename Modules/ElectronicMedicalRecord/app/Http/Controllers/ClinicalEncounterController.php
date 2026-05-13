<?php

namespace Modules\ElectronicMedicalRecord\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Modules\ElectronicMedicalRecord\Http\Requests\StoreEncounterAttachmentRequest;
use Modules\ElectronicMedicalRecord\Http\Requests\StoreEncounterDiagnosisRequest;
use Modules\ElectronicMedicalRecord\Http\Requests\StoreEncounterPrescriptionRequest;
use Modules\ElectronicMedicalRecord\Http\Requests\UpdateClinicalEncounterRequest;
use Modules\ElectronicMedicalRecord\Models\ClinicalEncounter;
use Modules\ElectronicMedicalRecord\Models\EncounterAttachment;
use Modules\ElectronicMedicalRecord\Models\EncounterDiagnosis;
use Modules\ElectronicMedicalRecord\Models\EncounterPrescription;
use Modules\MedicalRecords\Models\MedicalRecordVisit;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClinicalEncounterController extends Controller
{
    public function index(Request $request): Response
    {
        $scope = $request->query('scope', 'today');
        $search = trim((string) $request->query('search'));

        $query = ClinicalEncounter::query()
            ->with(['visit.medicalRecord'])
            ->latest('updated_at');

        if ($scope === 'today') {
            $query->whereDate('updated_at', today());
        } elseif ($scope === 'week') {
            $query->where('updated_at', '>=', now()->subDays(7));
        } elseif ($scope === 'locked') {
            $query->whereNotNull('locked_at');
        }

        if ($search !== '') {
            $query->whereHas('visit.medicalRecord', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('mrn', 'like', "%$search%");
            });
        }

        $encounters = $query->paginate(20)->withQueryString();

        $encounters->getCollection()->transform(function (ClinicalEncounter $encounter) {
            $patient = $encounter->visit?->medicalRecord;

            return [
                'id' => $encounter->id,
                'visit_id' => $encounter->medical_record_visit_id,
                'visit_number' => $encounter->visit?->visit_number,
                'visit_type' => $encounter->visit?->visit_type,
                'visit_date' => optional($encounter->visit?->visit_date)->toIso8601String(),
                'doctor' => $encounter->visit?->doctor,
                'department' => $encounter->visit?->department,
                'mrn' => $patient?->mrn,
                'patient_name' => $patient?->name,
                'locked_at' => optional($encounter->locked_at)->toIso8601String(),
                'updated_at' => optional($encounter->updated_at)->toIso8601String(),
            ];
        });

        return Inertia::render('ElectronicMedicalRecord/Index', [
            'encounters' => $encounters,
            'filters' => [
                'scope' => $scope,
                'search' => $search,
            ],
            'totals' => [
                'today' => ClinicalEncounter::whereDate('updated_at', today())->count(),
                'week' => ClinicalEncounter::where('updated_at', '>=', now()->subDays(7))->count(),
                'locked' => ClinicalEncounter::whereNotNull('locked_at')->count(),
                'all' => ClinicalEncounter::count(),
            ],
        ]);
    }

    public function show(MedicalRecordVisit $visit): Response
    {
        $visit->loadMissing('medicalRecord');

        $encounter = ClinicalEncounter::firstOrCreate(
            ['medical_record_visit_id' => $visit->id],
            ['recorded_by' => optional(auth()->user())->name]
        );

        $encounter->load(['diagnoses', 'prescriptions', 'attachments']);

        return Inertia::render('ElectronicMedicalRecord/Encounter', [
            'patient' => [
                'id' => $visit->medicalRecord->id,
                'mrn' => $visit->medicalRecord->mrn,
                'name' => $visit->medicalRecord->name,
                'gender' => $visit->medicalRecord->gender,
                'date_of_birth' => optional($visit->medicalRecord->date_of_birth)->toDateString(),
            ],
            'visit' => [
                'id' => $visit->id,
                'visit_number' => $visit->visit_number,
                'visit_date' => optional($visit->visit_date)->toIso8601String(),
                'visit_type' => $visit->visit_type,
                'department' => $visit->department,
                'doctor' => $visit->doctor,
                'chief_complaint' => $visit->chief_complaint,
                'status' => $visit->status,
            ],
            'encounter' => $this->encounterPayload($encounter),
            'options' => [
                'diagnosis_types' => config('electronicmedicalrecord.diagnosis_types'),
                'prescription_routes' => config('electronicmedicalrecord.prescription_routes'),
            ],
        ]);
    }

    public function update(UpdateClinicalEncounterRequest $request, MedicalRecordVisit $visit): RedirectResponse
    {
        $encounter = ClinicalEncounter::firstOrCreate(['medical_record_visit_id' => $visit->id]);

        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci dan tidak dapat diubah.');
        }

        $data = $request->validated();
        $data['bmi'] = ClinicalEncounter::computeBmi(
            $data['weight_kg'] ?? null,
            $data['height_cm'] ?? null
        );

        $encounter->fill($data)->save();

        return back()->with('success', 'Encounter berhasil diperbarui.');
    }

    public function lock(MedicalRecordVisit $visit): RedirectResponse
    {
        $encounter = ClinicalEncounter::firstOrCreate(['medical_record_visit_id' => $visit->id]);
        $encounter->update(['locked_at' => now()]);

        return back()->with('success', 'Encounter dikunci.');
    }

    public function unlock(MedicalRecordVisit $visit): RedirectResponse
    {
        $encounter = ClinicalEncounter::firstOrCreate(['medical_record_visit_id' => $visit->id]);
        $encounter->update(['locked_at' => null]);

        return back()->with('success', 'Kunci encounter dibuka.');
    }

    public function storeDiagnosis(StoreEncounterDiagnosisRequest $request, ClinicalEncounter $encounter): RedirectResponse
    {
        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci.');
        }

        $encounter->diagnoses()->create($request->validated());

        return back()->with('success', 'Diagnosis ditambahkan.');
    }

    public function destroyDiagnosis(ClinicalEncounter $encounter, EncounterDiagnosis $diagnosis): RedirectResponse
    {
        abort_unless($diagnosis->clinical_encounter_id === $encounter->id, 404);

        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci.');
        }

        $diagnosis->delete();

        return back()->with('success', 'Diagnosis dihapus.');
    }

    public function storePrescription(StoreEncounterPrescriptionRequest $request, ClinicalEncounter $encounter): RedirectResponse
    {
        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci.');
        }

        $encounter->prescriptions()->create($request->validated());

        return back()->with('success', 'Resep ditambahkan.');
    }

    public function destroyPrescription(ClinicalEncounter $encounter, EncounterPrescription $prescription): RedirectResponse
    {
        abort_unless($prescription->clinical_encounter_id === $encounter->id, 404);

        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci.');
        }

        $prescription->delete();

        return back()->with('success', 'Resep dihapus.');
    }

    public function storeAttachment(StoreEncounterAttachmentRequest $request, ClinicalEncounter $encounter): RedirectResponse
    {
        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci.');
        }

        $file = $request->file('file');
        $disk = config('electronicmedicalrecord.attachment.disk', 'local');
        $directory = config('electronicmedicalrecord.attachment.directory', 'emr-attachments');

        $storedPath = $file->store(sprintf('%s/%d', $directory, $encounter->id), $disk);

        $encounter->attachments()->create([
            'label' => $request->input('label'),
            'disk' => $disk,
            'file_path' => $storedPath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
            'uploaded_by' => optional(auth()->user())->name,
        ]);

        return back()->with('success', 'Lampiran ditambahkan.');
    }

    public function destroyAttachment(ClinicalEncounter $encounter, EncounterAttachment $attachment): RedirectResponse
    {
        abort_unless($attachment->clinical_encounter_id === $encounter->id, 404);

        if ($encounter->isLocked()) {
            return back()->with('error', 'Encounter terkunci.');
        }

        $attachment->delete();

        return back()->with('success', 'Lampiran dihapus.');
    }

    public function downloadAttachment(ClinicalEncounter $encounter, EncounterAttachment $attachment): StreamedResponse
    {
        abort_unless($attachment->clinical_encounter_id === $encounter->id, 404);

        return Storage::disk($attachment->disk)->download($attachment->file_path, $attachment->file_name);
    }

    private function encounterPayload(ClinicalEncounter $encounter): array
    {
        return [
            'id' => $encounter->id,
            'subjective' => $encounter->subjective,
            'objective' => $encounter->objective,
            'assessment' => $encounter->assessment,
            'plan' => $encounter->plan,
            'systolic_bp' => $encounter->systolic_bp,
            'diastolic_bp' => $encounter->diastolic_bp,
            'pulse' => $encounter->pulse,
            'respiratory_rate' => $encounter->respiratory_rate,
            'temperature' => $encounter->temperature,
            'spo2' => $encounter->spo2,
            'weight_kg' => $encounter->weight_kg,
            'height_cm' => $encounter->height_cm,
            'bmi' => $encounter->bmi,
            'recorded_by' => $encounter->recorded_by,
            'locked_at' => optional($encounter->locked_at)->toIso8601String(),
            'updated_at' => optional($encounter->updated_at)->toIso8601String(),
            'diagnoses' => $encounter->diagnoses->map(fn ($d) => [
                'id' => $d->id,
                'icd10_code' => $d->icd10_code,
                'description' => $d->description,
                'type' => $d->type,
            ])->values(),
            'prescriptions' => $encounter->prescriptions->map(fn ($p) => [
                'id' => $p->id,
                'drug_name' => $p->drug_name,
                'strength' => $p->strength,
                'form' => $p->form,
                'dosage' => $p->dosage,
                'route' => $p->route,
                'frequency' => $p->frequency,
                'duration' => $p->duration,
                'quantity' => $p->quantity,
                'instructions' => $p->instructions,
            ])->values(),
            'attachments' => $encounter->attachments->map(fn ($a) => [
                'id' => $a->id,
                'label' => $a->label,
                'file_name' => $a->file_name,
                'mime_type' => $a->mime_type,
                'size_bytes' => $a->size_bytes,
                'uploaded_by' => $a->uploaded_by,
                'created_at' => optional($a->created_at)->toIso8601String(),
            ])->values(),
        ];
    }
}
