<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    serviceTypes: Array,
    kioskId: { type: String, default: null },
});

const submitting = ref(null);

const form = useForm({
    service_type: '',
    customer_name: '',
    kiosk_id: props.kioskId,
});

function takeTicket(serviceType) {
    submitting.value = serviceType.key;
    form.service_type = serviceType.key;
    form.post(route('selfservicequeue.kiosk.issue'), {
        onFinish: () => (submitting.value = null),
    });
}
</script>

<template>
    <Head title="Anjungan Mandiri" />

    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-widest text-cyan-400">Anjungan Mandiri</p>
                    <h1 class="mt-2 text-4xl font-semibold">Ambil Nomor Antrian</h1>
                    <p class="mt-2 text-slate-400">Sentuh layanan yang anda butuhkan untuk mencetak nomor antrian.</p>
                </div>
                <div v-if="props.kioskId" class="rounded-2xl bg-slate-900 px-4 py-3 text-right text-xs text-slate-400">
                    <p class="uppercase tracking-widest">Kiosk</p>
                    <p class="mt-1 font-mono text-base text-white">{{ props.kioskId }}</p>
                </div>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <button
                    v-for="service in props.serviceTypes"
                    :key="service.key"
                    type="button"
                    :disabled="form.processing"
                    @click="takeTicket(service)"
                    class="group relative flex h-48 flex-col items-start justify-between rounded-3xl border border-slate-800 bg-slate-900 p-6 text-left transition hover:border-cyan-400 hover:bg-slate-800 disabled:opacity-50"
                >
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/10 text-2xl font-bold text-cyan-300">
                        {{ service.prefix }}
                    </span>
                    <div>
                        <p class="text-2xl font-semibold text-white">{{ service.label }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ service.description }}</p>
                    </div>
                    <span
                        v-if="submitting === service.key"
                        class="absolute inset-x-6 bottom-6 text-sm text-cyan-300"
                    >Menerbitkan tiket…</span>
                </button>
            </div>

            <p v-if="form.errors.service_type" class="mt-6 text-sm text-red-400">{{ form.errors.service_type }}</p>

            <footer class="mt-12 flex items-center justify-between border-t border-slate-800 pt-6 text-xs text-slate-500">
                <span>Sistem Antrian SIMRS</span>
                <span>{{ new Date().toLocaleString('id-ID') }}</span>
            </footer>
        </div>
    </div>
</template>
