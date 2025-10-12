<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

// ShadCN components
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogClose } from "@/components/ui/dialog";
import { Eye } from "lucide-vue-next";

import { router } from "@inertiajs/vue3";

const props = defineProps({
  orders: Object,
});

const isModalOpen = ref(false);
const activeOrderImages = ref([]);
const activeOrderIndex = ref(0);

function openImages(order) {
  activeOrderImages.value = (order.orderItems || [])
    .map((item) => item.menu?.picture)
    .filter(Boolean);

  if (activeOrderImages.value.length) {
    isModalOpen.value = true;
    activeOrderIndex.value = 0;
  } else {
    alert("No images for this order");
  }
}

function nextImage() {
  if (activeOrderIndex.value < activeOrderImages.value.length - 1) {
    activeOrderIndex.value++;
  } else {
    activeOrderIndex.value = 0;
  }
}

function getStatusColor(status) {
  switch (status) {
    case "pending": return "bg-yellow-100 text-yellow-800";
    case "approved": return "bg-blue-100 text-blue-800";
    case "accepted": return "bg-green-100 text-green-800";
    case "rejected": return "bg-red-100 text-red-800";
    default: return "bg-gray-100 text-gray-800";
  }
}

const updateStatus = (orderId) => {
  router.post(route("orders.updateStatus", orderId), {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      const order = props.orders.data.find((o) => o.id === orderId);
      if (!order) return;
      if (order.status === "pending") order.status = "approved";
      else if (order.status === "approved") order.status = "accepted";
    },
  });
};

const rejectOrder = (orderId) => {
  router.delete(route("orders.destroy", orderId), {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      const order = props.orders.data.find((o) => o.id === orderId);
      if (order) order.status = "rejected";
    },
  });
};
</script>

<template>
  <AppLayout title="Orders">
    <Head title="Orders" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Orders</h2>
    </template>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <Card class="p-6 space-y-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Orders</h3>

        <div class="rounded-md border border-gray-200 dark:border-neutral-800 overflow-hidden">
          <Table>
            <TableHeader>
              <TableRow class="bg-gray-50 dark:bg-neutral-800">
                <TableHead class="w-12 text-left">#</TableHead>
                <TableHead>Guest/User</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Total</TableHead>
                <TableHead>Items</TableHead>
                <TableHead>Action</TableHead>
              </TableRow>
            </TableHeader>

            <TableBody>
              <TableRow
                v-for="(order, index) in props.orders.data"
                :key="order.id"
                class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors"
              >
                <TableCell>{{ index + 1 }}</TableCell>
                <TableCell>{{ order.guest ? order.guest.name : order.user ? order.user.name : 'N/A' }}</TableCell>
                <TableCell>
                  <span :class="`px-2 py-1 rounded-full text-sm font-medium ${getStatusColor(order.status)}`">
                    {{ order.status }}
                  </span>
                </TableCell>
                <TableCell>${{ order.total.toFixed(2) }}</TableCell>
                <TableCell>
                  <Button size="sm" variant="outline" @click="openImages(order)">
                    <Eye class="w-5 h-5" />
                  </Button>
                </TableCell>
                <TableCell class="flex gap-2">
                  <Button
                    size="sm"
                    variant="outline"
                    :disabled="order.status === 'accepted'"
                    @click="updateStatus(order.id)"
                  >
                    {{ order.status === 'pending' ? 'Approve' : 'Accept' }}
                  </Button>
                  <Button
                    size="sm"
                    variant="destructive"
                    v-if="order.status !== 'accepted' && order.status !== 'rejected'"
                    @click="rejectOrder(order.id)"
                  >
                    Reject
                  </Button>
                </TableCell>
              </TableRow>

              <TableRow v-if="props.orders.data.length === 0">
                <TableCell colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">
                  No orders found.
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center gap-3">
          <Button
            variant="outline"
            :disabled="!props.orders.prev_page_url"
            @click="$inertia.get(props.orders.prev_page_url)"
          >
            Previous
          </Button>
          <Button
            variant="outline"
            :disabled="!props.orders.next_page_url"
            @click="$inertia.get(props.orders.next_page_url)"
          >
            Next
          </Button>
        </div>
      </Card>
    </div>

    <!-- Modal for images -->
    <Dialog v-model="isModalOpen">
      <DialogContent class="max-w-lg">
        <DialogHeader>
          <DialogTitle>Order Items</DialogTitle>
          <DialogClose />
        </DialogHeader>
        <div class="relative">
          <img
            v-if="activeOrderImages.length"
            :src="`/storage/${activeOrderImages[activeOrderIndex]}`"
            class="w-full h-64 object-cover rounded-md"
          />
          <button
            v-if="activeOrderImages.length > 1"
            @click="nextImage"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-3 py-1 rounded-full"
          >
            Next
          </button>
        </div>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
