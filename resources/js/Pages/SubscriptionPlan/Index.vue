<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({ 
  plans: Object 
})

const redirectToPlan = (externalId, planId) => {
  const url = `/plans/redirect?external_id=${externalId}&plan_id=${planId}`;
  window.location.href = url; 
};
</script>

<template>
  <Head title="Plans" />

  <AuthenticatedLayout>
      <!-- <template #header>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">Subscription Plans</h2>
      </template> -->

      <main class="max-w-6xl px-8 pt-10 mx-auto lg:pb-0 sm:pb-20">
        <div class="max-w-md mx-auto text-center mb-14">
          <h1 class="mb-6 text-4xl font-semibold lg:text-5xl"><span class="text-indigo-600">Subscription</span> Plans</h1>
          <p class="text-xl font-medium text-gray-500">Choose a plan that works best for you!</p>
        </div>

        <div class="flex flex-col items-center justify-between lg:flex-row lg:items-start">
          <div class="flex-1 order-2 w-full p-8 mt-8 bg-white shadow-xl rounded-3xl sm:w-96 lg:w-full lg:order-1 lg:rounded-r-none">
            <div class="flex items-center border-b border-gray-300 mb-7 pb-7">
              <img src="https://res.cloudinary.com/williamsondesign/abstract-1.jpg"  alt="" class="w-20 h-20 rounded-3xl" />
              <div class="ml-5">
                <span class="block text-2xl font-semibold">{{ plans[0].name.en ?? '' }}</span>
                <span>
                  <span class="text-xl font-medium text-gray-500 align-top">$&thinsp;</span><span class="text-3xl font-bold">
                    {{ plans[0].charge.prices[0].amount ?? ''}}
                  </span></span><span class="font-medium text-gray-500">/ month
                </span>
              </div>
            </div>
            <ul class="font-medium text-gray-500 mb-7">
              <li class="flex mb-2 text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3">Access to a selection of <span class="text-black">curated articles</span></span>
              </li>
              <li class="flex mb-2 text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3"><span class="text-black">Unlimited</span> reading</span>
              </li>
              <li class="flex text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3"><span class="text-black">Basic</span> features</span>
              </li>
            </ul>
            <!-- <a href="#" 
              @click.prevent="redirectToPlan(plans[0].external_id, plans[0].id)"
              :disabled="$page.props.auth.currentPlan === plans[0].name.en"
              class="flex items-center justify-center px-4 py-5 text-xl text-center text-white bg-indigo-600 rounded-xl">
              Choose Plan
              <img src="https://res.cloudinary.com/williamsondesign/arrow-right.svg" class="ml-2" />
            </a> -->
            <Link 
                :href="route('plans.redirect', { external_id: plans[0].external_id, plan_id: plans[0].id })"
                as="button"
                :class="{
                    'cursor-not-allowed bg-gray-400': $page.props.auth.currentPlan === plans[0].name.en,
                    'bg-indigo-600': $page.props.auth.currentPlan !== plans[0].name.en
                }"
                class="w-full rounded-xl"
            >
                <div class="flex items-center justify-center px-4 py-5 text-xl text-center text-white rounded-xl">
                    Choose Plan
                    <img src="https://res.cloudinary.com/williamsondesign/arrow-right.svg" class="ml-2" />
                </div>
            </Link>
          </div>
          
          <div class="flex-1 order-1 w-full p-8 text-gray-400 bg-gray-900 shadow-xl rounded-3xl sm:w-96 lg:w-full lg:order-2 lg:mt-0">
            <div class="flex items-center pb-8 mb-8 border-b border-gray-600">
              <img src="https://res.cloudinary.com/williamsondesign/abstract-2.jpg"  alt="" class="w-20 h-20 rounded-3xl" />
              <div class="ml-5">
                <span class="block text-3xl font-semibold text-white">{{ plans[1].name.en ?? '' }}</span>
                <span>
                  <span class="text-xl font-medium align-top">$&thinsp;</span><span class="text-3xl font-bold text-white">
                    {{ plans[1].charge.prices[0].amount ?? ''}}
                  </span></span><span class="font-medium">/ month
                </span>
              </div>
            </div>
            <ul class="mb-10 text-xl font-medium">
              <li class="flex mb-6">
                <img src="https://res.cloudinary.com/williamsondesign/check-white.svg" />
                <span class="ml-3"><span class="text-white">Full access</span> to all articles</span>
              </li>
              <li class="flex mb-6">
                <img src="https://res.cloudinary.com/williamsondesign/check-white.svg" />
                <span class="ml-3"><span class="text-white">Exclusive content </span>from top authors & <span class="text-white">Advanced features</span> like offline reading and article highlights</span>
              </li>
              <!-- <li class="flex mb-6">
                <img src="https://res.cloudinary.com/williamsondesign/check-white.svg" />
                <span class="ml-3"><span class="text-white">Advanced features</span> like offline reading and article highlights</span>
              </li> -->
              <li class="flex">
                <img src="https://res.cloudinary.com/williamsondesign/check-white.svg" />
                <span class="ml-3"><span class="text-white">Personalized recommendations</span> tailored to your interests</span>
              </li>
            </ul>
            <!-- <a 
              href="#" 
              @click.prevent="redirectToPlan(plans[1].external_id, plans[1].id)" 
              :disabled="$page.props.auth.currentPlan === plans[1].name.en"
              class="flex items-center justify-center px-4 py-5 text-xl text-center text-white bg-indigo-600 rounded-xl">
              Choose Plan
              <img src="https://res.cloudinary.com/williamsondesign/arrow-right.svg" class="ml-2" />
            </a> -->
            <Link 
                :href="route('plans.redirect', { external_id: plans[1].external_id, plan_id: plans[1].id })"
                as="button"
                :class="{
                    'cursor-not-allowed bg-gray-400': $page.props.auth.currentPlan === plans[1].name.en,
                    'bg-indigo-600': $page.props.auth.currentPlan !== plans[1].name.en
                }"
                class="w-full rounded-xl"
            >
                <div class="flex items-center justify-center px-4 py-5 text-xl text-center text-white rounded-xl">
                    Choose Plan
                    <img src="https://res.cloudinary.com/williamsondesign/arrow-right.svg" class="ml-2" />
                </div>
            </Link>
          </div>
          
          <div class="flex-1 order-3 w-full p-8 mt-8 bg-white shadow-xl rounded-3xl sm:w-96 lg:w-full lg:order-3 lg:rounded-l-none">
            <div class="flex items-center border-b border-gray-300 mb-7 pb-7">
              <img src="https://res.cloudinary.com/williamsondesign/abstract-3.jpg"  alt="" class="w-20 h-20 rounded-3xl" />
              <div class="ml-5">
                <span class="block text-2xl font-semibold">{{ plans[2].name.en ?? '' }}</span>
                <span>
                  <span class="text-xl font-medium text-gray-500 align-top">$&thinsp;</span><span class="text-3xl font-bold">
                    {{ plans[2].charge.prices[0].amount ?? ''}}
                  </span></span><span class="font-medium text-gray-500">/ month
                </span>
              </div>
            </div>
            <ul class="font-medium text-gray-500 mb-7">
              <li class="flex mb-2 text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3">All features in <span class="text-black">Premium Plan</span></span>
              </li>
              <li class="flex mb-2 text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3"><span class="text-black">Early access</span> to new articles</span>
              </li>
              <li class="flex text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3"><span class="text-black">Priority support</span></span>
              </li>
              <li class="flex text-lg">
                <img src="https://res.cloudinary.com/williamsondesign/check-grey.svg" />
                <span class="ml-3"><span class="text-black">Exclusive</span> webinars or events</span>
              </li>
            </ul>
            <!-- <a 
              href="#" 
              @click.prevent="redirectToPlan(plans[2].external_id, plans[2].id)" 
              :disabled="$page.props.auth.currentPlan === plans[2].name.en"
              class="flex items-center justify-center px-4 py-5 text-xl text-center text-white bg-indigo-600 rounded-xl">
              Choose Plan
              <img src="https://res.cloudinary.com/williamsondesign/arrow-right.svg" class="ml-2" />
            </a> -->
            <Link 
                :href="route('plans.redirect', { external_id: plans[2].external_id, plan_id: plans[2].id })"
                as="button"
                :class="{
                    'cursor-not-allowed bg-gray-400': $page.props.auth.currentPlan === plans[2].name.en,
                    'bg-indigo-600': $page.props.auth.currentPlan !== plans[2].name.en
                }"
                class="w-full rounded-xl"
            >
                <div class="flex items-center justify-center px-4 py-5 text-xl text-center text-white rounded-xl">
                    Choose Plan
                    <img src="https://res.cloudinary.com/williamsondesign/arrow-right.svg" class="ml-2" />
                </div>
            </Link>
          </div>
        </div>
  </main>
  </AuthenticatedLayout>
</template>