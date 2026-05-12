<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MedicalRecordForm from '@/Pages/MedicalRecords/Shared/MedicalRecordForm.vue';

const props = defineProps({ record: Object });
</script>

<template>
    <Head :title="`Edit ${props.record.mrn}`" />

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <Link :href="route('medicalrecords.show', props.record.id)" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</Link>
                    <h1 class="mt-2 text-2xl font-semibold text-gray-900">Edit <span class="font-mono">{{ props.record.mrn }}</span></h1>
                </div>
            </div>
            <MedicalRecordForm :record="props.record" :submit-url="route('medicalrecords.update', props.record.id)" method="put" />
        </div>
    </div>
</template>
