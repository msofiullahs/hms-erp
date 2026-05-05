<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TeamMemberManager from '@/Pages/Teams/Partials/TeamMemberManager.vue';
import UpdateTeamNameForm from '@/Pages/Teams/Partials/UpdateTeamNameForm.vue';

defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});
</script>

<template>
    <AppLayout title="Team Settings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Team Settings
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-6">
                <div class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <UpdateTeamNameForm :team="team" :permissions="permissions" />
                </div>

                <div class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                    <TeamMemberManager
                        :team="team"
                        :available-roles="availableRoles"
                        :user-permissions="permissions"
                    />
                </div>

                <template v-if="permissions.canDeleteTeam && ! team.personal_team">
                    <div class="rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-[0_30px_60px_-50px_rgba(15,23,42,0.8)]">
                        <DeleteTeamForm :team="team" />
                    </div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
