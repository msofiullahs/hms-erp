<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdmissionRegistrationForm from '@/Pages/AdmissionRegistration/Shared/AdmissionRegistrationForm.vue';

const registration = {
    patient_name: '',
    patient_number: '',
    department: '',
    doctor: '',
    admission_date: '',
    status: 'pending',
    notes: '',
};

const routeName = route('admissionregistration.store');
</script>

<template>
    <AppLayout title="New Admission">
        <Head title="New Admission" />

        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white">New Admission Registration</h1>
                    <p class="mt-1 text-sm text-slate-400">Create a new admission registration record.</p>
                </div>
                <Link :href="route('admissionregistration.index')" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">Back to list</Link>
            </div>

            <AdmissionRegistrationForm
                :registration="registration"
                action="Create Admission"
                method="post"
                :route="routeName"
                title="Create Admission Registration"
            />
        </div>
    </AppLayout>
</template>
