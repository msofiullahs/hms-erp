<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    patient: Object,
    visit: Object,
    encounter: Object,
    options: Object,
});

const locked = computed(() => !!props.encounter.locked_at);

const soapForm = useForm({
    subjective: props.encounter.subjective || '',
    objective: props.encounter.objective || '',
    assessment: props.encounter.assessment || '',
    plan: props.encounter.plan || '',
    systolic_bp: props.encounter.systolic_bp || '',
    diastolic_bp: props.encounter.diastolic_bp || '',
    pulse: props.encounter.pulse || '',
    respiratory_rate: props.encounter.respiratory_rate || '',
    temperature: props.encounter.temperature || '',
    spo2: props.encounter.spo2 || '',
    weight_kg: props.encounter.weight_kg || '',
    height_cm: props.encounter.height_cm || '',
    recorded_by: props.encounter.recorded_by || '',
});

const computedBmi = computed(() => {
    const w = parseFloat(soapForm.weight_kg);
    const h = parseFloat(soapForm.height_cm);
    if (!w || !h) return null;
    const m = h / 100;
    if (m <= 0) return null;
    return (w / (m * m)).toFixed(2);
});

function saveEncounter() {
    soapForm.put(route('emr.update', props.visit.id), { preserveScroll: true });
}

function lockEncounter() {
    if (!confirm('Lock this encounter? Once locked it cannot be modified until unlocked.')) return;
    router.post(route('emr.lock', props.visit.id), {}, { preserveScroll: true });
}

function unlockEncounter() {
    router.post(route('emr.unlock', props.visit.id), {}, { preserveScroll: true });
}

// --- diagnoses ---
const diagnosisForm = useForm({
    icd10_code: '',
    description: '',
    type: 'primary',
});

function addDiagnosis() {
    diagnosisForm.post(route('emr.diagnoses.store', props.encounter.id), {
        preserveScroll: true,
        onSuccess: () => diagnosisForm.reset(),
    });
}

function removeDiagnosis(id) {
    if (!confirm('Remove this diagnosis?')) return;
    router.delete(route('emr.diagnoses.destroy', [props.encounter.id, id]), { preserveScroll: true });
}

// --- prescriptions ---
const prescriptionForm = useForm({
    drug_name: '',
    strength: '',
    form: '',
    dosage: '',
    route: 'po',
    frequency: '',
    duration: '',
    quantity: '',
    instructions: '',
});

function addPrescription() {
    prescriptionForm.post(route('emr.prescriptions.store', props.encounter.id), {
        preserveScroll: true,
        onSuccess: () => prescriptionForm.reset('drug_name', 'strength', 'form', 'dosage', 'frequency', 'duration', 'quantity', 'instructions'),
    });
}

function removePrescription(id) {
    if (!confirm('Remove this prescription?')) return;
    router.delete(route('emr.prescriptions.destroy', [props.encounter.id, id]), { preserveScroll: true });
}

// --- attachments ---
const attachmentForm = useForm({
    label: '',
    file: null,
});

function addAttachment() {
    attachmentForm.post(route('emr.attachments.store', props.encounter.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => attachmentForm.reset(),
    });
}

function removeAttachment(id) {
    if (!confirm('Remove this attachment?')) return;
    router.delete(route('emr.attachments.destroy', [props.encounter.id, id]), { preserveScroll: true });
}

function diagnosisTypeLabel(t) {
    return props.options.diagnosis_types[t] || t;
}

function routeLabel(r) {
    return props.options.prescription_routes[r] || r || '—';
}

function formatBytes(b) {
    if (!b) return '—';
    if (b < 1024) return `${b} B`;
    if (b < 1024 * 1024) return `${(b / 1024).toFixed(1)} KB`;
    return `${(b / 1024 / 1024).toFixed(1)} MB`;
}

function patientAge() {
    if (!props.patient.date_of_birth) return '—';
    const dob = new Date(props.patient.date_of_birth);
    return Math.floor((Date.now() - dob.getTime()) / (365.25 * 24 * 60 * 60 * 1000)) + ' yrs';
}

function genderLabel(g) {
    return { M: 'Male', F: 'Female', O: 'Other' }[g] || '';
}
</script>

