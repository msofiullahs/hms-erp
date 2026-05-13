<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    registrations: Object,
});

const deleteRegistration = (id) => {
    if (!confirm('Delete this registration?')) {
        return;
    }

    router.delete(route('admissionregistration.destroy', id), {
        preserveState: false,
        onSuccess: () => {
            // no-op
        },
    });
};
</script>

<template>
    <AppLayout title="Admission Registrations">
        <Head title="Admission Registrations" />

        <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-white">Admission Registrations</h1>
                        <p class="mt-1 text-sm text-slate-400">Manage all registered admissions in the hospital.</p>
                    </div>
                    <Link :href="route('admissionregistration.create')" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">New Admission</Link>
                </div>

                <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="registration in props.registrations.data" :key="registration.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ registration.patient_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ registration.department }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ registration.admission_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ registration.status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link :href="route('admissionregistration.show', registration.id)" class="text-indigo-600 hover:text-indigo-900">View</Link>
                                    <Link :href="route('admissionregistration.edit', registration.id)" class="text-green-600 hover:text-green-900">Edit</Link>
                                    <button @click="deleteRegistration(registration.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <p class="text-sm text-slate-400">
                        Showing {{ props.registrations.from }} to {{ props.registrations.to }} of {{ props.registrations.total }} entries
                    </p>
                    <div class="inline-flex items-center space-x-1">
                        <Link
                            v-for="link in props.registrations.links"
                            :key="link.label + link.url"
                            :href="link.url || '#'"
                            class="px-3 py-1 rounded-md border text-sm"
                            :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
    </AppLayout>
</template>
