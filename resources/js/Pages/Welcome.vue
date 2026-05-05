<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const page = usePage();
</script>

<template>
    <Head title="HMS-ERP" />

    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative overflow-hidden pb-20">
            <div class="absolute inset-x-0 top-0 h-72 bg-gradient-to-br from-cyan-600 via-slate-900 to-slate-950 opacity-90" />
            <div class="relative mx-auto max-w-7xl px-6 py-12 lg:px-8">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-400/15 text-cyan-300 ring-1 ring-white/10">H</span>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">HMS-ERP</p>
                            <p class="text-xs text-slate-400">Hospital Management System</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm font-medium text-slate-200">
                        <Link v-if="page.props.auth.user" :href="route('dashboard')" class="rounded-full border border-slate-700 bg-white/5 px-4 py-2 transition hover:bg-white/10">Dashboard</Link>
                        <Link v-else-if="canLogin" :href="route('login')" class="rounded-full border border-slate-700 bg-white/5 px-4 py-2 transition hover:bg-white/10">Log in</Link>
                        <Link v-if="!page.props.auth.user && canRegister" :href="route('register')" class="rounded-full bg-cyan-400 px-4 py-2 text-slate-950 transition hover:bg-cyan-300">Register</Link>
                    </div>
                </nav>

                <main class="mt-16 grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] lg:items-center">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Hospital ERP</p>
                        <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Modern hospital operations, unified in one platform.</h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">HMS-ERP brings admission, registration, billing, inventory, clinical workflows, and integrations into a single responsive system built with Laravel and Vue.</p>

                        <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                            <Link v-if="page.props.auth.user" :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full bg-cyan-400 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-300">Open Dashboard</Link>
                            <Link v-else-if="canLogin" :href="route('login')" class="inline-flex items-center justify-center rounded-full border border-slate-700 bg-white/5 px-6 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">Log In</Link>
                            <Link v-if="!page.props.auth.user && canRegister" :href="route('register')" class="inline-flex items-center justify-center rounded-full bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-200">Start Free Trial</Link>
                            <Link :href="route('online-registration.create')" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Public Portal</Link>
                        </div>

                        <div class="mt-12 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-lg">
                                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Modules</p>
                                <p class="mt-4 text-3xl font-semibold text-white">24+</p>
                                <p class="mt-2 text-sm text-slate-400">Core hospital modules including Admissions, Billing, Pharmacy, Radiology, and more.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-lg">
                                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Deployment</p>
                                <p class="mt-4 text-3xl font-semibold text-white">Onsite + Remote</p>
                                <p class="mt-2 text-sm text-slate-400">Flexible implementation with both local and remote support.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-cyan-500/10 bg-white/5 p-8 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Project Snapshot</p>
                        <div class="mt-8 space-y-6">
                            <div class="rounded-3xl bg-slate-900/80 p-6">
                                <p class="text-sm text-slate-400">Primary focus</p>
                                <p class="mt-3 text-xl font-semibold text-white">Patient admission and registration workflows</p>
                            </div>
                            <div class="rounded-3xl bg-slate-900/80 p-6">
                                <p class="text-sm text-slate-400">Tech stack</p>
                                <p class="mt-3 text-xl font-semibold text-white">Laravel, Vue 3, Inertia, Tailwind</p>
                            </div>
                            <div class="rounded-3xl bg-slate-900/80 p-6">
                                <p class="text-sm text-slate-400">License</p>
                                <p class="mt-3 text-xl font-semibold text-white">Lifetime</p>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl bg-slate-900/95 p-8 shadow-xl shadow-slate-950/20 ring-1 ring-white/5">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Admissions</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Patient onboarding</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">Streamline registration, capture patient data, and connect admission workflows with a modern patient experience.</p>
                </div>
                <div class="rounded-3xl bg-slate-900/95 p-8 shadow-xl shadow-slate-950/20 ring-1 ring-white/5">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Clinical</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Clinical operations</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">Manage electronic medical records, emergency care, inpatient services, and integrated clinical reporting.</p>
                </div>
                <div class="rounded-3xl bg-slate-900/95 p-8 shadow-xl shadow-slate-950/20 ring-1 ring-white/5">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Finance</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Billing & inventory</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">Control stock, generate invoices, and handle hospital finance through centralized ERP workflows.</p>
                </div>
            </div>

            <div class="mt-16 rounded-3xl bg-slate-900/95 p-10 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <div class="grid gap-8 lg:grid-cols-3">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Theme</p>
                        <p class="mt-3 text-xl font-semibold text-white">HMS-ERP</p>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Stack</p>
                        <p class="mt-3 text-xl font-semibold text-white">Laravel, Vue 3, Inertia, Tailwind</p>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Support</p>
                        <p class="mt-3 text-xl font-semibold text-white">Onsite & Remote implementation</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="border-t border-slate-800 bg-slate-950/80 py-8 text-center text-sm text-slate-500">
            <p>Built with Laravel {{ laravelVersion }}, PHP {{ phpVersion }}, Vue 3, and Inertia.</p>
            <p class="mt-2">HMS-ERP · Hospital Management System · Modular architecture</p>
        </footer>
    </div>
</template>
