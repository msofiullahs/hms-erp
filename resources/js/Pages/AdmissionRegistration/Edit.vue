<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdmissionRegistrationForm from '@/Pages/AdmissionRegistration/Shared/AdmissionRegistrationForm.vue';

const props = defineProps({
    registration: Object,
});

const routeName = route('admissionregistration.update', props.registration.id);
</script>

<template>
    <AppLayout title="Edit Admission">
        <Head title="Edit Admission" />

        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white">Edit Admission Registration</h1>
                    <p class="mt-1 text-sm text-slate-400">Update the selected admission registration details.</p>
                </div>
                <Link :href="route('admissionregistration.index')" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">Back to list</Link>
            </div>

            <AdmissionRegistrationForm
                :registration="props.registration"
                action="Update Admission"
                method="put"
                :route="routeName"
                title="Edit Admission Registration"
            />
        </div>
    </AppLayout>
</template>
