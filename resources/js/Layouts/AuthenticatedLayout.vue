<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const user = usePage().props.auth.user;
const plan = usePage().props.can;

const canAccessBasic = usePage().props.can?.basic;
const canAccessPremium = usePage().props.can?.premium;
const canAccessPro = usePage().props.can?.pro;

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex items-center shrink-0">
                                <Link :href="route('dashboard')">
                                    <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
                                        ArticleHub
                                    </span>
                                    <!-- <ApplicationLogo
                                        class="block w-auto text-gray-800 fill-current h-9"
                                    /> -->
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>

                                <NavLink v-if="$page.props.auth.currentPlan === 'Basic'" :href="route('personalized.index')" :active="route().current('personalized.index')">
                                    Basic Content
                                </NavLink>

                                <NavLink v-if="$page.props.auth.currentPlan === 'Premium'" :href="route('personalized.index')" :active="route().current('personalized.index')">
                                    Exclusive Content
                                </NavLink>
                                
                                <NavLink v-if="$page.props.auth.currentPlan === 'Pro'" :href="route('personalized.index')" :active="route().current('personalized.index')">
                                    Exclusive Content
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Write Article -->
                            <!-- <div class="flex items-center ms-4">
                                <div class="flex items-center ms-4" v-if="$page.props.auth.currentPlan === 'Premium'">
                                    <Link :href="route('profile.edit')" class="px-2 py-1 text-sm font-semibold leading-tight rounded-md ">
                                        <font-awesome-icon icon="fa-solid fa-pen-to-square" />
                                    </Link>
                                </div>
                                
                                <div class="flex items-center ms-4" v-if="$page.props.auth.currentPlan === 'Pro'">
                                    <Link :href="route('profile.edit')" class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-sky-300 text-sky-700">
                                        Write Now
                                    </Link>
                                </div>
                            </div> -->
                            
                            <!-- Current Plan -->
                            <div class="flex items-center ms-4">
                                <div class="flex items-center ms-4" v-if="$page.props.auth.currentPlan === 'Basic'">
                                    <Link :href="route('profile.edit')" class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-amber-400 text-amber-700">
                                        {{ $page.props.auth.currentPlan }}
                                    </Link>
                                </div>

                                <div class="flex items-center ms-4" v-if="$page.props.auth.currentPlan === 'Premium'">
                                    <Link :href="route('profile.edit')" class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-rose-300 text-rose-700">
                                        {{ $page.props.auth.currentPlan }}
                                    </Link>
                                </div>
                                
                                <div class="flex items-center ms-4" v-if="$page.props.auth.currentPlan === 'Pro'">
                                    <Link :href="route('profile.edit')" class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-sky-300 text-sky-700">
                                        {{ $page.props.auth.currentPlan }}
                                    </Link>
                                </div>

                                <div class="flex items-center ms-4" v-if="$page.props.auth.currentPlan === 'Free'">
                                    <a href="/plans" class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-slate-300 text-slate-700">
                                        {{ $page.props.auth.currentPlan }}
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center -me-2 sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"
                            >
                                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
