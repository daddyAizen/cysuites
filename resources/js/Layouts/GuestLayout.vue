<script setup>
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

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
      <!-- Navbar -->
      <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center space-x-2">
              <Link :href="route('guests.dashboard')" class="flex items-center space-x-2">
                <ApplicationMark class="h-9 w-auto" />
                <span class="text-lg font-semibold text-gray-700 hidden sm:block">Cysuites</span>
              </Link>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex items-center space-x-6">
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

            <!-- Guest Info + Logout (Desktop) -->
            <div class="hidden sm:flex items-center space-x-6">
              <div class="text-sm text-gray-600">
                <span class="font-semibold text-gray-800">{{ guest?.name }}</span>
              </div>

              <form @submit.prevent="logout">
                <button
                  type="submit"
                  class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm font-medium transition"
                >
                  Logout
                </button>
              </form>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden flex items-center">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring transition"
              >
                <svg v-if="!showingNavigationDropdown" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile Menu -->
        <transition name="fade">
          <div v-if="showingNavigationDropdown" class="sm:hidden bg-white border-t border-gray-100">
            <div class="py-3 space-y-1">
              <ResponsiveNavLink :href="route('guests.dashboard')" :active="route().current('guests.dashboard')">
                Dashboard
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('guests.menu')" :active="route().current('guests.menu')">
                Menus
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('guests.orders')" :active="route().current('guests.orders')">
                Orders
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('guests.reservations')" :active="route().current('guests.reservations')">
                Reservations
              </ResponsiveNavLink>
              <button
                @click="logout"
                class="w-full text-left px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition"
              >
                Logout
              </button>
            </div>
          </div>
        </transition>
      </nav>

      <!-- Page Content -->
      <main class="py-6">
        <slot />
      </main>
    </div>

    <!-- Footer -->
    <footer class="relative overflow-hidden bg-neutral-900">
      <svg class="absolute -bottom-20 start-1/2 w-[1900px] transform -translate-x-1/2" width="2745" height="488"
        viewBox="0 0 2745 488" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0.5 330.864C232.505 403.801 853.749 527.683 1482.69 439.719C2111.63 351.756 2585.54 434.588 2743.87 487"
          class="stroke-neutral-700/50" stroke="currentColor" />
        <path
          d="M0.5 308.873C232.505 381.81 853.749 505.692 1482.69 417.728C2111.63 329.765 2585.54 412.597 2743.87 465.009"
          class="stroke-neutral-700/50" stroke="currentColor" />
      </svg>

      <div class="relative z-10">
        <div class="w-full max-w-5xl px-4 xl:px-0 py-10 lg:pt-16 mx-auto">
          <div class="inline-flex items-center">
            <img class="h-20" src="https://cysuites.com/assets/icons/logo.png" alt="Cysuites Logo" />
            <div class="border-s border-neutral-700 ps-5 ms-5">
              <p class="text-sm text-neutral-400">
                Cysuites Apartment & Hotel
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
