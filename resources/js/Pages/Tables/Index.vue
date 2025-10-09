<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const props = defineProps({
    tables: Array,
});

const form = useForm({
    name: "",
    seats: 1,
    status: "available",
});

const editForm = useForm({
    id: null,
    name: "",
    seats: 1,
    status: "",
});

const submit = () => {
    form.post(route("tables.store"), {
        onSuccess: () => form.reset(),
    });
};

const startEdit = (table) => {
    editForm.id = table.id;
    editForm.name = table.name;
    editForm.seats = table.seats;
    editForm.status = table.status;
};

const update = () => {
    Inertia.put(route("tables.update", editForm.id), editForm, {
        onSuccess: () => editForm.id = null,
    });
};

const remove = (tableId) => {
    if (confirm("Are you sure you want to delete this table?")) {
        Inertia.delete(route("tables.destroy", tableId));
    }
};
</script>

<template>
  <AppLayout title="Table Management">
    <Head title="Table Management" />

    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
          Table Management
        </h2>

        <!-- Add Table Dialog -->
        <Dialog>
          <DialogTrigger as-child>
            <Button size="lg">+ Add Table</Button>
          </DialogTrigger>
          <DialogContent class="sm:max-w-md p-6 rounded-2xl">
            <DialogHeader>
              <DialogTitle class="text-lg font-semibold text-gray-900 dark:text-white">
                Add New Table
              </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4 mt-4">
              <div class="space-y-1.5">
                <Label>Name</Label>
                <Input v-model="form.name" placeholder="e.g. Table 1" />
                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
              </div>

              <div class="space-y-1.5">
                <Label>Seats</Label>
                <Input type="number" v-model="form.seats" min="1" />
                <p v-if="form.errors.seats" class="text-sm text-red-500">{{ form.errors.seats }}</p>
              </div>

              <Button type="submit" :disabled="form.processing" class="w-full">
                {{ form.processing ? "Adding..." : "Add Table" }}
              </Button>
            </form>
          </DialogContent>
        </Dialog>
      </div>
    </template>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div v-if="tables.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <Card v-for="table in tables" :key="table.id" class="border rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col">
          <CardHeader class="flex justify-between items-center p-4 bg-gray-50 dark:bg-neutral-900">
            <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white truncate">
              {{ table.name }}
            </CardTitle>
            <Badge class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
              {{ table.seats }} Seats
            </Badge>
          </CardHeader>

          <CardContent class="p-4 flex-1 flex flex-col justify-between">
      

            <div class="flex flex-col sm:flex-row gap-2 mt-4">
              <Button size="sm" variant="secondary" class="flex-1" @click="startEdit(table)">
                Edit
              </Button>
              <Button size="sm" variant="destructive" class="flex-1" @click="remove(table.id)">
                Delete
              </Button>
            </div>
          </CardContent>
        </Card>
      </div>

      <div v-else class="text-center py-20 text-gray-500 dark:text-gray-400 space-y-2">
        <p class="text-xl font-medium">No tables yet</p>
        <p>Add your first table to get started!</p>
      </div>
    </div>


    <!-- Edit Table Dialog -->
    <Dialog :open="!!editForm.id" @update:open="(val) => !val && (editForm.id = null)">
      <DialogContent class="sm:max-w-md p-6 rounded-2xl">
        <DialogHeader>
          <DialogTitle class="text-lg font-semibold text-gray-900 dark:text-white">
            Edit Table
          </DialogTitle>
        </DialogHeader>

        <form @submit.prevent="update" class="space-y-4 mt-4">
          <div class="space-y-1.5">
            <Label>Name</Label>
            <Input v-model="editForm.name" placeholder="e.g. Table 1" />
            <p v-if="editForm.errors.name" class="text-sm text-red-500">{{ editForm.errors.name }}</p>
          </div>

          <div class="space-y-1.5">
            <Label>Seats</Label>
            <Input type="number" v-model="editForm.seats" min="1" />
            <p v-if="editForm.errors.seats" class="text-sm text-red-500">{{ editForm.errors.seats }}</p>
          </div>

          <div class="flex space-x-2 pt-4">
            <Button type="submit" :disabled="editForm.processing" class="flex-1">
              {{ editForm.processing ? "Updating..." : "Update Table" }}
            </Button>
            <Button type="button" variant="destructive" class="flex-1" @click="editForm.id = null">
              Cancel
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
