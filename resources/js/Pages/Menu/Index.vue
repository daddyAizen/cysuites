<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";

const props = defineProps({
    menus: Array,
});

const form = useForm({
    name: "",
    description: "",
    price: "",
    picture: null,
    is_out_of_stock: false,
});

const editForm = useForm({
    id: null,
    name: "",
    description: "",
    price: "",
    picture: null,
    is_out_of_stock: false,
});

const submit = () => {
    const data = new FormData();
    data.append("name", form.name);
    data.append("description", form.description);
    data.append("price", form.price);
    data.append("is_out_of_stock", form.is_out_of_stock ? 1 : 0);
    if (form.picture) data.append("picture", form.picture);

    form.post(route("menu.store"), {
        onSuccess: () => form.reset(),
    });
};

const startEdit = (menu) => {
    editForm.id = menu.id;
    editForm.name = menu.name;
    editForm.description = menu.description;
    editForm.price = menu.price;
    editForm.is_out_of_stock = menu.is_out_of_stock;
    editForm.picture = null;
};

const update = () => {
    const data = new FormData();
    data.append("name", editForm.name);
    data.append("description", editForm.description);
    data.append("price", editForm.price);
    data.append("is_out_of_stock", editForm.is_out_of_stock ? 1 : 0);
    if (editForm.picture) data.append("picture", editForm.picture);

    Inertia.post(route("menu.update", editForm.id), data, {
        onSuccess: () => {
            editForm.reset();
            editForm.id = null;
        },
    });
};

