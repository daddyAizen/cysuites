<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import { Chart, registerables } from "chart.js";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";

Chart.register(...registerables);

// Props from backend
const props = defineProps({
  pendingOrders: Number,
  totalOrders: Number,
  totalGuests: Number,
  totalMenuItems: Number,
  ordersOverTime: Array,
  topSellingItems: Array,
});

const pendingOrders = ref(props.pendingOrders || 0);
const totalOrders = ref(props.totalOrders || 0);
const totalGuests = ref(props.totalGuests || 0);
const totalMenuItems = ref(props.totalMenuItems || 0);

const ordersOverTimeData = ref(props.ordersOverTime || []);
const topSellingData = ref(props.topSellingItems || []);

const dateFilter = ref("7days"); // default filter

// Charts
let ordersChart = null;
let topItemsChart = null;

function formatOrdersOverTime() {
  return {
    labels: ordersOverTimeData.value.map(o => o.date),
    datasets: [{
      label: "Orders",
      data: ordersOverTimeData.value.map(o => o.count),
      backgroundColor: "rgba(59, 130, 246, 0.2)",
      borderColor: "rgba(59, 130, 246, 1)",
      borderWidth: 2,
      fill: true,
      tension: 0.4,
    }],
  };
}

function formatTopSelling() {
  return {
    labels: topSellingData.value.map(i => i.name),
    datasets: [{
      label: "Quantity Sold",
      data: topSellingData.value.map(i => i.quantity),
      backgroundColor: "rgba(16, 185, 129, 0.7)",
    }],
  };
}

function updateCharts() {
  if (ordersChart) ordersChart.destroy();
  if (topItemsChart) topItemsChart.destroy();

  const ordersCtx = document.getElementById("ordersChart").getContext("2d");
  ordersChart = new Chart(ordersCtx, {
    type: "line",
    data: formatOrdersOverTime(),
    options: { responsive: true, plugins: { legend: { display: true, position: "top" } } },
  });

  const topItemsCtx = document.getElementById("topItemsChart").getContext("2d");
  topItemsChart = new Chart(topItemsCtx, {
    type: "bar",
    data: formatTopSelling(),
    options: { responsive: true, plugins: { legend: { display: true, position: "top" } } },
  });
}

// Filter orders by date
function filterOrders(filter) {
  dateFilter.value = filter;
  router.get(route("dashboard"), { filter }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (pageData) => {
      pendingOrders.value = pageData.props.pendingOrders;
      totalOrders.value = pageData.props.totalOrders;
      totalGuests.value = pageData.props.totalGuests;
      totalMenuItems.value = pageData.props.totalMenuItems;
      ordersOverTimeData.value = pageData.props.ordersOverTime;
      topSellingData.value = pageData.props.topSellingItems;
      updateCharts();
    },
  });
}

onMounted(() => updateCharts());
watch([ordersOverTimeData, topSellingData], updateCharts);

</script>

<template>
  <AppLayout title="Admin Dashboard">
    <Head title="Admin Dashboard" />

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <Card class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 shadow-lg rounded-xl">
          <div class="text-sm font-medium text-gray-500">Pending Orders</div>
          <div class="text-3xl font-bold text-blue-700">{{ pendingOrders }}</div>
        </Card>
        <Card class="p-6 bg-gradient-to-r from-green-50 to-green-100 shadow-lg rounded-xl">
          <div class="text-sm font-medium text-gray-500">Total Orders</div>
          <div class="text-3xl font-bold text-green-700">{{ totalOrders }}</div>
        </Card>
        <Card class="p-6 bg-gradient-to-r from-purple-50 to-purple-100 shadow-lg rounded-xl">
          <div class="text-sm font-medium text-gray-500">Total Guests</div>
          <div class="text-3xl font-bold text-purple-700">{{ totalGuests }}</div>
        </Card>
        <Card class="p-6 bg-gradient-to-r from-yellow-50 to-yellow-100 shadow-lg rounded-xl">
          <div class="text-sm font-medium text-gray-500">Menu Items</div>
          <div class="text-3xl font-bold text-yellow-700">{{ totalMenuItems }}</div>
        </Card>
      </div>

      <!-- Date Filter Buttons -->
      <div class="flex gap-3 my-4">
        <Button variant="outline" :class="dateFilter==='7days' ? 'bg-blue-600 text-white' : ''" @click="filterOrders('7days')">This Week</Button>
        <Button variant="outline" :class="dateFilter==='30days' ? 'bg-blue-600 text-white' : ''" @click="filterOrders('30days')">This Month</Button>
        <Button variant="outline" :class="dateFilter==='all' ? 'bg-blue-600 text-white' : ''" @click="filterOrders('all')">All Time</Button>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card class="p-6 shadow-lg rounded-xl">
          <canvas id="ordersChart"></canvas>
        </Card>
        <Card class="p-6 shadow-lg rounded-xl">
          <canvas id="topItemsChart"></canvas>
        </Card>
      </div>

    </div>
  </AppLayout>
</template>
