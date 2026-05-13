<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    ticket: Object,
    serviceLabel: String,
});

const countdown = ref(15);
let timer = null;

onMounted(() => {
    timer = setInterval(() => {
        countdown.value -= 1;
        if (countdown.value <= 0) {
            clearInterval(timer);
            window.location.href = route('selfservicequeue.kiosk.home');
        }
    }, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

function printTicket() {
    window.print();
}

function issuedAtFormatted() {
    if (!props.ticket.issued_at) return '-';
    return new Date(props.ticket.issued_at).toLocaleString();
}
</script>

<template>
    <Head title="Queue Ticket" />

    <div class="min-h-screen bg-slate-950 px-6 py-10 text-white print:bg-white print:text-black">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-10 text-center print:border-2 print:border-black print:bg-white">
                <p class="text-sm uppercase tracking-widest text-cyan-400 print:text-black">Queue Number</p>
                <p class="mt-4 font-mono text-8xl font-bold tracking-tight text-white print:text-black">
                    {{ props.ticket.ticket_number }}
                </p>
                <p class="mt-4 text-lg text-slate-300 print:text-black">{{ props.serviceLabel }}</p>

                <div class="mt-8 grid grid-cols-2 gap-4 text-left text-sm text-slate-400 print:text-black">
                    <div>
                        <p class="uppercase tracking-widest text-xs text-slate-500 print:text-black">Issued</p>
                        <p class="mt-1 text-white print:text-black">{{ issuedAtFormatted() }}</p>
                    </div>
                    <div>
                        <p class="uppercase tracking-widest text-xs text-slate-500 print:text-black">Kiosk</p>
                        <p class="mt-1 text-white print:text-black">{{ props.ticket.kiosk_id || '—' }}</p>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl border border-dashed border-slate-700 p-4 print:border-black">
                    <p class="font-mono text-xs text-slate-400 print:text-black">{{ props.ticket.qr_code }}</p>
                    <p class="mt-2 font-mono text-[10px] text-slate-500 print:text-black">{{ props.ticket.barcode_data }}</p>
                </div>

                <p class="mt-6 text-sm text-slate-400 print:hidden">
                    Please wait — your number will be called on the queue display.
                </p>
            </div>

            <div class="mt-6 flex items-center justify-between print:hidden">
                <Link
                    :href="route('selfservicequeue.kiosk.home')"
                    class="rounded-2xl border border-slate-700 px-5 py-3 text-sm text-slate-200 hover:border-cyan-400"
                >Done ({{ countdown }}s)</Link>
                <button
                    type="button"
                    @click="printTicket"
                    class="rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-cyan-400"
                >Print Ticket</button>
            </div>
        </div>
    </div>
</template>
