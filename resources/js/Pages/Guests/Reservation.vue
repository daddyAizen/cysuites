<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {
  Card,
  CardHeader,
  CardTitle,
  CardContent,
  CardFooter,
} from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from "@/Components/ui/select";
import { Badge } from "@/Components/ui/badge";

const props = defineProps({
  guest: Object,
  tables: Array,
  reservations: Array,
});

const form = useForm({
  guest_id: props.guest.id,
  table_id: "",
  reservation_date: "",
  reservation_time: "",
});

const submitReservation = () => {
  form.post(route("reservations.store"), {
    onSuccess: () => {
      form.reset("table_id", "reservation_date", "reservation_time");
    },
  });
};

const cancelReservation = (id) => {
  if (confirm("Cancel this reservation?")) {
    form.delete(route("reservations.destroy", id), {
      preserveScroll: true,
    });
  }
};
</script>

<template>
  <GuestLayout title="My Reservations">
    <Head title="My Reservations" />

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-16 px-4">
      <div class="max-w-6xl mx-auto space-y-16">
        <!-- Reservation Form -->
        <Card class="max-w-2xl mx-auto shadow-xl border-gray-200">
          <CardHeader class="text-center space-y-2">
            <CardTitle class="text-3xl font-extrabold text-gray-800">
              Book a Table 🍽️
            </CardTitle>
            <p class="text-gray-500 text-sm">
              Hello, <span class="font-semibold">{{ guest.name }}</span> — plan your perfect meal.
            </p>
          </CardHeader>

          <CardContent class="space-y-6">
            <!-- Table Select -->
            <div class="space-y-2">
              <Label class="text-gray-700 font-medium">Select Table</Label>
              <Select v-model="form.table_id">
                <SelectTrigger class="w-full">
                  <SelectValue placeholder="Choose a table" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem
                    v-for="table in tables"
                    :key="table.id"
                    :value="table.id"
                    :disabled="table.status === 'unavailable'"
                  >
                    <div class="flex items-center justify-between">
                      <span>{{ table.name }}</span>
                      <Badge
                        v-if="table.status === 'unavailable'"
                        variant="destructive"
                        class="ml-2 text-xs"
                      >
                        Unavailable
                      </Badge>
                      <Badge
                        v-else
                        variant="outline"
                        class="ml-2 text-xs text-green-700 border-green-300"
                      >
                        Available
                      </Badge>
                    </div>
                  </SelectItem>
                </SelectContent>
              </Select>
              <p v-if="form.errors.table_id" class="text-red-500 text-sm">
                {{ form.errors.table_id }}
              </p>
            </div>

            <!-- Date Input -->
            <div class="space-y-2">
              <Label class="text-gray-700 font-medium">Reservation Date</Label>
              <Input type="date" v-model="form.reservation_date" class="w-full" />
              <p v-if="form.errors.reservation_date" class="text-red-500 text-sm">
                {{ form.errors.reservation_date }}
              </p>
            </div>

            <!-- Time Input -->
            <div class="space-y-2">
              <Label class="text-gray-700 font-medium">Reservation Time</Label>
              <Input type="time" v-model="form.reservation_time" class="w-full" />
              <p v-if="form.errors.reservation_time" class="text-red-500 text-sm">
                {{ form.errors.reservation_time }}
              </p>
            </div>
          </CardContent>

          <CardFooter>
            <Button
              class="w-full text-lg font-semibold"
              :disabled="form.processing"
              @click="submitReservation"
            >
              {{ form.processing ? "Submitting..." : "Reserve Table" }}
            </Button>
          </CardFooter>
        </Card>

        <!-- Reservations List -->
        <div>
          <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Your Reservations
          </h2>

          <div
            v-if="reservations.length"
            class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <Card
              v-for="r in reservations"
              :key="r.id"
              class="shadow-md hover:shadow-lg transition border border-gray-200"
            >
              <CardHeader>
                <CardTitle class="text-lg font-semibold text-gray-800">
                  {{ r.table.name }}
                </CardTitle>
                <Badge
                  :variant="r.status === 'pending'
                    ? 'outline'
                    : r.status === 'confirmed'
                    ? 'default'
                    : r.status === 'cancelled'
                    ? 'destructive'
                    : 'secondary'"
                  class="capitalize"
                >
                  {{ r.status }}
                </Badge>
              </CardHeader>
              <CardContent class="text-sm text-gray-600 space-y-1">
                <p>📅 {{ r.reservation_date }}</p>
                <p>🕒 {{ r.reservation_time }}</p>
              </CardContent>
              <CardFooter>
                <Button
                  v-if="r.status !== 'cancelled' && r.status !== 'completed'"
                  variant="destructive"
                  size="sm"
                  class="w-full"
                  @click="cancelReservation(r.id)"
                >
                  Cancel Reservation
                </Button>
              </CardFooter>
            </Card>
          </div>

          <div v-else class="text-gray-500 text-center py-12 italic">
            You don’t have any reservations yet. Start by booking a table above.
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
