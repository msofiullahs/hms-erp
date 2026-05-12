<?php

namespace Modules\SelfServiceQueue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\SelfServiceQueue\Http\Requests\StoreSelfServiceQueueTicketRequest;
use Modules\SelfServiceQueue\Http\Requests\UpdateSelfServiceQueueTicketRequest;
use Modules\SelfServiceQueue\Models\Ticket;

class SelfServiceQueueController extends Controller
{
    public function kiosk(): Response
    {
        return Inertia::render('SelfServiceQueue/Kiosk', [
            'serviceTypes' => $this->serviceTypesForDisplay(),
            'kioskId' => request()->query('kiosk_id'),
        ]);
    }

    public function issue(StoreSelfServiceQueueTicketRequest $request): RedirectResponse
    {
        $serviceType = $request->input('service_type', config('selfservicequeue.queue.default_service_type'));
        $ticketNumber = Ticket::generateTicketNumber($serviceType);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'customer_name' => $request->input('customer_name'),
            'kiosk_id' => $request->input('kiosk_id'),
            'service_type' => $serviceType,
            'status' => 'waiting',
            'issued_at' => now(),
            'expires_at' => now()->addHours((int) config('selfservicequeue.queue.expires_after_hours', 6)),
            'metadata' => $request->input('metadata', []),
            'qr_code' => Ticket::generateQrCode($ticketNumber),
            'barcode_data' => Ticket::generateBarcodeData($ticketNumber),
        ]);

        return redirect()->route('selfservicequeue.kiosk.ticket', ['ticket' => $ticket->id]);
    }

    public function ticket(Ticket $ticket): Response
    {
        return Inertia::render('SelfServiceQueue/Ticket', [
            'ticket' => $this->ticketPayload($ticket),
            'serviceLabel' => $this->serviceLabel($ticket->service_type),
        ]);
    }

    public function board(): Response
    {
        return Inertia::render('SelfServiceQueue/Board', [
            'refreshSeconds' => (int) config('selfservicequeue.board.refresh_seconds', 5),
        ]);
    }

    public function boardData(): JsonResponse
    {
        $waiting = Ticket::waiting()
            ->orderBy('issued_at')
            ->limit((int) config('selfservicequeue.board.waiting_preview_limit', 10))
            ->get()
            ->map(fn (Ticket $t) => $this->ticketPayload($t));

        $called = Ticket::called()
            ->orderByDesc('called_at')
            ->limit((int) config('selfservicequeue.board.recent_called_limit', 5))
            ->get()
            ->map(fn (Ticket $t) => $this->ticketPayload($t));

        return response()->json([
            'waiting' => $waiting,
            'called' => $called,
            'now_serving' => $called->first(),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function index(): Response
    {
        $tickets = Ticket::latest('issued_at')->paginate(20);

        $tickets->getCollection()->transform(fn (Ticket $t) => $this->ticketPayload($t));

        return Inertia::render('SelfServiceQueue/Index', [
            'tickets' => $tickets,
            'serviceTypes' => $this->serviceTypesForDisplay(),
            'stats' => [
                'waiting' => Ticket::waiting()->count(),
                'called' => Ticket::called()->count(),
                'served_today' => Ticket::served()->whereDate('served_at', today())->count(),
            ],
        ]);
    }

    public function show(Ticket $selfservicequeue): Response
    {
        return Inertia::render('SelfServiceQueue/Show', [
            'ticket' => $this->ticketPayload($selfservicequeue),
            'serviceLabel' => $this->serviceLabel($selfservicequeue->service_type),
        ]);
    }

    public function update(UpdateSelfServiceQueueTicketRequest $request, Ticket $selfservicequeue): RedirectResponse
    {
        $selfservicequeue->update($request->validated());

        return redirect()
            ->route('selfservicequeue.show', $selfservicequeue)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $selfservicequeue): RedirectResponse
    {
        $selfservicequeue->delete();

        return redirect()
            ->route('selfservicequeue.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    public function call(Request $request, Ticket $selfservicequeue): RedirectResponse
    {
        $selfservicequeue->update([
            'status' => 'called',
            'counter' => $request->input('counter', $selfservicequeue->counter ?? '1'),
            'called_at' => now(),
        ]);

        return back()->with('success', "Ticket {$selfservicequeue->ticket_number} called to counter {$selfservicequeue->counter}.");
    }

    public function serve(Ticket $selfservicequeue): RedirectResponse
    {
        $selfservicequeue->update([
            'status' => 'served',
            'served_at' => now(),
        ]);

        return back()->with('success', "Ticket {$selfservicequeue->ticket_number} marked as served.");
    }

    public function cancel(Ticket $selfservicequeue): RedirectResponse
    {
        $selfservicequeue->update(['status' => 'cancelled']);

        return back()->with('success', "Ticket {$selfservicequeue->ticket_number} cancelled.");
    }

    private function serviceTypesForDisplay(): array
    {
        return collect(config('selfservicequeue.service_types', []))
            ->map(fn (array $data, string $key) => array_merge(['key' => $key], $data))
            ->values()
            ->all();
    }

    private function serviceLabel(?string $key): string
    {
        return config("selfservicequeue.service_types.$key.label", $key ?? '');
    }

    private function ticketPayload(Ticket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'customer_name' => $ticket->customer_name,
            'kiosk_id' => $ticket->kiosk_id,
            'service_type' => $ticket->service_type,
            'service_label' => $this->serviceLabel($ticket->service_type),
            'counter' => $ticket->counter,
            'status' => $ticket->status,
            'qr_code' => $ticket->qr_code,
            'barcode_data' => $ticket->barcode_data,
            'issued_at' => optional($ticket->issued_at)->toIso8601String(),
            'called_at' => optional($ticket->called_at)->toIso8601String(),
            'served_at' => optional($ticket->served_at)->toIso8601String(),
        ];
    }
}
