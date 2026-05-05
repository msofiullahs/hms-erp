<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({ registration: Object });
const page = usePage();
const form = useForm({
    registration_id: props.registration.id,
    otp_code: '',
});

function submit() {
    form.post(route('online-registration.verify'));
}
</script>

<template>
    <Head title="Verify Registration" />
    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-slate-900 p-10 shadow-xl shadow-slate-950/30">
                <h1 class="text-3xl font-semibold text-white">Verify your pre-registration</h1>
                <p class="mt-3 text-slate-400">An OTP was sent to {{ props.registration.email }}. Enter the code to confirm your reservation.</p>

                <div v-if="page.props.flash.error" class="mt-6 rounded-2xl bg-red-500/10 p-4 text-sm text-red-200">
                    {{ page.props.flash.error }}
                </div>

                <div class="mt-10 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-200">OTP code</label>
                        <input v-model="form.otp_code" type="text" maxlength="6" class="mt-2 block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-cyan-400 focus:outline-none" />
                        <p v-if="form.errors.otp_code" class="mt-1 text-sm text-red-400">{{ form.errors.otp_code }}</p>
                    </div>

                    <button @click.prevent="submit" class="inline-flex items-center justify-center rounded-full bg-cyan-400 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">Verify OTP</button>
                </div>
            </div>
        </div>
    </div>
</template>
