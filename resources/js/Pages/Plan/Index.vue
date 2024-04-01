<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    articles: Object,
    currentPlan: Object,
});

const page = usePage();
const flash = page.props.flash;

if (flash.success) {
    Swal.fire({
        title: "Success!",
        text: flash.success,
        icon: "success"
    });
    page.props.flash.success = null;
} else if (flash.error) {
    Swal.fire({
        title: "Error",
        text: flash.error,
        icon: "error"
    });
    page.props.flash.success = null;
}

const canAccessBasic = usePage().props.can.basic;
const canAccessPremium = usePage().props.can.premium;
const canAccessPro = usePage().props.can.pro;
</script>

<template>
    <Head title="" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template> -->

        <div v-if="$page.props.auth.currentPlan === 'Basic'" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This page is for basic plan</div>
                </div>
            </div>
        </div>

        <div v-if="$page.props.auth.currentPlan === 'Premium'" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This page is for premium plan</div>
                </div>
            </div>
        </div>

        <div v-if="$page.props.auth.currentPlan === 'Pro'" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This page is for pro plan</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
