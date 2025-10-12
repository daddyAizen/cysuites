<script setup>
import { ref, computed, watchEffect } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const page = usePage();

const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value.role?.name || 'No Role');

console.log('User Role Name:', userRole.value);
console.log('User Name:', user.value.name);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
  <div>
    <Head :title="title" />

    <Banner />

    <div class="min-h-[85vh] bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <ApplicationMark class="block h-9 w-auto" />
                </Link>
              </div>

              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff'" :href="route('dashboard')" :active="route().current('dashboard')">
                  Dashboard
                </NavLink>

                <NavLink v-if="userRole === 'Admin'" :href="route('staff.index')" :active="route().current('staff')">
                  Staff
                </NavLink>

                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff'" :href="route('rooms.index')" :active="route().current('rooms')">
                  Rooms
                </NavLink>

                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff' || userRole === 'Kitchen Staff'" :href="route('menu.index')" :active="route().current('menu')">
                  Menu
                </NavLink>

                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff'" :href="route('tables.index')" :active="route().current('tables')">
                  Tables
                </NavLink>

                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff'" :href="route('reservations.index')" :active="route().current('reservations')">
                  Reservations
                </NavLink>

                <NavLink v-if="userRole === 'Admin' || userRole === 'Kitchen Staff'" :href="route('orders.index')" :active="route().current('orders')">
                  Orders
                </NavLink>

                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff' || userRole === 'Kitchen Staff'" :href="route('menu.staff')" :active="route().current('menu.staff')">
                  Staff Menu
                </NavLink>
                <NavLink v-if="userRole === 'Admin' || userRole === 'Staff' || userRole === 'Kitchen Staff'" :href="route('promotions.index')" :active="route().current('promotions.index')">
                  Promotions
                </NavLink>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
              <div class="ms-3 relative">
                <Dropdown align="right" width="48">
                  <template #trigger>
                    <!-- <button v-if="user.value.profile_photo_url" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                      <img class="size-8 rounded-full object-cover" :src="user.value.profile_photo_url" :alt="user.value.name">
                    </button> -->
                    <span class="inline-flex rounded-md">
                      <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                        {{ user.name }}
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Manage Account
                    </div>
                    <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
                    <div class="border-t border-gray-200" />
                    <form @submit.prevent="logout">
                      <DropdownLink as="button">Log Out</DropdownLink>
                    </form>
                  </template>
                </Dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
              <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                  <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
              Dashboard
            </ResponsiveNavLink>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header v-if="$slots.header" class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
