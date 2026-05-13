<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from 'vue';

const props = defineProps({ record: Object });

const showVisitForm = ref(false);

const visitForm = useForm({
    visit_date: new Date().toISOString().slice(0, 16),
    visit_type: 'rawat_jalan',
    department: '',
    doctor: '',
    chief_complaint: '',
    diagnosis: '',
    icd10_code: '',
    status: 'active',
});

function submitVisit() {
    visitForm.post(route('medicalrecords.visits.store', props.record.id), {
        onSuccess: () => {
            visitForm.reset();
            visitForm.visit_date = new Date().toISOString().slice(0, 16);
            visitForm.visit_type = 'rawat_jalan';
            visitForm.status = 'active';
            showVisitForm.value = false;
        },
    });
}

function closeVisit(visit) {
    router.post(route('medicalrecords.visits.close', [props.record.id, visit.id]), {}, { preserveScroll: true });
}

function formatDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString();
}

function formatBirth(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString();
}

function visitTypeLabel(type) {
    return {
        rawat_jalan: 'Outpatient',
        rawat_inap: 'Inpatient',
        igd: 'ER',
        penunjang: 'Ancillary',
    }[type] || type;
}

function genderLabel(g) {
    return { M: 'Male', F: 'Female', O: 'Other' }[g] || '—';
}

function insuranceLabel(t) {
    return { umum: 'Self-pay', bpjs: 'BPJS', asuransi: 'Other Insurance' }[t] || t;
}

const age = computed(() => {
    if (!props.record.date_of_birth) return null;
    const dob = new Date(props.record.date_of_birth);
    return Math.floor((Date.now() - dob.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
});
</script>

<template>
    <AppLayout :title="`Medical Record ${props.record.mrn}`">
        <Head :title="`Medical Record ${props.record.mrn}`" />

        <div class="mx-auto max-w-5xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <Link :href="route('medicalrecords.index')" class="text-sm text-cyan-300 hover:text-cyan-200">&larr; Patient List</Link>
                    <h1 class="mt-2 text-2xl font-semibold text-white">{{ props.record.name }}</h1>
                    <p class="font-mono text-sm text-slate-400">{{ props.record.mrn }}</p>
                </div>
                <div class="flex gap-3">
                    <Link :href="route('medicalrecords.edit', props.record.id)" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Edit</Link>
                    <button @click="showVisitForm = !showVisitForm" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ showVisitForm ? 'Cancel' : '+ New Visit' }}</button>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow sm:rounded-lg p-6 lg:col-span-2">
                    <h2 class="text-base font-semibold text-gray-900">Identity</h2>
                    <dl class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500">National ID</dt><dd class="mt-1 text-gray-900">{{ props.record.nik || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Gender</dt><dd class="mt-1 text-gray-900">{{ genderLabel(props.record.gender) }}</dd></div>
                        <div><dt class="text-gray-500">Date of Birth</dt><dd class="mt-1 text-gray-900">{{ formatBirth(props.record.date_of_birth) }}<span v-if="age !== null" class="ml-2 text-gray-500">({{ age }} yrs)</span></dd></div>
                        <div><dt class="text-gray-500">Place of Birth</dt><dd class="mt-1 text-gray-900">{{ props.record.place_of_birth || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Blood Type</dt><dd class="mt-1 text-gray-900">{{ props.record.blood_type || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Religion</dt><dd class="mt-1 text-gray-900">{{ props.record.religion || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Occupation</dt><dd class="mt-1 text-gray-900">{{ props.record.occupation || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Marital Status</dt><dd class="mt-1 text-gray-900">{{ props.record.marital_status || '—' }}</dd></div>
                        <div class="col-span-2"><dt class="text-gray-500">Address</dt><dd class="mt-1 text-gray-900">{{ props.record.address || '—' }}<span v-if="props.record.city" class="text-gray-500">, {{ props.record.city }}</span><span v-if="props.record.province" class="text-gray-500">, {{ props.record.province }}</span></dd></div>
                        <div><dt class="text-gray-500">Phone</dt><dd class="mt-1 text-gray-900">{{ props.record.phone || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Email</dt><dd class="mt-1 text-gray-900">{{ props.record.email || '—' }}</dd></div>
                    </dl>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">Insurance & Emergency Contact</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div><dt class="text-gray-500">Insurance</dt><dd class="mt-1 text-gray-900">{{ insuranceLabel(props.record.insurance_type) }}</dd></div>
                        <div v-if="props.record.bpjs_number"><dt class="text-gray-500">BPJS Number</dt><dd class="mt-1 font-mono text-gray-900">{{ props.record.bpjs_number }}</dd></div>
                        <hr />
                        <div><dt class="text-gray-500">Emergency Contact</dt><dd class="mt-1 text-gray-900">{{ props.record.emergency_contact_name || '—' }}</dd></div>
                        <div v-if="props.record.emergency_contact_phone"><dt class="text-gray-500">Phone</dt><dd class="mt-1 text-gray-900">{{ props.record.emergency_contact_phone }}</dd></div>
                        <div v-if="props.record.emergency_contact_relation"><dt class="text-gray-500">Relationship</dt><dd class="mt-1 text-gray-900">{{ props.record.emergency_contact_relation }}</dd></div>
                    </dl>
                </div>
            </div>

            <div v-if="showVisitForm" class="bg-white shadow sm:rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-900">New Visit</h2>
                <form @submit.prevent="submitVisit" class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input v-model="visitForm.visit_date" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Visit Type</label>
                        <select v-model="visitForm.visit_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="rawat_jalan">Outpatient</option>
                            <option value="rawat_inap">Inpatient</option>
                            <option value="igd">Emergency (ER)</option>
                            <option value="penunjang">Ancillary</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <input v-model="visitForm.department" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Doctor</label>
                        <input v-model="visitForm.doctor" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                        <input v-model="visitForm.chief_complaint" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                        <textarea v-model="visitForm.diagnosis" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ICD-10 Code</label>
                        <input v-model="visitForm.icd10_code" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    </div>
                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit" :disabled="visitForm.processing" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">Save Visit</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900">Visit History ({{ props.record.visits.length }})</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="visit in props.record.visits" :key="visit.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ visit.visit_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(visit.visit_date) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ visitTypeLabel(visit.visit_type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ visit.department || '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ visit.doctor || '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ visit.diagnosis || '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium" :class="visit.status === 'active' ? 'bg-cyan-100 text-cyan-800' : 'bg-slate-100 text-slate-700'">{{ visit.status }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-3">
                                <Link :href="route('emr.show', visit.id)" class="text-indigo-600 hover:text-indigo-800">EMR</Link>
                                <button v-if="visit.status === 'active'" @click="closeVisit(visit)" class="text-emerald-600 hover:text-emerald-800">Close</button>
                            </td>
                        </tr>
                        <tr v-if="props.record.visits.length === 0">
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">No visits yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
