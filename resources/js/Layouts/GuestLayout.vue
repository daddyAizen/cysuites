<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import NavLink from '@/Components/NavLink.vue';


defineProps({
    title: String,
    guest: Object,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('guests.logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-white border-b border-gray-100 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <!-- Logo -->
                        <div class="flex items-center space-x-2">
                            <ApplicationMark class="block h-8 w-auto" />
                            <span class="font-semibold text-lg text-gray-700">
                                Guest Portal
                            </span>
                           <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('guests.dashboard')" :active="route().current('guests.dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('guests.menu')" :active="route().current('guests.menu')">
                                    Menus
                                </NavLink>
                                <NavLink :href="route('guests.orders')" :active="route().current('guests.orders')">
                                    Orders
                                </NavLink>
                                 <NavLink :href="route('guests.reservations')" :active="route().current('guests.reservations')">
                                    Reservations
                                </NavLink>

                            </div>
                        </div>
                        </div>

                        <!-- Guest Info + Logout -->
                        <div class="flex items-center space-x-6">
                            <div class="text-sm text-gray-600">
                                Room:
                                <span class="font-semibold text-gray-800">
                                    {{ guest?.room_number || 'Unknown' }}
                                </span>
                            </div>

                            <form @submit.prevent="logout">
                                <button
                                    type="submit"
                                    class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm font-medium"
                                >
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-6">
                <slot />
            </main>
        </div>
    </div>
</template>
