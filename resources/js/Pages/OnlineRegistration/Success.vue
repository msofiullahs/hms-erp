<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({ registration: Object });
</script>

<template>
    <Head title="Registration Confirmed" />
    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-slate-900 p-10 shadow-xl shadow-slate-950/30">
                <h1 class="text-3xl font-semibold text-white">Registration Confirmed</h1>
                <p class="mt-3 text-slate-400">Your pre-registration has been verified successfully.</p>

                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-950/70 p-6">
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Name</p>
                        <p class="mt-3 text-lg text-white">{{ props.registration.name }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/70 p-6">
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Visit date</p>
                        <p class="mt-3 text-lg text-white">{{ props.registration.visit_date }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/70 p-6">
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Email</p>
                        <p class="mt-3 text-lg text-white">{{ props.registration.email }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/70 p-6">
                        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Phone</p>
                        <p class="mt-3 text-lg text-white">{{ props.registration.phone }}</p>
                    </div>
                </div>

                <div class="mt-8 rounded-3xl bg-slate-900/80 p-6 text-slate-300">
                    Your OTP verification is complete. Please save this confirmation and contact the hospital if you need to update your visit details.
                </div>
            </div>
        </div>
    </div>
</template>
