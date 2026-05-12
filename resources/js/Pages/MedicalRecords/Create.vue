<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MedicalRecordForm from '@/Pages/MedicalRecords/Shared/MedicalRecordForm.vue';

defineProps({ suggestedMrn: String });
</script>

<template>
    <Head title="Pasien Baru" />

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <Link :href="route('medicalrecords.index')" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</Link>
                    <h1 class="mt-2 text-2xl font-semibold text-gray-900">Pasien Baru</h1>
                    <p class="text-sm text-gray-500">MRN akan diterbitkan otomatis (preview: <span class="font-mono">{{ suggestedMrn }}</span>)</p>
                </div>
            </div>
            <MedicalRecordForm :record="{}" :submit-url="route('medicalrecords.store')" method="post" />
        </div>
    </div>
</template>
