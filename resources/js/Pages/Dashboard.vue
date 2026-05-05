<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <Head title="Dashboard - HMS-ERP" />

        <section class="space-y-8">
            <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
                <div class="rounded-[2rem] bg-slate-900 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-cyan-400">Welcome back</p>
                            <h2 class="mt-2 text-3xl font-semibold text-white">Hospital Management Dashboard</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-400 max-w-2xl">
                                Monitor core operations, quick access to admissions and patient registration, and keep your workflow flowing.
                            </p>
                        </div>
                        <div class="rounded-3xl bg-gradient-to-br from-cyan-500 to-slate-800 px-5 py-4 text-white shadow-lg shadow-cyan-500/20">
                            <p class="text-sm uppercase tracking-[0.3em] text-cyan-100">Status</p>
                            <p class="mt-3 text-2xl font-semibold">Live</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="rounded-[2rem] bg-slate-900 p-6 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.8)]">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Admissions</p>
                        <p class="mt-4 text-3xl font-semibold text-white">128</p>
                        <p class="mt-2 text-sm text-slate-400">New admissions this week</p>
                    </div>
                    <div class="rounded-[2rem] bg-slate-900 p-6 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.8)]">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Online registrations</p>
                        <p class="mt-4 text-3xl font-semibold text-white">47</p>
                        <p class="mt-2 text-sm text-slate-400">Portal submissions today</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[0.7fr_0.7fr]">
                <article class="rounded-[2rem] bg-slate-900 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-white">Activity Overview</h3>
                            <p class="mt-2 text-sm text-slate-400">A quick glance at the latest hospital workflow metrics.</p>
                        </div>
                        <span class="rounded-full bg-slate-800 px-4 py-2 text-sm text-slate-300">Updated now</span>
                    </div>
                    <div class="mt-8 min-h-[260px] rounded-[1.75rem] bg-slate-950 p-5">
                        <div class="h-full rounded-[1.5rem] bg-gradient-to-br from-cyan-600 to-slate-800 p-4 text-white">
                            <p class="text-sm uppercase tracking-[0.3em] text-cyan-100">Traffic</p>
                            <p class="mt-5 text-4xl font-semibold">76%</p>
                            <p class="mt-2 text-sm text-slate-200">Better than last week</p>
                            <div class="mt-8 flex gap-3">
                                <div class="flex-1 rounded-3xl bg-slate-900 p-4">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Admissions</p>
                                    <p class="mt-3 text-2xl font-semibold text-white">34</p>
                                </div>
                                <div class="flex-1 rounded-3xl bg-slate-900 p-4">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Online</p>
                                    <p class="mt-3 text-2xl font-semibold text-white">13</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] bg-slate-900 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                        <h3 class="text-xl font-semibold text-white">Quick Actions</h3>
                        <div class="mt-6 space-y-3">
                            <Link :href="route('admissionregistration.index')" class="block rounded-2xl bg-slate-950 px-4 py-3 text-sm text-slate-200 hover:bg-slate-900">View Admissions</Link>
                            <Link :href="route('online-registration.create')" class="block rounded-2xl bg-slate-950 px-4 py-3 text-sm text-slate-200 hover:bg-slate-900">Open Registration Portal</Link>
                            <button class="w-full rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-cyan-500">Generate Report</button>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-slate-900 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                        <h3 class="text-xl font-semibold text-white">Recent Activity</h3>
                        <ul class="mt-6 space-y-4 text-sm text-slate-400">
                            <li class="rounded-3xl bg-slate-950 p-4">New admission created by Dr. Smith.</li>
                            <li class="rounded-3xl bg-slate-950 p-4">Patient pre-registration submitted.</li>
                            <li class="rounded-3xl bg-slate-950 p-4">System report generated successfully.</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </AppLayout>
</template>
