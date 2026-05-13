<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    refreshSeconds: { type: Number, default: 5 },
});

const nowServing = ref(null);
const recentCalled = ref([]);
const waiting = ref([]);
const updatedAt = ref(null);
const error = ref(null);
let timer = null;

async function fetchBoard() {
    try {
        const res = await fetch('/api/v1/selfservicequeue/board', {
            headers: { Accept: 'application/json' },
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        nowServing.value = data.now_serving || null;
        recentCalled.value = (data.called || []).slice(1);
        waiting.value = data.waiting || [];
        updatedAt.value = data.updated_at;
        error.value = null;
    } catch (e) {
        error.value = e.message;
    }
}

onMounted(() => {
    fetchBoard();
    timer = setInterval(fetchBoard, props.refreshSeconds * 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

function formatTime(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}
</script>

<template>
    <Head title="Queue Board" />

    <div class="min-h-screen bg-slate-950 px-8 py-8 text-white">
        <header class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-widest text-cyan-400">Queue Board</p>
                <h1 class="mt-1 text-3xl font-semibold">Hospital Queue System</h1>
            </div>
            <div class="text-right text-xs text-slate-400">
                <p>Auto-refresh every {{ props.refreshSeconds }} seconds</p>
                <p v-if="updatedAt" class="mt-1 font-mono">{{ formatTime(updatedAt) }}</p>
                <p v-if="error" class="mt-1 text-red-400">Connection lost: {{ error }}</p>
            </div>
        </header>

        <section class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-cyan-500/40 bg-gradient-to-br from-slate-900 to-slate-800 p-10 text-center">
                <p class="text-sm uppercase tracking-widest text-cyan-300">Now Serving</p>
                <template v-if="nowServing">
                    <p class="mt-6 font-mono text-9xl font-bold tracking-tight">{{ nowServing.ticket_number }}</p>
                    <p class="mt-4 text-2xl text-slate-200">{{ nowServing.service_label }}</p>
                    <p class="mt-2 text-lg text-cyan-300">Please proceed to Counter {{ nowServing.counter || '—' }}</p>
                </template>
                <template v-else>
                    <p class="mt-16 text-2xl text-slate-500">No tickets have been called yet.</p>
                </template>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <p class="text-sm uppercase tracking-widest text-slate-400">Previously Called</p>
                <ul class="mt-4 space-y-3">
                    <li
                        v-for="ticket in recentCalled"
                        :key="ticket.id"
                        class="flex items-center justify-between rounded-2xl bg-slate-800/60 px-4 py-3"
                    >
                        <div>
                            <p class="font-mono text-xl font-semibold">{{ ticket.ticket_number }}</p>
                            <p class="text-xs text-slate-400">{{ ticket.service_label }}</p>
                        </div>
                        <span class="rounded-xl bg-slate-700 px-3 py-1 text-xs">Counter {{ ticket.counter || '—' }}</span>
                    </li>
                    <li v-if="recentCalled.length === 0" class="text-sm text-slate-500">—</li>
                </ul>
            </div>
        </section>

        <section class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm uppercase tracking-widest text-slate-400">Waiting</p>
                <span class="rounded-xl bg-slate-800 px-3 py-1 text-xs text-slate-300">{{ waiting.length }} tickets</span>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                <div
                    v-for="ticket in waiting"
                    :key="ticket.id"
                    class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-center"
                >
                    <p class="font-mono text-2xl font-semibold">{{ ticket.ticket_number }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ ticket.service_label }}</p>
                </div>
                <p v-if="waiting.length === 0" class="col-span-full py-6 text-center text-slate-500">
                    No tickets waiting.
                </p>
            </div>
        </section>
    </div>
</template>
