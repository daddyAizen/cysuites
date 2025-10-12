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
                                <!-- {{ guest.name }} -->
                                <p> {{ guest }}</p>
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
                                    <!-- {{ guests?.room_code || 'Unknown' }} -->
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
<footer class="relative overflow-hidden bg-neutral-900">
  <svg class="absolute -bottom-20 start-1/2 w-[1900px] transform -translate-x-1/2" width="2745" height="488" viewBox="0 0 2745 488" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0.5 330.864C232.505 403.801 853.749 527.683 1482.69 439.719C2111.63 351.756 2585.54 434.588 2743.87 487" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 308.873C232.505 381.81 853.749 505.692 1482.69 417.728C2111.63 329.765 2585.54 412.597 2743.87 465.009" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 286.882C232.505 359.819 853.749 483.701 1482.69 395.738C2111.63 307.774 2585.54 390.606 2743.87 443.018" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 264.891C232.505 337.828 853.749 461.71 1482.69 373.747C2111.63 285.783 2585.54 368.615 2743.87 421.027" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 242.9C232.505 315.837 853.749 439.719 1482.69 351.756C2111.63 263.792 2585.54 346.624 2743.87 399.036" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 220.909C232.505 293.846 853.749 417.728 1482.69 329.765C2111.63 241.801 2585.54 324.633 2743.87 377.045" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 198.918C232.505 271.855 853.749 395.737 1482.69 307.774C2111.63 219.81 2585.54 302.642 2743.87 355.054" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 176.927C232.505 249.864 853.749 373.746 1482.69 285.783C2111.63 197.819 2585.54 280.651 2743.87 333.063" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 154.937C232.505 227.873 853.749 351.756 1482.69 263.792C2111.63 175.828 2585.54 258.661 2743.87 311.072" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 132.946C232.505 205.882 853.749 329.765 1482.69 241.801C2111.63 153.837 2585.54 236.67 2743.87 289.082" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 110.955C232.505 183.891 853.749 307.774 1482.69 219.81C2111.63 131.846 2585.54 214.679 2743.87 267.091" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 88.9639C232.505 161.901 853.749 285.783 1482.69 197.819C2111.63 109.855 2585.54 192.688 2743.87 245.1" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 66.9729C232.505 139.91 853.749 263.792 1482.69 175.828C2111.63 87.8643 2585.54 170.697 2743.87 223.109" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 44.9819C232.505 117.919 853.749 241.801 1482.69 153.837C2111.63 65.8733 2585.54 148.706 2743.87 201.118" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 22.991C232.505 95.9276 853.749 219.81 1482.69 131.846C2111.63 43.8824 2585.54 126.715 2743.87 179.127" class="stroke-neutral-700/50" stroke="currentColor"/>
    <path d="M0.5 1C232.505 73.9367 853.749 197.819 1482.69 109.855C2111.63 21.8914 2585.54 104.724 2743.87 157.136" class="stroke-neutral-700/50" stroke="currentColor"/>
  </svg>

  <div class="relative z-10">
    <div class="w-full max-w-5xl px-4 xl:px-0 py-10 lg:pt-16 mx-auto">
      <div class="inline-flex items-center">
        <!-- Logo -->
        <img class="h-20" src="https://cysuites.com/assets/icons/logo.png" alt="">
        <!-- End Logo -->

        <div class="border-s border-neutral-700 ps-5 ms-5">
          <p class="text-sm text-neutral-400">
            Cysuites Apartment & Hotel
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>
</template>
