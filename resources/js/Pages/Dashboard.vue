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
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div v-if="currentPlan == 'Free'" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
        
        <div v-else class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-for="article in articles" :key="article.id" class="mb-8">
                            <div class="mb-2">
                                <h3 class="text-2xl font-bold">
                                {{ article.title }} 
                                <span class="mx-2">•</span> 
                                <span class="text-base font-light">{{ article.published_at_date }}</span>
                                </h3> 
                                by 
                                <span class="text-gray-500">{{ article.author.name }}</span>
                            </div>
                            <div v-html="article.content" class="mb-2 text-gray-700"></div>

                            <div class="mb-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 rounded-md bg-gray-50 ring-1 ring-inset ring-gray-500/10">{{ article.category.name }}</span>
                                <!-- <span class="text-sm font-semibold text-gray-600">Category:</span>
                                <span class="text-sm text-gray-500">{{ article.category.name }}</span>   -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
