<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const mobileMenuVisible = ref(false);

const menuItems = [
    { label: 'Dashboard', route: 'dashboard' },
    { label: 'Admission Registration', route: 'admissionregistration.index' },
    { label: 'Online Registration Portal', route: 'online-registration.create' },
];

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <Head :title="title" />

        <div class="flex">
            <aside class="hidden xl:flex flex-col w-72 shrink-0 bg-slate-900 border-r border-slate-800 px-6 py-8">
                <div class="mb-10">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-600 text-xl font-bold text-slate-950">H</span>
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-cyan-400">HMS-ERP</p>
                            <h2 class="text-xl font-semibold text-white">Material Dashboard</h2>
                        </div>
                    </Link>
                </div>

                <nav class="space-y-2">
                    <template v-for="item in menuItems" :key="item.label">
                        <Link
                            :href="route(item.route)"
                            class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm font-medium text-slate-200 transition hover:border-cyan-500 hover:bg-slate-900"
                        >
                            <span>{{ item.label }}</span>
                        </Link>
                    </template>
                </nav>

                <div class="mt-auto pt-8 border-t border-slate-800">
                    <button
                        @click="logout"
                        class="w-full rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-500"
                    >
                        Log Out
                    </button>
                </div>
            </aside>

            <div class="flex-1">
                <header class="sticky top-0 z-20 bg-slate-950/95 border-b border-slate-800 px-6 py-4 backdrop-blur xl:hidden">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-cyan-400">HMS-ERP</p>
                            <h1 class="text-lg font-semibold text-white">{{ title }}</h1>
                        </div>
                        <button
                            @click="mobileMenuVisible = !mobileMenuVisible"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-cyan-400 ring-1 ring-slate-800 hover:bg-slate-800"
                        >
                            <span class="text-xl">☰</span>
                        </button>
                    </div>
                    <transition name="fade">
                        <div v-if="mobileMenuVisible" class="mt-4 space-y-2 rounded-3xl border border-slate-800 bg-slate-900 p-4">
                            <template v-for="item in menuItems" :key="item.label">
                                <Link
                                    :href="route(item.route)"
                                    class="block rounded-2xl px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-800"
                                >
                                    {{ item.label }}
                                </Link>
                            </template>
                            <button
                                @click="logout"
                                class="w-full rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-500"
                            >
                                Log Out
                            </button>
                        </div>
                    </transition>
                </header>

                <main class="min-h-screen px-6 py-8 xl:px-10 xl:py-10">
                    <div v-if="$slots.header" class="mb-6 rounded-[2rem] border border-slate-800 bg-slate-900 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                        <slot name="header" />
                    </div>

                    <div class="space-y-6">
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

