<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    ticket: Object,
    serviceLabel: String,
});

function call() {
    const counter = prompt('Counter number?', props.ticket.counter || '1');
    if (!counter) return;
    router.post(route('selfservicequeue.call', props.ticket.id), { counter });
}

function serve() {
    router.post(route('selfservicequeue.serve', props.ticket.id));
}

function cancel() {
    if (!confirm(`Cancel ticket ${props.ticket.ticket_number}?`)) return;
    router.post(route('selfservicequeue.cancel', props.ticket.id));
}

function destroyTicket() {
    if (!confirm('Delete this ticket? This action cannot be undone.')) return;
    router.delete(route('selfservicequeue.destroy', props.ticket.id));
}

function formatTime(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString();
}
</script>

<template>
    <AppLayout :title="`Ticket ${props.ticket.ticket_number}`">
        <Head :title="`Ticket ${props.ticket.ticket_number}`" />

        <div class="mx-auto max-w-3xl">
            <div class="mb-6 flex items-center justify-between">
                <Link :href="route('selfservicequeue.index')" class="text-sm text-cyan-300 hover:text-cyan-200">&larr; Back to list</Link>
                <span class="rounded-full bg-slate-800 px-3 py-1 text-xs font-medium uppercase tracking-wider text-slate-200">{{ props.ticket.status }}</span>
            </div>

            <div class="rounded-2xl bg-white shadow p-8">
                <p class="text-sm uppercase tracking-widest text-slate-500">Queue Ticket</p>
                <p class="mt-2 font-mono text-6xl font-semibold text-slate-900">{{ props.ticket.ticket_number }}</p>
                <p class="mt-2 text-lg text-slate-600">{{ props.serviceLabel }}</p>

                <dl class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-slate-500">Patient Name</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ props.ticket.customer_name || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Kiosk ID</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ props.ticket.kiosk_id || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Counter</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ props.ticket.counter || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Issued</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ formatTime(props.ticket.issued_at) }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Called</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ formatTime(props.ticket.called_at) }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Served</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ formatTime(props.ticket.served_at) }}</dd>
                    </div>
                </dl>

                <div class="mt-8 rounded-xl border border-dashed border-slate-300 p-4 text-xs font-mono">
                    <p>QR: {{ props.ticket.qr_code }}</p>
                    <p class="mt-1 text-slate-500">Barcode: {{ props.ticket.barcode_data }}</p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <button v-if="['waiting', 'called'].includes(props.ticket.status)" @click="call" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">Call</button>
                    <button v-if="props.ticket.status === 'called'" @click="serve" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Mark as Served</button>
                    <button v-if="['waiting', 'called'].includes(props.ticket.status)" @click="cancel" class="rounded-md bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Cancel</button>
                    <button @click="destroyTicket" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Delete</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
