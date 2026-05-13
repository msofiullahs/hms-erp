<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    encounters: Object,
    filters: Object,
    totals: Object,
});

const scope = ref(props.filters?.scope || 'today');
const search = ref(props.filters?.search || '');

let timer = null;
watch([scope, search], () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(route('emr.index'), {
            scope: scope.value,
            search: search.value || undefined,
        }, { preserveState: true, preserveScroll: true, replace: true });
    }, 300);
});

function formatDateTime(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString([], {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
}

function visitTypeLabel(t) {
    return {
        rawat_jalan: 'Outpatient',
        rawat_inap: 'Inpatient',
        igd: 'ER',
        penunjang: 'Ancillary',
    }[t] || t;
}

const scopes = [
    { key: 'today', label: 'Today' },
    { key: 'week', label: 'Last 7 days' },
    { key: 'locked', label: 'Locked' },
    { key: 'all', label: 'All' },
];
</script>

<template>
    <AppLayout title="Electronic Medical Record">
        <Head title="Electronic Medical Record" />

        <div class="mx-auto max-w-7xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-white">Electronic Medical Record</h1>
                    <p class="mt-1 text-sm text-slate-400">Clinical encounters: SOAP notes, vitals, diagnoses, prescriptions, and attachments.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <button
                    v-for="s in scopes"
                    :key="s.key"
                    @click="scope = s.key"
                    type="button"
                    class="rounded-2xl border px-4 py-3 text-left transition"
                    :class="scope === s.key
                        ? 'border-cyan-400 bg-cyan-500/10 text-white'
                        : 'border-slate-800 bg-slate-900 text-slate-300 hover:border-slate-700'"
                >
                    <p class="text-xs uppercase tracking-wider text-slate-400">{{ s.label }}</p>
                    <p class="mt-1 text-2xl font-semibold">{{ props.totals[s.key] }}</p>
                </button>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-4">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search patient name or MRN…"
                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor / Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="e in props.encounters.data" :key="e.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ e.patient_name }}</p>
                                <p class="text-xs font-mono text-gray-500">{{ e.mrn }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ e.visit_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ visitTypeLabel(e.visit_type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <p>{{ e.doctor || '—' }}</p>
                                <p class="text-xs">{{ e.department || '—' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="e.locked_at" class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Locked</span>
                                <span v-else class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Draft</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDateTime(e.updated_at) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <Link :href="route('emr.show', e.visit_id)" class="text-indigo-600 hover:text-indigo-800">Open EMR</Link>
                            </td>
                        </tr>
                        <tr v-if="props.encounters.data.length === 0">
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">No encounters in this range.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-400">
                    Showing {{ props.encounters.from || 0 }}–{{ props.encounters.to || 0 }} of {{ props.encounters.total }} encounters
                </p>
                <div class="inline-flex items-center space-x-1">
                    <Link
                        v-for="link in props.encounters.links"
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
