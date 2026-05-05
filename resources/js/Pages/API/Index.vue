<script setup>
import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});
</script>

<template>
    <AppLayout title="API Tokens">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <ApiTokenManager
                        :tokens="tokens"
                        :available-permissions="availablePermissions"
                        :default-permissions="defaultPermissions"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
