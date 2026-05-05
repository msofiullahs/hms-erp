<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile">
        <template #header>
            <div class="flex flex-col gap-2">
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-400">Profile</p>
                <h1 class="text-3xl font-semibold text-white">Account Settings</h1>
                <p class="max-w-2xl text-sm text-slate-400">Manage your profile, password, two-factor authentication, and active sessions.</p>
            </div>
        </template>

        <div class="max-w-7xl mx-auto grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
            <section class="space-y-6">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation" class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword" class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <UpdatePasswordForm />
                </div>

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication" class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />
                </div>
            </section>

            <aside class="space-y-6">
                <div class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <h2 class="text-lg font-semibold text-white">Security & Sessions</h2>
                    <p class="mt-2 text-sm text-slate-400">Sign out other browser sessions and review account security tools.</p>
                    <div class="mt-6">
                        <LogoutOtherBrowserSessionsForm :sessions="sessions" />
                    </div>
                </div>

                <div v-if="$page.props.jetstream.hasAccountDeletionFeatures" class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <DeleteUserForm />
                </div>
            </aside>
        </div>
    </AppLayout>
</template>
