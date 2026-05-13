<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    tickets: Object,
    serviceTypes: Array,
    stats: Object,
});

const counterByTicket = ref({});

function counterFor(ticket) {
    if (counterByTicket.value[ticket.id] === undefined) {
        counterByTicket.value[ticket.id] = ticket.counter || '1';
    }
    return counterByTicket.value[ticket.id];
}

function setCounter(ticket, value) {
    counterByTicket.value[ticket.id] = value;
}

function callTicket(ticket) {
    router.post(route('selfservicequeue.call', ticket.id), {
        counter: counterFor(ticket),
    }, { preserveScroll: true });
}

function serveTicket(ticket) {
    router.post(route('selfservicequeue.serve', ticket.id), {}, { preserveScroll: true });
}

function cancelTicket(ticket) {
    if (!confirm(`Batalkan tiket ${ticket.ticket_number}?`)) return;
    router.post(route('selfservicequeue.cancel', ticket.id), {}, { preserveScroll: true });
}

function statusClass(status) {
    return {
        waiting: 'bg-amber-100 text-amber-800',
        called: 'bg-cyan-100 text-cyan-800',
        served: 'bg-emerald-100 text-emerald-800',
        cancelled: 'bg-rose-100 text-rose-800',
    }[status] || 'bg-slate-100 text-slate-700';
}

function formatTime(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
    });
}
</script>

<template>
    <AppLayout title="Antrian Mandiri">
        <Head title="Antrian Mandiri" />

        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white">Sistem Antrian (Anjungan Mandiri)</h1>
                    <p class="mt-1 text-sm text-slate-400">Panggil, layani, dan pantau tiket yang dikeluarkan dari kiosk.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a :href="route('selfservicequeue.kiosk.home')" target="_blank" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Buka Kiosk</a>
                    <a :href="route('selfservicequeue.board')" target="_blank" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Papan Antrian</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-amber-50 border border-amber-200 rounded-lg px-5 py-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-amber-700">Menunggu</p>
                    <p class="mt-1 text-3xl font-semibold text-amber-900">{{ props.stats.waiting }}</p>
                </div>
                <div class="bg-cyan-50 border border-cyan-200 rounded-lg px-5 py-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-cyan-700">Dipanggil</p>
                    <p class="mt-1 text-3xl font-semibold text-cyan-900">{{ props.stats.called }}</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-5 py-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-700">Selesai hari ini</p>
                    <p class="mt-1 text-3xl font-semibold text-emerald-900">{{ props.stats.served_today }}</p>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diterbitkan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="ticket in props.tickets.data" :key="ticket.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-gray-900">{{ ticket.ticket_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ticket.service_label }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatTime(ticket.issued_at) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input
                                    type="text"
                                    :value="counterFor(ticket)"
                                    @input="(e) => setCounter(ticket, e.target.value)"
                                    class="w-20 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Loket"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium capitalize" :class="statusClass(ticket.status)">{{ ticket.status }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button
                                    v-if="['waiting', 'called'].includes(ticket.status)"
                                    @click="callTicket(ticket)"
                                    class="text-cyan-600 hover:text-cyan-800"
                                >Panggil</button>
                                <button
                                    v-if="ticket.status === 'called'"
                                    @click="serveTicket(ticket)"
                                    class="text-emerald-600 hover:text-emerald-800"
                                >Selesai</button>
                                <button
                                    v-if="['waiting', 'called'].includes(ticket.status)"
                                    @click="cancelTicket(ticket)"
                                    class="text-rose-600 hover:text-rose-800"
                                >Batal</button>
                                <Link :href="route('selfservicequeue.show', ticket.id)" class="text-indigo-600 hover:text-indigo-800">Detail</Link>
                            </td>
                        </tr>
                        <tr v-if="props.tickets.data.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada tiket.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-slate-400">
                    Menampilkan {{ props.tickets.from || 0 }}–{{ props.tickets.to || 0 }} dari {{ props.tickets.total }} tiket
                </p>
                <div class="inline-flex items-center space-x-1">
                    <Link
                        v-for="link in props.tickets.links"
                        :key="link.label + link.url"
                        :href="link.url || '#'"
                        class="px-3 py-1 rounded-md border text-sm"
                        :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200'"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