const toggleStock = (menuId) => {
    Inertia.post(route("menu.toggleStock", menuId));
};
</script>

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



                <!-- Add Menu Dialog -->
                <Dialog>
                    <DialogTrigger as-child>
                        <button size="lg" class="font-medium bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                            + Add Menu Item
                        </button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-lg p-6 rounded-2xl">
                        <DialogHeader>
                            <DialogTitle
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                Add New Menu Item
                            </DialogTitle>
                        </DialogHeader>

                        <form @submit.prevent="submit" class="space-y-5 mt-4">
                            <div class="space-y-1.5">
                                <Label>Name</Label>
                                <Input
                                    v-model="form.name"
                                    placeholder="e.g. Beef Burger"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div class="space-y-1.5">
                                <Label>Description</Label>
                                <Textarea
                                    v-model="form.description"
                                    placeholder="Short description..."
                                />
                                <p
                                    v-if="form.errors.description"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.description }}
                                </p>
                            </div>

                            <div class="space-y-1.5">
                                <Label>Price (Ksh)</Label>
                                <Input
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    placeholder="250.00"
                                />
                                <p
                                    v-if="form.errors.price"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.price }}
                                </p>
                            </div>

                            <div class="space-y-1.5">
                                <Label>Picture</Label>
                                <Input
                                    type="file"
                                    @change="
                                        form.picture = $event.target.files[0]
                                    "
                                />
                                <p
                                    v-if="form.errors.picture"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.picture }}
                                </p>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full font-semibold bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition"
                            >
                                {{
                                    form.processing
                                        ? "Adding..."
                                        : "Add Menu Item"
                                }}
                            </button>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </template>

        <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                v-if="menus.length"
                class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3"
            >
                <Card
                    v-for="menu in menus"
                    :key="menu.id"
                    class="flex flex-col h-full overflow-hidden border border-gray-200 dark:border-neutral-800 shadow-sm hover:shadow-lg transition-shadow duration-200 rounded-md"
                >
                    <!-- Image container -->
                    <div
                        class="w-full h-48 overflow-hidden bg-gray-100 dark:bg-neutral-900 "
                    >
                        <img
                            v-if="menu.picture_url"
                            :src="menu.picture_url"
                            alt="menu image"
                            class="w-full h-full object-cover object-center transition-transform duration-300 hover:scale-105"
                        />
                    </div>

                    <!-- Header -->
                    <CardHeader
                        class="flex justify-between items-center p-4 bg-gray-50 dark:bg-neutral-900"
                    >
                        <CardTitle
                            class="text-lg font-semibold text-gray-900 dark:text-white truncate"
                        >
                            {{ menu.name }}
                        </CardTitle>
                        <Badge
                            :class="
                                menu.is_out_of_stock
                                    ? 'bg-red-600 text-white px-3 py-1 rounded-md text-sm'
                                    : 'bg-green-600 text-white px-3 py-1 rounded-md text-sm'
                            "
                        >
                            {{
                                menu.is_out_of_stock
                                    ? "Out of Stock"
                                    : "Available"
                            }}
                        </Badge>
                    </CardHeader>

                    <!-- Content & Actions -->
                    <CardContent
                        class="p-4 flex-1 flex flex-col justify-between"
                    >
                        <div>
                            <p
                                class="text-gray-700 dark:text-gray-400 line-clamp-3"
                            >
                                {{ menu.description }}
                            </p>
                            <p
                                class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-2"
                            >
                                Ksh {{ menu.price }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 mt-4">
                            <Button
                                size="sm"
                                variant="secondary"
                                class="flex-1 bg-white text-black border hover:bg-blue-700"
                                @click="startEdit(menu)"
                            >
                                Edit
                            </Button>
                            <Button
                                size="sm"
                                class="flex-1 bg-black text-white hover:bg-red-700"
                                :variant="
                                    menu.is_out_of_stock
                                        ? 'default'
                                        : 'destructive'
                                "
                                @click="toggleStock(menu.id)"
                            >
                                {{
                                    menu.is_out_of_stock
                                        ? "Mark Available"
                                        : "Mark Out of Stock"
                                }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="text-center py-20 text-gray-500 dark:text-gray-400 space-y-2"
            >
                <p class="text-xl font-medium">No menu items yet</p>
                <p>Add your first item to get started!</p>
            </div>
        </div>

        <!-- Edit Menu Dialog -->
        <Dialog
            :open="!!editForm.id"
            @update:open="(val) => !val && (editForm.id = null)"
        >
            <DialogContent class="sm:max-w-lg p-6 rounded-xl">
                <DialogHeader>
                    <DialogTitle
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        Edit Menu Item
                    </DialogTitle>
                </DialogHeader>

                <form @submit.prevent="update" class="space-y-5 mt-4">
                    <div class="space-y-1.5">
                        <Label>Name</Label>
                        <Input
                            v-model="editForm.name"
                            placeholder="e.g. Beef Burger"
                        />
                        <p
                            v-if="editForm.errors.name"
                            class="text-sm text-red-500"
                        >
                            {{ editForm.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <Label>Description</Label>
                        <Textarea
                            v-model="editForm.description"
                            placeholder="Short description..."
                        />
                        <p
                            v-if="editForm.errors.description"
                            class="text-sm text-red-500"
                        >
                            {{ editForm.errors.description }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <Label>Price (Ksh)</Label>
                        <Input
                            v-model="editForm.price"
                            type="number"
                            step="0.01"
                            placeholder="250.00"
                        />
                        <p
                            v-if="editForm.errors.price"
                            class="text-sm text-red-500"
                        >
                            {{ editForm.errors.price }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <Label>Picture</Label>
                        <Input
                            type="file"
                            @change="editForm.picture = $event.target.files[0]"
                        />
                        <p
                            v-if="editForm.errors.picture"
                            class="text-sm text-red-500"
                        >
                            {{ editForm.errors.picture }}
                        </p>
                    </div>

                    <!-- <div class="flex items-center space-x-2">
            <Input type="checkbox" v-model="editForm.is_out_of_stock" />
            <Label>Out of Stock</Label>
          </div> -->

                    <div class="flex space-x-2 pt-4">
                        <Button
                            type="submit"
                            :disabled="editForm.processing"
                            class="flex-1 font-semibold"
                        >
                            {{
                                editForm.processing
                                    ? "Updating..."
                                    : "Update Menu"
                            }}
                        </Button>
                        <Button
                            type="button"
                            variant="destructive"
                            class="flex-1 font-semibold"
                            @click="editForm.id = null"
                        >
                            Cancel
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
