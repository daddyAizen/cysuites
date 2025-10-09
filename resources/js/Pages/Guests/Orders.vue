<template>
    <GuestLayout>
        <div class="max-w-6xl mx-auto py-10 px-4">


            <div v-if="orders.length" class="space-y-6">
                <div
                    v-for="order in orders"
                    :key="order.id"
                    class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition"
                >
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Order #{{ order.id }} - {{ order.status }}
                    </h3>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div
                            v-for="item in order.order_items"
                            :key="item.id"
                            class="flex flex-col items-center bg-gray-50 rounded-xl p-4"
                        >
                            <img
                                v-if="item.menu && item.menu.picture"
                                :src="`/storage/${item.menu.picture}`"
                                alt="Menu Image"
                                class="h-32 w-32 object-cover rounded-xl mb-2"
                            />
                            <h4 class="font-medium text-gray-800 text-center">
                                {{ item.menu?.name || "Menu Deleted" }}
                            </h4>
                            <p class="text-sm text-gray-600 text-center">
                                Qty: {{ item.quantity }}
                            </p>
                            <p class="text-sm font-bold text-gray-900">
                                KES {{ item.menu?.price || 0 }}
                            </p>
                        </div>
                    </div>
                    <div v-if="order.status === 'pending'" class="flex justify-end mt-4">
                        <button
                            @click="cancelOrder(order.id)"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition"
                        >
                            Cancel Order
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500 text-lg mt-10">
                No orders placed yet.
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();
const orders = ref([...page.props.orders])

const cancelOrder = (orderId) => {
  if (!confirm('Are you sure you want to cancel this order?')) return;

  router.post(route('guests.orders.cancel', orderId), {
    onSuccess: () => {
      orders.value = orders.value.filter(order => order.id !== orderId)
      alert('Order deleted successfully!')
    }
  })
}

</script>
