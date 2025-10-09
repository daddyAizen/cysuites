<template>
    <AppLayout title="Menu Management">
        <Head title="Menu Management" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight"
                >
                    Menu Management
                </h2>
            </div>
        </template>
        <div class="max-w-6xl mx-auto py-10 px-4">
            <div v-if="orders.length" class="space-y-6">
                <div
                    v-for="order in orders"
                    :key="order.id"
                    class="bg-white shadow-md rounded-2xl p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-lg transition"
                >
                    <div class="flex flex-col gap-2">
                        <div class="text-gray-700 font-semibold">
                            Guest: {{ order.guest.name }} (Room:
                            {{ order.guest.room_number }})
                        </div>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <div
                                v-for="item in order.order_items"
                                :key="item.id"
                                class="flex items-center gap-2 border border-gray-200 rounded-lg p-2 bg-gray-50"
                            >
                                <img
                                    v-if="item.menu.picture"
                                    :src="`/storage/${item.menu.picture}`"
                                    class="h-10 w-10 object-cover rounded"
                                />
                                <div>
                                    <div class="font-medium">
                                        {{ item.menu.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Qty: {{ item.quantity }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-4 md:mt-0">
                        <span
                            :class="
                                order.status === 'approved'
                                    ? 'text-green-600 font-bold'
                                    : 'text-yellow-600 font-semibold'
                            "
                        >
                            {{ order.status.toUpperCase() }}
                        </span>
                        <button
                            v-if="order.status === 'pending'"
                            @click="approveOrder(order.id)"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition"
                        >
                            Approve
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500 text-lg mt-10">
                No orders at the moment.
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const orders = ref(usePage().props.orders || []);

const approveOrder = (orderId) => {
    router.post(
        route("orders.approve", orderId),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                const order = orders.value.find((o) => o.id === orderId);
                if (order) order.status = "approved";
            },
        }
    );
};
</script>
