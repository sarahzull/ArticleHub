<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UserPlan from './Partials/UserPlan.vue';
import ChangePlan from './Partials/ChangePlan.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    userPlan: {
        type: Object,
    },
    plans: {
        type: Object,
    },
});

const showSubscriptionForm = ref(false);

const toggleSubscriptionForm = () => {
    showSubscriptionForm.value = !showSubscriptionForm.value;
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Profile</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg"  v-if="userPlan">
                    <UserPlan
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        :user-plan="userPlan"
                        @toggle-subscription-form="toggleSubscriptionForm"
                        :show-subscription-form="showSubscriptionForm"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg"  v-if="showSubscriptionForm">
                    <ChangePlan
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        :user-plan="userPlan"
                        :plans="plans"
                        @toggle-subscription-form="toggleSubscriptionForm"
                        :show-subscription-form="showSubscriptionForm"
                        class="max-w-xl"
                    />
                </div>
                
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg" v-if="!showSubscriptionForm">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg" v-if="!showSubscriptionForm">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg" v-if="!showSubscriptionForm">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
