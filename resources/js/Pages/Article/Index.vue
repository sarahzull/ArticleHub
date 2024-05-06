<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    articles: Object,
    currentPlan: Object,
    topAuthors: Object,
    premiumArticles: Object,
    basicArticles: Object,
    flash: Object
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
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" v-if="$page.props.auth.currentPlan === 'Basic'">Basic Content</h2>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" v-else>Exclusive Content</h2>
        </template>

        <!-- <div v-if="$page.props.auth.currentPlan === 'Basic'" class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">This page is for basic plan</div>
                </div>
            </div>
        </div> -->

        <!-- <div v-if="$page.props.auth.currentPlan === 'Premium'" class="py-12">
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
        </div> -->

        <!-- Top authors -->
        <div class="pt-4 pb-2" v-if="$page.props.auth.currentPlan === 'Premium' || $page.props.auth.currentPlan === 'Pro'">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h3 class="text-lg font-semibold leading-tight text-gray-800">Top Authors</h3>
                <div class="flex flex-row flex-wrap gap-6">
                <div class="flex items-center justify-center flex-1 h-20 px-3 py-6 my-4 text-center bg-white rounded-lg shadow-sm sm:w-64" v-for="author in topAuthors" :key="author.id">
                    <span class="text-gray-900">
                        {{author.name}}
                    </span>
                </div>
                </div>  
            </div>
        </div>

        <!-- Premium Articles -->
        <div class="pt-4 pb-2" v-if="$page.props.auth.currentPlan === 'Premium' || $page.props.auth.currentPlan === 'Pro'">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h3 class="text-lg font-semibold leading-tight text-gray-800">Premium Articles</h3>
                <div class="p-10 my-4 bg-white rounded-lg shadow-sm">
                    <span class="text-gray-900 ">
                    <div v-for="article in premiumArticles" :key="article.id" class="mb-8">
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
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <!-- Basic Articles -->
        <div class="pt-4 pb-2" v-else>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h3 class="text-lg font-semibold leading-tight text-gray-800">Articles</h3>
                <div class="p-10 my-4 bg-white rounded-lg shadow-sm">
                    <span class="text-gray-900 ">
                    <div v-for="article in basicArticles" :key="article.id" class="mb-8">
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
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    
    </AuthenticatedLayout>
</template>
