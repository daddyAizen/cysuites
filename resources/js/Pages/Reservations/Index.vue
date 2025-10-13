<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { ref } from "vue";

const props = defineProps({
  reservations: Array,
  tables: Array,
  guests: Array,
});

const form = useForm({
  guest_id: "",
  table_id: "",
  reservation_date: "",
  reservation_time: "",
});

const editForm = useForm({
  id: null,
  guest_id: "",
  table_id: "",
  reservation_date: "",
  reservation_time: "",
  status: "",
});

const showEditModal = ref(false);

const submit = () => {
  form.post(route("reservations.store"), {
    onSuccess: () => form.reset(),
  });
};

const startEdit = (res) => {
  editForm.id = res.id;
  editForm.guest_id = res.guest_id;
  editForm.table_id = res.table_id;
  editForm.reservation_date = res.reservation_date;
  editForm.reservation_time = res.reservation_time;
  editForm.status = res.status;
  showEditModal.value = true;
};

const update = () => {
  editForm.post(route("reservations.update", editForm.id), {
    onSuccess: () => {
      editForm.reset();
      showEditModal.value = false;
    },
  });
};

const deleteReservation = (id) => {
  if (confirm("Are you sure you want to delete this reservation?")) {
    form.post(route("reservations.cancel", id));
  }
};
</script>

<template>
  <AppLayout title="Reservations">
    <Head title="Reservations" />

    <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-white leading-tight"
            >
                Reservations
            </h2>
        </template>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-6">
        <Dialog>
          <DialogTrigger as-child>
            <Button class=" hover:bg-indigo-700">+ Add Reservation</Button>
          </DialogTrigger>
          <DialogContent class="max-w-md">
            <DialogHeader>
              <DialogTitle>Add New Reservation</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submit" class="space-y-4 mt-4">
              <div>
                <Label>Guest</Label>
                <select v-model="form.guest_id" class="input w-full rounded-md border-gray-300">
                  <option disabled value="">Select Guest</option>
                  <option v-for="guest in props.guests" :key="guest.id" :value="guest.id">
                    {{ guest.name }}
                  </option>
                </select>
              </div>
              <div>
                <Label>Table</Label>
                <select v-model="form.table_id" class="input w-full rounded-md border-gray-300">
                  <option disabled value="">Select Table</option>
                  <option
                    v-for="table in props.tables"
                    :key="table.id"
                    :value="table.id"
                    :disabled="table.status === 'unavailable'"
                  >
                    {{ table.name }} ({{ table.status }})
                  </option>
                </select>
              </div>
              <div class="flex gap-3">
                <div class="flex-1">
                  <Label>Date</Label>
                  <Input type="date" v-model="form.reservation_date" />
                </div>
                <div class="flex-1">
                  <Label>Time</Label>
                  <Input type="time" v-model="form.reservation_time" />
                </div>
              </div>
              <Button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700">
                {{ form.processing ? "Adding..." : "Add Reservation" }}
              </Button>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto bg-white dark:bg-neutral-800 shadow rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
          <thead class="bg-gray-100 dark:bg-neutral-900">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Guest</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Table</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Time</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
            <tr
              v-for="res in props.reservations"
              :key="res.id"
              class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition"
            >
              <td class="px-6 py-4">{{ res.guest.name }}</td>
              <td class="px-6 py-4">{{ res.table.name }}</td>
              <td class="px-6 py-4">{{ res.reservation_date }}</td>
              <td class="px-6 py-4">{{ res.reservation_time }}</td>
              <td class="px-6 py-4">
                <Badge
                  :class="{
                    'bg-yellow-500': res.status === 'pending',
                    'bg-green-600': res.status === 'confirmed',
                    'bg-red-600': res.status === 'cancelled',
                    'bg-gray-500': res.status === 'completed',
                  }"
                  class="capitalize text-white"
                >
                  {{ res.status }}
                </Badge>
              </td>
              <td class="px-6 py-4 text-right flex justify-end gap-2">
                <Button size="sm" variant="secondary" @click="startEdit(res)">Edit</Button>
                <Button size="sm" variant="destructive" @click="deleteReservation(res.id)">Delete</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Edit Modal -->
    <Dialog v-model:open="showEditModal">
      <DialogContent class="max-w-md">
        <DialogHeader>
          <DialogTitle>Edit Reservation</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="update" class="space-y-4 mt-4">
          <div>
            <Label>Guest</Label>
            <select v-model="editForm.guest_id" class="input w-full rounded-md border-gray-300">
              <option v-for="guest in props.guests" :key="guest.id" :value="guest.id">{{ guest.name }}</option>
            </select>
          </div>
          <div>
            <Label>Table</Label>
            <select v-model="editForm.table_id" class="input w-full rounded-md border-gray-300">
              <option v-for="table in props.tables" :key="table.id" :value="table.id">{{ table.name }}</option>
            </select>
          </div>
          <div class="flex gap-3">
            <div class="flex-1">
              <Label>Date</Label>
              <Input type="date" v-model="editForm.reservation_date" />
            </div>
            <div class="flex-1">
              <Label>Time</Label>
              <Input type="time" v-model="editForm.reservation_time" />
            </div>
          </div>
          <div>
            <Label>Status</Label>
            <select v-model="editForm.status" class="input w-full rounded-md border-gray-300">
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="cancelled">Cancelled</option>
              <option value="completed">Completed</option>
            </select>
          </div>
          <div class="flex gap-3 pt-4">
            <Button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700">
              {{ editForm.processing ? "Saving..." : "Save Changes" }}
            </Button>
            <Button type="button" variant="destructive" class="flex-1" @click="showEditModal = false">Cancel</Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
