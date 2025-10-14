<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLink from '@/Components/NavLink.vue';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 -mt-10 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-5 lg:grid-cols-3"
                >
                    <div class="flex lg:justify-center lg:col-start-2">
                        <img
                            src="https://cysuites.com/assets/icons/cysuites_logo.png"
                            alt=""
                        />
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('guests.login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-4 border p-4">
                    <!-- Hero -->
                    <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Grid -->
                        <div
                            class="grid md:grid-cols-2 gap-4 md:gap-8 xl:gap-20 md:items-center"
                        >
                            <div>
                                <h1
                                    class="block text-3xl font-bold text-gray-800 sm:text-4xl lg:text-6xl lg:leading-tight dark:text-white"
                                >
                                    Come Dine at
                                    <span class="text-blue-600">Cysuites</span>
                                </h1>
                                <p
                                    class="mt-3 text-lg text-gray-800 dark:text-neutral-400"
                                >
                                    Cysuites offers both local and continental cuisines which have a charming cunningness a certain delicious deception that manipulates the mouth almost the same way a magician does
                                </p>

                                <!-- Buttons -->
                                <div
                                    class="mt-7 grid gap-3 w-full sm:inline-flex"
                                >
                                    <a
                                        class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                        href="#"
                                    >
                                        <NavLink class="text-white" :href="route('guests.login')" :active="route().current('guests.login')">
                                Book a reservation
                                </NavLink>
                                        <svg
                                            class="shrink-0 size-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </a>
                                    <a
                                        class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        href="#"
                                    >
                                        Accomodation
                                    </a>
                                </div>
                                <!-- End Buttons -->


                            </div>
                            <!-- End Col -->

                            <div class="relative ms-4">
                                <img
                                    class="min-w-full min-h-[60vh] rounded-md"
                                    src="https://cysuites.com/assets/images/hive/DSC_9605.jpg"
                                    alt="Hero Image"
                                />
                                <div
                                    class="absolute inset-0 -z-1 bg-linear-to-tr from-gray-200 via-white/0 to-white/0 size-full rounded-md mt-4 -mb-4 me-4 -ms-4 lg:mt-6 lg:-mb-6 lg:me-6 lg:-ms-6 dark:from-neutral-800 dark:via-neutral-900/0 dark:to-neutral-900/0"
                                ></div>

                                <!-- SVG-->
                                <div class="absolute bottom-0 start-0">
                                    <svg
                                        class="w-2/3 ms-auto h-auto text-white dark:text-neutral-900"
                                        width="630"
                                        height="451"
                                        viewBox="0 0 630 451"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <rect
                                            x="531"
                                            y="352"
                                            width="99"
                                            height="99"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="140"
                                            y="352"
                                            width="106"
                                            height="99"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="482"
                                            y="402"
                                            width="64"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="433"
                                            y="402"
                                            width="63"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="384"
                                            y="352"
                                            width="49"
                                            height="50"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="531"
                                            y="328"
                                            width="50"
                                            height="50"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="99"
                                            y="303"
                                            width="49"
                                            height="58"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="99"
                                            y="352"
                                            width="49"
                                            height="50"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="99"
                                            y="392"
                                            width="49"
                                            height="59"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="44"
                                            y="402"
                                            width="66"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="234"
                                            y="402"
                                            width="62"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="334"
                                            y="303"
                                            width="50"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="581"
                                            width="49"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="581"
                                            width="49"
                                            height="64"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="482"
                                            y="123"
                                            width="49"
                                            height="49"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="507"
                                            y="124"
                                            width="49"
                                            height="24"
                                            fill="currentColor"
                                        />
                                        <rect
                                            x="531"
                                            y="49"
                                            width="99"
                                            height="99"
                                            fill="currentColor"
                                        />
                                    </svg>
                                </div>
                                <!-- End SVG-->
                            </div>
                            <!-- End Col -->
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- End Hero -->
                </main>
            </div>
        </div>
    </div>
    <!-- FAQ -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
  <!-- Title -->
  <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
    <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-gray-800 dark:text-neutral-200">
      Frequently Asked Questions
    </h2>
  </div>
  <!-- End Title -->

  <div class="max-w-5xl mx-auto">
    <!-- Grid -->
    <div class="grid sm:grid-cols-2 gap-6 md:gap-12">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
          Do you offer free gym service to your guests
        </h3>
        <p class="mt-2 text-gray-600 dark:text-neutral-400">
          Yes, we offer complimentary gym services to all our guests.
        </p>
      </div>
      <!-- End Col -->

      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
          Can we order takeout?
        </h3>
        <p class="mt-2 text-gray-600 dark:text-neutral-400">
          Yes, we offer takeout services for all our walk-in and in-house guests as well.
        </p>
      </div>
      <!-- End Col -->

      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
          What days are your offers and promotions?
        </h3>
        <p class="mt-2 text-gray-600 dark:text-neutral-400">
         Our offers and promotions are available often times throughout the week, with special deals on weekends.
        </p>
      </div>
      <!-- End Col -->

      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
          How much is the pricing
        </h3>
        <p class="mt-2 text-gray-600 dark:text-neutral-400">
          kindly contact the admin at admin@cysuites.com
        </p>
      </div>
      <!-- End Col -->
    </div>
    <!-- End Grid -->
  </div>
</div>
<!-- End FAQ -->
    <footer class="relative overflow-hidden bg-neutral-900">

        <svg
            class="absolute -bottom-20 start-1/2 w-[1900px] transform -translate-x-1/2"
            width="2745"
            height="488"
            viewBox="0 0 2745 488"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M0.5 330.864C232.505 403.801 853.749 527.683 1482.69 439.719C2111.63 351.756 2585.54 434.588 2743.87 487"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 308.873C232.505 381.81 853.749 505.692 1482.69 417.728C2111.63 329.765 2585.54 412.597 2743.87 465.009"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 286.882C232.505 359.819 853.749 483.701 1482.69 395.738C2111.63 307.774 2585.54 390.606 2743.87 443.018"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 264.891C232.505 337.828 853.749 461.71 1482.69 373.747C2111.63 285.783 2585.54 368.615 2743.87 421.027"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 242.9C232.505 315.837 853.749 439.719 1482.69 351.756C2111.63 263.792 2585.54 346.624 2743.87 399.036"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 220.909C232.505 293.846 853.749 417.728 1482.69 329.765C2111.63 241.801 2585.54 324.633 2743.87 377.045"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 198.918C232.505 271.855 853.749 395.737 1482.69 307.774C2111.63 219.81 2585.54 302.642 2743.87 355.054"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 176.927C232.505 249.864 853.749 373.746 1482.69 285.783C2111.63 197.819 2585.54 280.651 2743.87 333.063"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 154.937C232.505 227.873 853.749 351.756 1482.69 263.792C2111.63 175.828 2585.54 258.661 2743.87 311.072"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 132.946C232.505 205.882 853.749 329.765 1482.69 241.801C2111.63 153.837 2585.54 236.67 2743.87 289.082"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 110.955C232.505 183.891 853.749 307.774 1482.69 219.81C2111.63 131.846 2585.54 214.679 2743.87 267.091"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 88.9639C232.505 161.901 853.749 285.783 1482.69 197.819C2111.63 109.855 2585.54 192.688 2743.87 245.1"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 66.9729C232.505 139.91 853.749 263.792 1482.69 175.828C2111.63 87.8643 2585.54 170.697 2743.87 223.109"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 44.9819C232.505 117.919 853.749 241.801 1482.69 153.837C2111.63 65.8733 2585.54 148.706 2743.87 201.118"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 22.991C232.505 95.9276 853.749 219.81 1482.69 131.846C2111.63 43.8824 2585.54 126.715 2743.87 179.127"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
            <path
                d="M0.5 1C232.505 73.9367 853.749 197.819 1482.69 109.855C2111.63 21.8914 2585.54 104.724 2743.87 157.136"
                class="stroke-neutral-700/50"
                stroke="currentColor"
            />
        </svg>

        <div class="relative z-10">
            <div class="w-full max-w-5xl px-4 xl:px-0 py-10 lg:pt-16 mx-auto">
                <div class="inline-flex items-center">
                    <!-- Logo -->
                    <img
                        class="h-20"
                        src="https://cysuites.com/assets/icons/logo.png"
                        alt=""
                    />
                    <!-- End Logo -->

                    <div class="border-s border-neutral-700 ps-5 ms-5">
                        <p class="text-sm text-neutral-400">
                            Cysuites Apartment & Hotel
                        </p>
                        <NavLink :href="route('login')" class="mt-4 text-sm text-neutral-400">
                            Staff
                        </NavLink>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
