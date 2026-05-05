<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    department: '',
    visit_date: '',
    notes: '',
    consent: false,
});

function submit() {
    form.post(route('online-registration.store'));
}
</script>

<template>
    <Head title="Online Registration" />

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-slate-900 p-10 shadow-xl shadow-slate-950/30">
                <h1 class="text-3xl font-semibold text-white">Pre-register for hospital services</h1>
                <p class="mt-3 text-slate-400">Submit your details and receive an OTP code to verify your reservation.</p>

                <div class="mt-10 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-200">Full name</label>
                        <input v-model="form.name" type="text" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none" />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Email</label>
                            <input v-model="form.email" type="email" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none" />
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Phone</label>
                            <input v-model="form.phone" type="tel" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none" />
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-400">{{ form.errors.phone }}</p>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Department</label>
                            <input v-model="form.department" type="text" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Preferred visit date</label>
                            <input v-model="form.visit_date" type="date" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none" />
                            <p v-if="form.errors.visit_date" class="mt-1 text-sm text-red-400">{{ form.errors.visit_date }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-200">Additional notes</label>
                        <textarea v-model="form.notes" rows="4" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none"></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <input v-model="form.consent" type="checkbox" id="consent" class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-cyan-400 focus:ring-cyan-400" />
                        <label for="consent" class="text-sm text-slate-300">I confirm that I want to receive an OTP code to complete registration.</label>
                    </div>
                    <p v-if="form.errors.consent" class="text-sm text-red-400">{{ form.errors.consent }}</p>

                    <button @click.prevent="submit" class="inline-flex items-center justify-center rounded-full bg-cyan-400 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">Send OTP</button>
                </div>
            </div>
        </div>
    </div>
</template>
