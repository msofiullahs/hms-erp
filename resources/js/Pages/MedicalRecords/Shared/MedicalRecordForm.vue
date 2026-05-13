<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    record: { type: Object, default: () => ({}) },
    submitUrl: { type: String, required: true },
    method: { type: String, default: 'post' },
});

const form = useForm({
    nik: props.record.nik || '',
    name: props.record.name || '',
    gender: props.record.gender || '',
    date_of_birth: props.record.date_of_birth ? String(props.record.date_of_birth).slice(0, 10) : '',
    place_of_birth: props.record.place_of_birth || '',
    blood_type: props.record.blood_type || '',
    address: props.record.address || '',
    city: props.record.city || '',
    province: props.record.province || '',
    postal_code: props.record.postal_code || '',
    phone: props.record.phone || '',
    email: props.record.email || '',
    religion: props.record.religion || '',
    marital_status: props.record.marital_status || '',
    occupation: props.record.occupation || '',
    education: props.record.education || '',
    insurance_type: props.record.insurance_type || 'umum',
    bpjs_number: props.record.bpjs_number || '',
    emergency_contact_name: props.record.emergency_contact_name || '',
    emergency_contact_phone: props.record.emergency_contact_phone || '',
    emergency_contact_relation: props.record.emergency_contact_relation || '',
    notes: props.record.notes || '',
});

function submit() {
    if (props.method === 'put') {
        form.put(props.submitUrl);
    } else {
        form.post(props.submitUrl);
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-8">
        <section class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-base font-semibold text-gray-900">Patient Identity</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                    <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">National ID (NIK)</label>
                    <input v-model="form.nik" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <p v-if="form.errors.nik" class="mt-1 text-sm text-red-600">{{ form.errors.nik }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <select v-model="form.gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">—</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="O">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input v-model="form.date_of_birth" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Place of Birth</label>
                    <input v-model="form.place_of_birth" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                    <select v-model="form.blood_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">—</option>
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>AB+</option><option>AB-</option>
                        <option>O+</option><option>O-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Religion</label>
                    <input v-model="form.religion" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Marital Status</label>
                    <input v-model="form.marital_status" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Occupation</label>
                    <input v-model="form.occupation" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Education</label>
                    <input v-model="form.education" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
            </div>
        </section>

        <section class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-base font-semibold text-gray-900">Contact & Address</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea v-model="form.address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <input v-model="form.city" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Province</label>
                    <input v-model="form.province" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                    <input v-model="form.postal_code" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input v-model="form.phone" type="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
            </div>
        </section>

        <section class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-base font-semibold text-gray-900">Insurance</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Insurance Type *</label>
                    <select v-model="form.insurance_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="umum">Self-pay</option>
                        <option value="bpjs">BPJS</option>
                        <option value="asuransi">Other Insurance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">BPJS Number</label>
                    <input v-model="form.bpjs_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <p v-if="form.errors.bpjs_number" class="mt-1 text-sm text-red-600">{{ form.errors.bpjs_number }}</p>
                </div>
            </div>
        </section>

        <section class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-base font-semibold text-gray-900">Emergency Contact</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.emergency_contact_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input v-model="form.emergency_contact_phone" type="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Relationship</label>
                    <input v-model="form.emergency_contact_relation" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
            </div>
        </section>

        <section class="bg-white shadow sm:rounded-lg p-6">
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea v-model="form.notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        </section>

        <div class="flex items-center justify-end gap-3">
            <Link :href="route('medicalrecords.index')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</Link>
            <button
                type="submit"
                :disabled="form.processing"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
            >Save</button>
        </div>
    </form>
</template>
