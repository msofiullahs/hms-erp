<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MedicalRecordForm from '@/Pages/MedicalRecords/Shared/MedicalRecordForm.vue';

defineProps({ suggestedMrn: String });
</script>

<template>
    <AppLayout title="New Patient">
        <Head title="New Patient" />

        <div class="mx-auto max-w-4xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <Link :href="route('medicalrecords.index')" class="text-sm text-cyan-300 hover:text-cyan-200">&larr; Back</Link>
                    <h1 class="mt-2 text-2xl font-semibold text-white">New Patient</h1>
                    <p class="text-sm text-slate-400">MRN will be auto-generated (preview: <span class="font-mono text-slate-300">{{ suggestedMrn }}</span>)</p>
                </div>
            </div>
            <MedicalRecordForm :record="{}" :submit-url="route('medicalrecords.store')" method="post" />
        </div>
    </AppLayout>
</template>
