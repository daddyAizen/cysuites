<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <GuestLayout title="Guest Dashboard">
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-white leading-tight"
            >
                Guest Dashboard
            </h2>
        </template>

        <!-- Features -->
        <div class="max-w-[85rem] px-4 py-4 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="aspect-w-16 aspect-h-7">
                <img
                    class="w-full max-h-[50vh] object-cover rounded-xl"
                    src="https://cysuites.com/assets/images/landing_page1.jpeg"
                    alt="Features Image"
                />
            </div>

            <!-- Grid -->
            <div class="mt-5 lg:mt-16 grid lg:grid-cols-3 gap-8 lg:gap-12">
                <div class="lg:col-span-1">
                    <h2
                        class="font-bold text-2xl md:text-3xl text-gray-800 dark:text-neutral-200"
                    >
                        More than just hotels Culture
                    </h2>
                    <p class="mt-2 md:mt-4 text-gray-500 dark:text-neutral-500">
                        Located in the heart of Westlands, this luxurious
                        serviced apartment hotel boasts of modern facilities,
                        unique services and a serene location which is the
                        perfect home away from home for both business and
                        leisure travels
                    </p>
                </div>
                <!-- End Col -->

                <div class="lg:col-span-2">
                    <div class="grid sm:grid-cols-2 gap-8 md:gap-12">
                        <!-- Card -->
                        <NavLink :href="route('guests.menu')"
                            class="block border border-gray-200 rounded-lg hover:shadow-2xs focus:outline-hidden dark:border-neutral-700"
                        >
                            <div
                                class="relative flex items-center overflow-hidden"
                            >
                                <img
                                    class="w-32 sm:w-48 h-full absolute inset-0 object-cover h-full rounded-s-lg"
                                    src="https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fGZvb2R8ZW58MHx8MHx8fDA%3D"
                                    alt="Blog Image"
                                />
                                 <div class="grow p-4 ms-32 sm:ms-48">
                                    <div
                                        class="min-h-24 flex flex-col justify-center"
                                    >
                                        <h3
                                            class="font-semibold text-sm text-gray-800 dark:text-neutral-300"
                                        >
                                            Order now
                                        </h3>
                                        <p
                                            class="mt-1 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            Order food straight to your room
                                            from our diverse menu, featuring
                                            local and international cuisines.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Navlink>
                        <!-- End Card -->

                        <!-- Card -->
                        <NavLink :href="route('guests.reservations')"
                            class="block border border-gray-200 rounded-lg hover:shadow-2xs focus:outline-hidden dark:border-neutral-700"
                        >
                            <div
                                class="relative flex items-center overflow-hidden"
                            >
                                <img
                                    class="w-32 sm:w-48 h-full absolute inset-0 object-cover rounded-s-lg"
                                    src="https://images.unsplash.com/photo-1707126186357-1690bc6866e1?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8bWFraW4lMjByZXNlcnZhdGlvbnxlbnwwfHwwfHx8MA%3D%3D"
                                    alt="Blog Image"
                                />

                                <div class="grow p-4 ms-32 sm:ms-48">
                                    <div
                                        class="min-h-24 flex flex-col justify-center"
                                    >
                                        <h3
                                            class="font-semibold text-sm text-gray-800 dark:text-neutral-300"
                                        >
                                            Make Reservation
                                        </h3>
                                        <p
                                            class="mt-1 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            Book a table online and enjoy a
                                            seamless dining experience at your
                                            favorite restaurant.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Navlink>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
