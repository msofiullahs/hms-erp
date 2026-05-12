<?php

namespace Modules\MedicalRecords\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\MedicalRecords\Http\Requests\StoreMedicalRecordRequest;
use Modules\MedicalRecords\Http\Requests\StoreMedicalRecordVisitRequest;
use Modules\MedicalRecords\Models\MedicalRecord;
use Modules\MedicalRecords\Models\MedicalRecordVisit;

class MedicalRecordController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $insurance = $request->query('insurance');

        $records = MedicalRecord::query()
            ->when($search, function ($q, $term) {
                $q->where(function ($q) use ($term) {
                    $q->where('mrn', 'like', "%$term%")
                        ->orWhere('name', 'like', "%$term%")
                        ->orWhere('nik', 'like', "%$term%")
                        ->orWhere('bpjs_number', 'like', "%$term%");
                });
            })
            ->when($insurance, fn ($q, $type) => $q->where('insurance_type', $type))
            ->withCount('visits')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('MedicalRecords/Index', [
            'records' => $records,
            'filters' => [
                'search' => $search,
                'insurance' => $insurance,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('MedicalRecords/Create', [
            'suggestedMrn' => MedicalRecord::generateMrn(),
        ]);
    }

    public function store(StoreMedicalRecordRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['mrn'] = MedicalRecord::generateMrn();

        $record = MedicalRecord::create($data);

        return redirect()
            ->route('medicalrecords.show', $record)
            ->with('success', "Rekam medis {$record->mrn} berhasil dibuat.");
    }

    public function show(MedicalRecord $medicalrecord): Response
    {
        $medicalrecord->load(['visits' => fn ($q) => $q->orderByDesc('visit_date')]);

        return Inertia::render('MedicalRecords/Show', [
            'record' => $medicalrecord,
        ]);
    }

    public function edit(MedicalRecord $medicalrecord): Response
    {
        return Inertia::render('MedicalRecords/Edit', [
            'record' => $medicalrecord,
        ]);
    }

    public function update(StoreMedicalRecordRequest $request, MedicalRecord $medicalrecord): RedirectResponse
    {
        $medicalrecord->update($request->validated());

        return redirect()
            ->route('medicalrecords.show', $medicalrecord)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(MedicalRecord $medicalrecord): RedirectResponse
    {
        $medicalrecord->delete();

        return redirect()
            ->route('medicalrecords.index')
            ->with('success', 'Rekam medis dihapus.');
    }

    public function storeVisit(StoreMedicalRecordVisitRequest $request, MedicalRecord $medicalrecord): RedirectResponse
    {
        $data = $request->validated();
        $data['visit_number'] = $medicalrecord->nextVisitNumber();
        $data['status'] = $data['status'] ?? 'active';

        $medicalrecord->visits()->create($data);

        return redirect()
            ->route('medicalrecords.show', $medicalrecord)
            ->with('success', "Kunjungan {$data['visit_number']} dicatat.");
    }

    public function closeVisit(MedicalRecord $medicalrecord, MedicalRecordVisit $visit): RedirectResponse
    {
        abort_unless($visit->medical_record_id === $medicalrecord->id, 404);

        $visit->update(['status' => 'closed']);

        return back()->with('success', "Kunjungan {$visit->visit_number} ditutup.");
    }

    public function reports(): Response
    {
        $today = today();
        $monthStart = now()->startOfMonth();

        $byInsurance = MedicalRecord::query()
            ->selectRaw('insurance_type, COUNT(*) as count')
            ->groupBy('insurance_type')
            ->pluck('count', 'insurance_type');

        $byGender = MedicalRecord::query()
            ->selectRaw("COALESCE(gender, '-') as gender, COUNT(*) as count")
            ->groupBy('gender')
            ->pluck('count', 'gender');

        $visitsByType = MedicalRecordVisit::query()
            ->whereBetween('visit_date', [$monthStart, $today->copy()->endOfDay()])
            ->selectRaw('visit_type, COUNT(*) as count')
            ->groupBy('visit_type')
            ->pluck('count', 'visit_type');

        $topDepartments = MedicalRecordVisit::query()
            ->whereNotNull('department')
            ->whereBetween('visit_date', [$monthStart, $today->copy()->endOfDay()])
            ->selectRaw('department, COUNT(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $monthlyRegistrations = MedicalRecord::query()
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bucket, COUNT(*) as count")
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->pluck('count', 'bucket');

        return Inertia::render('MedicalRecords/Reports', [
            'totals' => [
                'patients' => MedicalRecord::count(),
                'visits_today' => MedicalRecordVisit::whereDate('visit_date', $today)->count(),
                'visits_month' => MedicalRecordVisit::whereBetween('visit_date', [$monthStart, $today->copy()->endOfDay()])->count(),
                'active_visits' => MedicalRecordVisit::where('status', 'active')->count(),
            ],
            'byInsurance' => $byInsurance,
            'byGender' => $byGender,
            'visitsByType' => $visitsByType,
            'topDepartments' => $topDepartments,
            'monthlyRegistrations' => $monthlyRegistrations,
        ]);
    }
}
