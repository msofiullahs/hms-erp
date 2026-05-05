<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    registration: Object,
    action: String,
    method: String,
    route: String,
    title: String,
});

const form = useForm({
    patient_name: props.registration.patient_name || '',
    patient_number: props.registration.patient_number || '',
    department: props.registration.department || '',
    doctor: props.registration.doctor || '',
    admission_date: props.registration.admission_date || '',
    status: props.registration.status || 'pending',
    notes: props.registration.notes || '',
});

const submit = () => {
    form[props.method](props.route, {
        preserveState: false,
        onSuccess: () => {
            form.reset('patient_name', 'patient_number', 'department', 'doctor', 'admission_date', 'status', 'notes');
        },
    });
};

watch(() => props.registration, (value) => {
    if (value) {
        form.patient_name = value.patient_name || '';
        form.patient_number = value.patient_number || '';
        form.department = value.department || '';
        form.doctor = value.doctor || '';
        form.admission_date = value.admission_date || '';
        form.status = value.status || 'pending';
        form.notes = value.notes || '';
    }
});
</script>

<template>
    <div class="space-y-6 bg-white shadow sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
                <p class="mt-1 text-sm text-gray-600">Manage admission registration details.</p>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="patient_name" class="block text-sm font-medium text-gray-700">Patient Name</label>
                <input id="patient_name" v-model="form.patient_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.patient_name" class="mt-2 text-sm text-red-600">{{ form.errors.patient_name }}</p>
            </div>

            <div>
                <label for="patient_number" class="block text-sm font-medium text-gray-700">Patient Number</label>
                <input id="patient_number" v-model="form.patient_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.patient_number" class="mt-2 text-sm text-red-600">{{ form.errors.patient_number }}</p>
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <input id="department" v-model="form.department" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.department" class="mt-2 text-sm text-red-600">{{ form.errors.department }}</p>
            </div>

            <div>
                <label for="doctor" class="block text-sm font-medium text-gray-700">Doctor</label>
                <input id="doctor" v-model="form.doctor" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.doctor" class="mt-2 text-sm text-red-600">{{ form.errors.doctor }}</p>
            </div>

            <div>
                <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date</label>
                <input id="admission_date" v-model="form.admission_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.admission_date" class="mt-2 text-sm text-red-600">{{ form.errors.admission_date }}</p>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="pending">Pending</option>
                    <option value="admitted">Admitted</option>
                    <option value="discharged">Discharged</option>
                </select>
                <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</p>
            </div>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea id="notes" v-model="form.notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            <p v-if="form.errors.notes" class="mt-2 text-sm text-red-600">{{ form.errors.notes }}</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" @click="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ action }}
            </button>
        </div>
    </div>
</template>
