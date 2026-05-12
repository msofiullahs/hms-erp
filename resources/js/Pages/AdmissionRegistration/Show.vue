<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    registration: Object,
});
</script>

<template>
    <AppLayout title="Admission Details">
        <Head title="Admission Registration Details" />

        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Admission Registration Details</h1>
                    <p class="mt-1 text-sm text-gray-600">Review the selected admission registration record.</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('admissionregistration.edit', props.registration.id)" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700">Edit</Link>
                    <Link :href="route('admissionregistration.index')" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">Back to list</Link>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500">Patient Name</h2>
                        <p class="mt-2 text-lg text-gray-900">{{ props.registration.patient_name }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500">Patient Number</h2>
                        <p class="mt-2 text-lg text-gray-900">{{ props.registration.patient_number || '—' }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500">Department</h2>
                        <p class="mt-2 text-lg text-gray-900">{{ props.registration.department }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500">Doctor</h2>
                        <p class="mt-2 text-lg text-gray-900">{{ props.registration.doctor || '—' }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500">Admission Date</h2>
                        <p class="mt-2 text-lg text-gray-900">{{ props.registration.admission_date }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500">Status</h2>
                        <p class="mt-2 text-lg text-gray-900 capitalize">{{ props.registration.status }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-sm font-semibold text-gray-500">Notes</h2>
                    <p class="mt-2 text-gray-700 whitespace-pre-line">{{ props.registration.notes || 'No notes available.' }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
