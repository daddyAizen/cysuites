<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const props = defineProps({
    reservations: Array,
    tables: Array,
    guests: Array,
});

// New Reservation form
const form = useForm({
    guest_id: "",
    table_id: "",
    reservation_date: "",
    reservation_time: "",
});

// Edit Reservation form
const editForm = useForm({
    id: null,
    guest_id: "",
    table_id: "",
    reservation_date: "",
    reservation_time: "",
    status: "",
});

// Submit new reservation
const submit = () => {
    form.post(route("reservations.store"), { onSuccess: () => form.reset() });
};

// Start editing a reservation
const startEdit = (reservation) => {
    editForm.id = reservation.id;
    editForm.guest_id = reservation.guest_id;
    editForm.table_id = reservation.table_id;
    editForm.reservation_date = reservation.reservation_date;
    editForm.reservation_time = reservation.reservation_time;
    editForm.status = reservation.status;
};

// Update reservation
const update = () => {
    Inertia.post(route("reservations.update", editForm.id), editForm, {
        onSuccess: () => editForm.reset(),
    });
};

// Delete reservation
const deleteReservation = (id) => {
    if (confirm("Are you sure you want to delete this reservation?")) {
        Inertia.delete(route("reservations.destroy", id));
    }
};

// Change status dynamically
const changeStatus = (reservation, status) => {
    Inertia.post(route("reservations.update", reservation.id), {
        ...reservation,
        status: status,
    });
};
</script>

<template>
<AppLayout title="Reservations">
    <Head title="Reservations" />

    <template #header>
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-2xl text-gray-900 dark:text-white">Reservations</h2>
            <Dialog>
                <DialogTrigger as-child>
                    <Button size="lg" class="font-medium">+ Add Reservation</Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-lg p-6 rounded-2xl">
                    <DialogHeader>
                        <DialogTitle>Add New Reservation</DialogTitle>
                    </DialogHeader>
                    <form @submit.prevent="submit" class="space-y-4 mt-4">
                        <div>
                            <Label>Guest</Label>
                            <select v-model="form.guest_id" class="input w-full">
                                <option value="" disabled>Select Guest</option>
                                <option v-for="guest in guests" :key="guest.id" :value="guest.id">{{ guest.name }}</option>
                            </select>
                        </div>
                        <div>
                            <Label>Table</Label>
                            <select v-model="form.table_id" class="input w-full">
                                <option value="" disabled>Select Table</option>
                                <option
                                    v-for="table in tables"
                                    :key="table.id"
                                    :value="table.id"
                                    :disabled="table.status === 'unavailable'"
                                >
                                    {{ table.name }} - {{ table.status === 'available' ? 'Available' : 'Unavailable' }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <Label>Date</Label>
                            <Input type="date" v-model="form.reservation_date" />
                        </div>
                        <div>
                            <Label>Time</Label>
                            <Input type="time" v-model="form.reservation_time" />
                        </div>
                        <Button type="submit" class="w-full font-semibold">
                            {{ form.processing ? "Adding..." : "Add Reservation" }}
                        </Button>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </template>

    <!-- Reservations Table -->
    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div v-if="reservations.length" class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Table</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                    <tr v-for="res in reservations" :key="res.id" class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                        <td class="px-6 py-4 whitespace-nowrap">{{ res.guest.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ res.table.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ res.reservation_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ res.reservation_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <Badge
                                :class="{
                                    'bg-yellow-500': res.status === 'pending',
                                    'bg-green-500': res.status === 'confirmed',
                                    'bg-red-500': res.status === 'cancelled',
                                    'bg-gray-500': res.status === 'completed',
                                }"
                            >
                                {{ res.status }}
                            </Badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2 justify-end">
                            <Button size="sm" variant="secondary" @click="startEdit(res)">Edit</Button>
                            <Button size="sm" variant="destructive" @click="deleteReservation(res.id)">Delete</Button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="text-center py-20 text-gray-500 dark:text-gray-400">
            <p>No reservations yet.</p>
            <p>Add a reservation to get started!</p>
        </div>
    </div>

    <!-- Edit Reservation Dialog -->
    <Dialog :open="!!editForm.id" @update:open="(val) => !val && (editForm.id = null)">
        <DialogContent class="sm:max-w-lg p-6 rounded-2xl">
            <DialogHeader>
                <DialogTitle>Edit Reservation</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="update" class="space-y-4 mt-4">
                <div>
                    <Label>Guest</Label>
                    <select v-model="editForm.guest_id" class="input w-full">
                        <option v-for="guest in guests" :key="guest.id" :value="guest.id">{{ guest.name }}</option>
                    </select>
                </div>
                <div>
                    <Label>Table</Label>
                    <select v-model="editForm.table_id" class="input w-full">
                        <option
                            v-for="table in tables"
                            :key="table.id"
                            :value="table.id"
                            :disabled="table.status === 'unavailable'"
                        >
                            {{ table.name }} - {{ table.status === 'available' ? 'Available' : 'Unavailable' }}
                        </option>
                    </select>
                </div>
                <div>
                    <Label>Date</Label>
                    <Input type="date" v-model="editForm.reservation_date" />
                </div>
                <div>
                    <Label>Time</Label>
                    <Input type="time" v-model="editForm.reservation_time" />
                </div>
                <div>
                    <Label>Status</Label>
                    <select v-model="editForm.status" class="input w-full">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="flex space-x-2 pt-4">
                    <Button type="submit" class="flex-1">{{ editForm.processing ? "Updating..." : "Update" }}</Button>
                    <Button type="button" variant="destructive" class="flex-1" @click="editForm.id = null">Cancel</Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</AppLayout>
</template>
