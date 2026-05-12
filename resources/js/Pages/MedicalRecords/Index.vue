<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    records: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const insurance = ref(props.filters?.insurance || '');

let timeout = null;
watch([search, insurance], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('medicalrecords.index'), {
            search: search.value || undefined,
            insurance: insurance.value || undefined,
        }, { preserveState: true, preserveScroll: true, replace: true });
    }, 300);
});

function destroyRecord(record) {
    if (!confirm(`Hapus rekam medis ${record.mrn}?`)) return;
    router.delete(route('medicalrecords.destroy', record.id), { preserveScroll: true });
}

function formatDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString('id-ID');
}

function insuranceLabel(type) {
    return { umum: 'Umum', bpjs: 'BPJS', asuransi: 'Asuransi' }[type] || type;
}

function insuranceClass(type) {
    return {
        umum: 'bg-slate-100 text-slate-700',
        bpjs: 'bg-emerald-100 text-emerald-800',
        asuransi: 'bg-indigo-100 text-indigo-800',
    }[type] || 'bg-slate-100 text-slate-700';
}
</script>

<template>
    <AppLayout title="Rekam Medis">
        <Head title="Rekam Medis" />

        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Rekam Medis Pasien</h1>
                    <p class="mt-1 text-sm text-gray-600">Master file pasien (MRN) dan riwayat kunjungan.</p>
                </div>
                <div class="flex gap-3">
                    <Link :href="route('medicalrecords.reports')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Laporan</Link>
                    <Link :href="route('medicalrecords.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Pasien Baru</Link>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-4 mb-4 flex flex-col sm:flex-row gap-3">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Cari MRN, nama, NIK, atau no. BPJS…"
                    class="flex-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <select v-model="insurance" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-48">
                    <option value="">Semua Jaminan</option>
                    <option value="umum">Umum</option>
                    <option value="bpjs">BPJS</option>
                    <option value="asuransi">Asuransi</option>
                </select>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MRN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jaminan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kunjungan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="record in props.records.data" :key="record.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-gray-900">{{ record.mrn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(record.date_of_birth) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.gender || '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium" :class="insuranceClass(record.insurance_type)">
                                    {{ insuranceLabel(record.insurance_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.visits_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <Link :href="route('medicalrecords.show', record.id)" class="text-indigo-600 hover:text-indigo-800">Detail</Link>
                                <Link :href="route('medicalrecords.edit', record.id)" class="text-emerald-600 hover:text-emerald-800">Edit</Link>
                                <button @click="destroyRecord(record)" class="text-rose-600 hover:text-rose-800">Hapus</button>
                            </td>
                        </tr>
                        <tr v-if="props.records.data.length === 0">
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada rekam medis.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-700">
                    Menampilkan {{ props.records.from || 0 }}–{{ props.records.to || 0 }} dari {{ props.records.total }} pasien
                </p>
                <div class="inline-flex items-center space-x-1">
                    <Link
                        v-for="link in props.records.links"
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
