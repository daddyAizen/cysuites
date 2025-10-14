<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/Components/ui/dialog";
import { Label } from "@/Components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/Components/ui/select";
import { Input } from "@/Components/ui/input";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  guests: Array,
  rooms: Array,
});

const form = useForm({
  name: "",
  email: "",
  phone: "",
  room_id: "",
});

const submit = () => {
  form.post(route("guests.store"), {
    onSuccess: () => form.reset(),
  });
};

const checkoutGuest = (guestId) => {
  if (confirm("Are you sure you want to check out this guest?")) {
    Inertia.post(route("guests.checkout", guestId), {}, {
      preserveScroll: true,
    });
  }
};

</script>

<template>
  <AppLayout title="Guest Management">
    <Head title="Guest Management" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        Guest Management
      </h2>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-neutral-900 shadow-sm sm:rounded-lg p-6 space-y-6 border border-gray-100 dark:border-neutral-800">

          <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Guests</h3>

            <Dialog>
              <DialogTrigger as-child>
                <Button class="bg-indigo-600 text-white hover:bg-indigo-700">Add Guest</Button>
              </DialogTrigger>

              <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                  <DialogTitle class="text-lg font-semibold">Add New Guest</DialogTitle>
                </DialogHeader>

                <form @submit.prevent="submit" class="space-y-4 mt-4">

                  <div class="space-y-2">
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="form.name" placeholder="Guest name" />
                    <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
                  </div>

                  <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input id="email" v-model="form.email" placeholder="guest@example.com" />
                    <div v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</div>
                  </div>

                  <div class="space-y-2">
                    <Label for="phone">Phone</Label>
                    <Input id="phone" v-model="form.phone" placeholder="0712345678" />
                    <div v-if="form.errors.phone" class="text-sm text-red-500">{{ form.errors.phone }}</div>
                  </div>

                  <div class="space-y-2">
                    <Label for="room_id">Select Room</Label>
                    <Select v-model="form.room_id" filterable>
                      <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select Room" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem
                          v-for="room in props.rooms"
                          :key="room.id"
                          :value="room.id"
                        >
                          {{ room.room_name }}

                        </SelectItem>
                      </SelectContent>
                    </Select>
                    <div v-if="form.errors.room_id" class="text-sm text-red-500">{{ form.errors.room_id }}</div>
                  </div>

                  <div class="flex justify-end pt-2">
                    <Button type="submit" :disabled="form.processing" class="w-full sm:w-auto">
                      {{ form.processing ? "Adding..." : "Add Guest" }}
                    </Button>
                  </div>

                </form>
              </DialogContent>
            </Dialog>
          </div>

          <div class="max-w-[77rem] mx-auto my-8 aspect-w-16 aspect-h-7">
                <img
                    class="w-full max-h-[30vh] object-cover rounded-xl"
                    src="https://images.unsplash.com/photo-1632067694887-097be1c5c5d4?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Z3Vlc3R8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=900"
                    alt="Features Image"
                />
            </div>


          <!-- Table -->
          <div class="rounded-md border border-gray-200 dark:border-neutral-800 overflow-hidden">
            <Table>
              <TableHeader>
                <TableRow class="bg-gray-50 dark:bg-neutral-800">
                  <TableHead>#</TableHead>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Phone</TableHead>
                  <TableHead>Room</TableHead>
                  <TableHead>Room Code</TableHead>
                  <TableHead>Action</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="(guest, index) in guests" :key="guest.id" class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                  <TableCell>{{ index + 1 }}</TableCell>
                  <TableCell>{{ guest.name }}</TableCell>
                  <TableCell>{{ guest.email }}</TableCell>
                  <TableCell>{{ guest.phone }}</TableCell>
                  <TableCell>{{ guest.room?.room_name }}</TableCell>
                  <TableCell class="font-mono text-sm">{{ guest.room_code }}</TableCell>
                  <TableCell>
                    <Button
                      class="bg-blue-500 text-white w-full py-2 rounded hover:bg-blue-600 transition"
                      @click.prevent="checkoutGuest(guest.id)"
                    >
                      Checkout
                    </Button>
                  </TableCell>
                </TableRow>

                <TableRow v-if="guests.length === 0">
                  <TableCell colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                    No guests found.
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>