<template>
    <AppLayout :title="`EMR ${props.patient.mrn}`">
        <Head :title="`EMR ${props.patient.mrn} - ${props.visit.visit_number}`" />

        <div class="mx-auto max-w-6xl space-y-6">
            <!-- Header -->
            <div class="bg-white shadow sm:rounded-lg p-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <Link :href="route('medicalrecords.show', props.patient.id)" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Patient Record</Link>
                    <h1 class="mt-2 text-2xl font-semibold text-gray-900">{{ props.patient.name }}</h1>
                    <p class="text-sm text-gray-500">
                        <span class="font-mono">{{ props.patient.mrn }}</span>
                        <span class="ml-3">{{ patientAge() }}</span>
                        <span v-if="props.patient.gender" class="ml-3">{{ genderLabel(props.patient.gender) }}</span>
                    </p>
                    <p class="mt-3 text-sm text-gray-700">
                        <span class="font-mono text-gray-500">{{ props.visit.visit_number }}</span>
                        <span class="ml-3 capitalize">{{ props.visit.visit_type.replaceAll('_', ' ') }}</span>
                        <span v-if="props.visit.department" class="ml-3">· {{ props.visit.department }}</span>
                        <span v-if="props.visit.doctor" class="ml-3">· {{ props.visit.doctor }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span v-if="locked" class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">Locked</span>
                    <span v-else class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">Draft</span>
                    <button v-if="!locked" @click="lockEncounter" class="rounded-md border border-rose-300 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">Lock</button>
                    <button v-else @click="unlockEncounter" class="rounded-md border border-emerald-300 px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">Unlock</button>
                </div>
            </div>

            <!-- Vital signs + SOAP -->
            <form @submit.prevent="saveEncounter" class="space-y-6">
                <section class="bg-white shadow sm:rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">Vital Signs</h2>
                    <div class="mt-4 grid gap-4 grid-cols-2 sm:grid-cols-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">BP (mmHg)</label>
                            <div class="mt-1 flex items-center gap-1">
                                <input v-model="soapForm.systolic_bp" type="number" min="0" max="300" class="w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                                <span class="text-gray-400">/</span>
                                <input v-model="soapForm.diastolic_bp" type="number" min="0" max="250" class="w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Pulse (bpm)</label>
                            <input v-model="soapForm.pulse" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Resp Rate (/min)</label>
                            <input v-model="soapForm.respiratory_rate" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Temp (°C)</label>
                            <input v-model="soapForm.temperature" type="number" step="0.1" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">SpO₂ (%)</label>
                            <input v-model="soapForm.spo2" type="number" min="0" max="100" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Weight (kg)</label>
                            <input v-model="soapForm.weight_kg" type="number" step="0.1" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Height (cm)</label>
                            <input v-model="soapForm.height_cm" type="number" step="0.1" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" :disabled="locked" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">BMI (kg/m²)</label>
                            <div class="mt-1 inline-flex h-9 w-full items-center rounded-md bg-gray-50 px-3 text-sm text-gray-700">{{ computedBmi || '—' }}</div>
                        </div>
                    </div>
                </section>

                <section class="bg-white shadow sm:rounded-lg p-6">
                    <h2 class="text-base font-semibold text-gray-900">SOAP Notes</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subjective</label>
                            <textarea v-model="soapForm.subjective" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="locked" placeholder="Chief complaint, history of present illness…"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Objective</label>
                            <textarea v-model="soapForm.objective" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="locked" placeholder="Physical exam findings…"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assessment</label>
                            <textarea v-model="soapForm.assessment" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="locked" placeholder="Clinical impression, working diagnosis…"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plan</label>
                            <textarea v-model="soapForm.plan" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="locked" placeholder="Treatment plan, education, follow-up…"></textarea>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Provider</label>
                            <input v-model="soapForm.recorded_by" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="locked" placeholder="Dr. Name" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" :disabled="soapForm.processing || locked" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">Save Encounter</button>
                    </div>
                </section>
            </form>

            <!-- Diagnoses -->
            <section class="bg-white shadow sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Diagnoses (ICD-10)</h2>
                </div>

                <form v-if="!locked" @submit.prevent="addDiagnosis" class="mt-4 grid gap-3 sm:grid-cols-[120px_1fr_160px_auto]">
                    <input v-model="diagnosisForm.icd10_code" type="text" placeholder="Code (e.g. J11.1)" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input v-model="diagnosisForm.description" type="text" placeholder="Diagnosis description" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <select v-model="diagnosisForm.type" class="rounded-md border-gray-300 text-sm shadow-sm">
                        <option v-for="(label, key) in props.options.diagnosis_types" :key="key" :value="key">{{ label }}</option>
                    </select>
                    <button type="submit" :disabled="diagnosisForm.processing" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">Add</button>
                </form>
                <p v-if="diagnosisForm.errors.icd10_code" class="mt-1 text-sm text-red-600">{{ diagnosisForm.errors.icd10_code }}</p>

                <table class="mt-4 min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="d in props.encounter.diagnoses" :key="d.id">
                            <td class="px-3 py-2 text-sm font-mono text-gray-900">{{ d.icd10_code }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ d.description }}</td>
                            <td class="px-3 py-2 text-sm text-gray-500">{{ diagnosisTypeLabel(d.type) }}</td>
                            <td class="px-3 py-2 text-right">
                                <button v-if="!locked" @click="removeDiagnosis(d.id)" class="text-rose-600 hover:text-rose-800 text-sm">Remove</button>
                            </td>
                        </tr>
                        <tr v-if="props.encounter.diagnoses.length === 0">
                            <td colspan="4" class="px-3 py-6 text-center text-sm text-gray-500">No diagnoses yet.</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Prescriptions -->
            <section class="bg-white shadow sm:rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-900">Prescriptions</h2>

                <form v-if="!locked" @submit.prevent="addPrescription" class="mt-4 grid gap-3 sm:grid-cols-4">
                    <input v-model="prescriptionForm.drug_name" type="text" placeholder="Drug name *" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input v-model="prescriptionForm.strength" type="text" placeholder="Strength (e.g. 500mg)" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input v-model="prescriptionForm.form" type="text" placeholder="Form (tab, syrup…)" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <select v-model="prescriptionForm.route" class="rounded-md border-gray-300 text-sm shadow-sm">
                        <option v-for="(label, key) in props.options.prescription_routes" :key="key" :value="key">{{ label }}</option>
                    </select>
                    <input v-model="prescriptionForm.dosage" type="text" placeholder="Dosage (e.g. 1 tab) *" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input v-model="prescriptionForm.frequency" type="text" placeholder="Frequency (e.g. 3x daily) *" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input v-model="prescriptionForm.duration" type="text" placeholder="Duration (e.g. 5 days)" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input v-model="prescriptionForm.quantity" type="number" min="0" placeholder="Quantity" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <textarea v-model="prescriptionForm.instructions" rows="2" placeholder="Special instructions" class="rounded-md border-gray-300 text-sm shadow-sm sm:col-span-3"></textarea>
                    <button type="submit" :disabled="prescriptionForm.processing" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">Add</button>
                </form>

                <table class="mt-4 min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Drug</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dose · Route · Freq</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration / Qty</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructions</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="p in props.encounter.prescriptions" :key="p.id">
                            <td class="px-3 py-2 text-sm text-gray-900">
                                <p class="font-medium">{{ p.drug_name }}</p>
                                <p class="text-xs text-gray-500">{{ [p.strength, p.form].filter(Boolean).join(' · ') }}</p>
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ p.dosage }} · {{ routeLabel(p.route) }} · {{ p.frequency }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ p.duration || '—' }}<span v-if="p.quantity" class="text-gray-500"> · {{ p.quantity }}</span></td>
                            <td class="px-3 py-2 text-sm text-gray-500 max-w-xs truncate">{{ p.instructions || '—' }}</td>
                            <td class="px-3 py-2 text-right">
                                <button v-if="!locked" @click="removePrescription(p.id)" class="text-rose-600 hover:text-rose-800 text-sm">Remove</button>
                            </td>
                        </tr>
                        <tr v-if="props.encounter.prescriptions.length === 0">
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500">No prescriptions yet.</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Attachments -->
            <section class="bg-white shadow sm:rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-900">Attachments</h2>

                <form v-if="!locked" @submit.prevent="addAttachment" class="mt-4 grid gap-3 sm:grid-cols-[1fr_1fr_auto]" enctype="multipart/form-data">
                    <input v-model="attachmentForm.label" type="text" placeholder="Label (e.g. Lab CBC)" class="rounded-md border-gray-300 text-sm shadow-sm" />
                    <input @change="(e) => attachmentForm.file = e.target.files[0]" type="file" class="text-sm" />
                    <button type="submit" :disabled="attachmentForm.processing" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">Upload</button>
                </form>
                <p v-if="attachmentForm.errors.file" class="mt-1 text-sm text-red-600">{{ attachmentForm.errors.file }}</p>

                <table class="mt-4 min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded by</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="a in props.encounter.attachments" :key="a.id">
                            <td class="px-3 py-2 text-sm text-gray-900">{{ a.label }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ a.file_name }}<br /><span class="text-xs text-gray-400">{{ a.mime_type }}</span></td>
                            <td class="px-3 py-2 text-sm text-gray-500">{{ formatBytes(a.size_bytes) }}</td>
                            <td class="px-3 py-2 text-sm text-gray-500">{{ a.uploaded_by || '—' }}</td>
                            <td class="px-3 py-2 text-right space-x-3">
                                <a :href="route('emr.attachments.download', [props.encounter.id, a.id])" class="text-indigo-600 hover:text-indigo-800 text-sm">Download</a>
                                <button v-if="!locked" @click="removeAttachment(a.id)" class="text-rose-600 hover:text-rose-800 text-sm">Remove</button>
                            </td>
                        </tr>
                        <tr v-if="props.encounter.attachments.length === 0">
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500">No attachments yet.</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </AppLayout>
</template>
