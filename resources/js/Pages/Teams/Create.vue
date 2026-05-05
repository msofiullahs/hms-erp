<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateTeamForm from '@/Pages/Teams/Partials/CreateTeamForm.vue';
</script>

<template>
    <AppLayout title="Create Team">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Team
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <CreateTeamForm />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
