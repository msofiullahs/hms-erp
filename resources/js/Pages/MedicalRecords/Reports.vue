<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    totals: Object,
    byInsurance: Object,
    byGender: Object,
    visitsByType: Object,
    topDepartments: Array,
    monthlyRegistrations: Object,
});

function insuranceLabel(t) {
    return { umum: 'Umum', bpjs: 'BPJS Kesehatan', asuransi: 'Asuransi Lain' }[t] || t;
}

function genderLabel(g) {
    return { M: 'Laki-laki', F: 'Perempuan', O: 'Lainnya', '-': 'Tidak diisi' }[g] || g;
}

function visitTypeLabel(t) {
    return {
        rawat_jalan: 'Rawat Jalan',
        rawat_inap: 'Rawat Inap',
        igd: 'IGD',
        penunjang: 'Penunjang',
    }[t] || t;
}

const maxMonthly = computed(() => {
    const vals = Object.values(props.monthlyRegistrations || {});
    return vals.length ? Math.max(...vals) : 1;
});

function pct(value, total) {
    if (!total) return 0;
    return Math.round((value / total) * 100);
}

const insuranceTotal = computed(() => Object.values(props.byInsurance || {}).reduce((a, b) => a + b, 0));
const genderTotal = computed(() => Object.values(props.byGender || {}).reduce((a, b) => a + b, 0));
const visitsTotal = computed(() => Object.values(props.visitsByType || {}).reduce((a, b) => a + b, 0));
</script>

<template>
    <AppLayout title="Laporan Rekam Medis">
        <Head title="Laporan Rekam Medis" />

        <div class="mx-auto max-w-7xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <Link :href="route('medicalrecords.index')" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Rekam Medis</Link>
                    <h1 class="mt-2 text-2xl font-semibold text-gray-900">Laporan & Statistik</h1>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wider text-gray-500">Total Pasien</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ props.totals.patients }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wider text-gray-500">Kunjungan Hari Ini</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ props.totals.visits_today }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wider text-gray-500">Kunjungan Bulan Ini</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ props.totals.visits_month }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wider text-gray-500">Kunjungan Aktif</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ props.totals.active_visits }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">Distribusi Penjamin</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="(count, key) in props.byInsurance" :key="key">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ insuranceLabel(key) }}</span>
                                <span class="font-medium text-gray-900">{{ count }} ({{ pct(count, insuranceTotal) }}%)</span>
                            </div>
                            <div class="mt-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-indigo-500" :style="{ width: pct(count, insuranceTotal) + '%' }"></div>
                            </div>
                        </div>
                        <p v-if="!Object.keys(props.byInsurance || {}).length" class="text-sm text-gray-500">—</p>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">Distribusi Jenis Kelamin</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="(count, key) in props.byGender" :key="key">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ genderLabel(key) }}</span>
                                <span class="font-medium text-gray-900">{{ count }} ({{ pct(count, genderTotal) }}%)</span>
                            </div>
                            <div class="mt-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-emerald-500" :style="{ width: pct(count, genderTotal) + '%' }"></div>
                            </div>
                        </div>
                        <p v-if="!Object.keys(props.byGender || {}).length" class="text-sm text-gray-500">—</p>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">Kunjungan per Jenis (Bulan Ini)</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="(count, key) in props.visitsByType" :key="key">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ visitTypeLabel(key) }}</span>
                                <span class="font-medium text-gray-900">{{ count }} ({{ pct(count, visitsTotal) }}%)</span>
                            </div>
                            <div class="mt-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-cyan-500" :style="{ width: pct(count, visitsTotal) + '%' }"></div>
                            </div>
                        </div>
                        <p v-if="!Object.keys(props.visitsByType || {}).length" class="text-sm text-gray-500">—</p>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">Top 10 Departemen (Bulan Ini)</h2>
                    <ul class="mt-4 space-y-2">
                        <li v-for="dep in props.topDepartments" :key="dep.department" class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">{{ dep.department }}</span>
                            <span class="font-medium text-gray-900">{{ dep.count }}</span>
                        </li>
                        <li v-if="!props.topDepartments.length" class="text-sm text-gray-500">—</li>
                    </ul>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-900">Tren Pendaftaran Pasien (12 Bulan)</h2>
                <div class="mt-6 flex items-end gap-2 h-48">
                    <div
                        v-for="(count, bucket) in props.monthlyRegistrations"
                        :key="bucket"
                        class="flex-1 flex flex-col items-center justify-end"
                    >
                        <span class="text-xs font-mono text-gray-700">{{ count }}</span>
                        <div class="w-full rounded-t bg-indigo-500" :style="{ height: Math.max(2, (count / maxMonthly) * 100) + '%' }"></div>
                        <span class="mt-1 text-[10px] text-gray-500">{{ bucket.slice(2) }}</span>
                    </div>
                    <p v-if="!Object.keys(props.monthlyRegistrations || {}).length" class="text-sm text-gray-500">Tidak ada data.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
