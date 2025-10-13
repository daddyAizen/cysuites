<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { Button } from "@/Components/ui/button";
import { CheckCircle, XCircle } from "lucide-vue-next";

import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";

const toggleRoom = (roomId) => {
    Inertia.post(
        route("rooms.toggle", roomId),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log("Room status updated");
            },
        }
    );
};

const props = defineProps({
    rooms: Array,
});

const form = useForm({
    room_name: "",
});

const submit = () => {
    form.post(route("rooms.store"), {
        onSuccess: () => form.reset(),
    });
};


</script>

<template>
    <AppLayout title="Room Management">
        <Head title="Room Management" />

        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-white leading-tight"
            >
                Room Management
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-neutral-900 shadow-sm sm:rounded-lg p-6 space-y-6 border border-gray-100 dark:border-neutral-800"
                >
                    <div class="flex justify-between items-center">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            All Rooms
                        </h3>

                        <Dialog>
                            <DialogTrigger as-child>
                                <Button>Add Room</Button>
                            </DialogTrigger>

                            <DialogContent class="sm:max-w-[425px]">
                                <DialogHeader>
                                    <DialogTitle class="text-lg font-semibold"
                                        >Add New Room</DialogTitle
                                    >
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4 mt-4"
                                >
                                    <div class="space-y-2">
                                        <Label for="name">Room Name</Label>
                                        <Input
                                            id="name"
                                            v-model="form.room_name"
                                            placeholder="Room A1"
                                        />
                                        <div
                                            v-if="form.errors.room_name"
                                            class="text-sm text-red-500"
                                        >
                                            {{ form.errors.room_name }}
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <Button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="w-full sm:w-auto"
                                        >
                                            {{
                                                form.processing
                                                    ? "Adding..."
                                                    : "Add Room"
                                            }}
                                        </Button>
                                    </div>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        <div
                            v-for="room in rooms"
                            :key="room.id"
                            class="border border-gray-200 dark:border-neutral-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 bg-gray-50 dark:bg-neutral-800"
                        >
                            <div class="flex justify-between items-center">
                                <h4
                                    class="text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ room.room_name }}
                                </h4>
                                <span
                                    :class="[
                                        'px-3 py-1 text-xs font-medium rounded-full',
                                        room.is_booked
                                            ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200'
                                            : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200',
                                    ]"
                                >
                                    {{
                                        room.is_booked
                                            ? "Unavailable"
                                            : "Available"
                                    }}
                                </span>
                            </div>

                            <p
                                class="text-sm text-gray-500 dark:text-gray-400 mt-2"
                            >
                                Room Code: {{ room.room_code }}
                            </p>

                            <div class="mt-4 flex flex-col space-y-2">
                                <Button
                                    :disabled="room.is_booked"
                                    class="w-full"
                                    :class="{
                                        'opacity-50 cursor-not-allowed':
                                            room.is_booked,
                                    }"
                                >
                                    {{
                                        room.is_booked
                                            ? "Unavailable"
                                            : ""
                                    }}
                                </Button>

                                <Button
                                    :class="[
                                        'w-full flex items-center justify-center space-x-2 transition-all duration-200 rounded-lg font-medium py-2 px-4',
                                        room.is_booked
                                            ? 'bg-red-500 text-white cursor-not-allowed hover:bg-red-500/90'
                                            : 'bg-green-500 text-white hover:bg-green-600',
                                    ]"
                                    :disabled="room.is_booked"
                                    @click.prevent="toggleRoom(room.id)"
                                >
                                    <component
                                        :is="
                                            room.is_booked
                                                ? XCircle
                                                : CheckCircle
                                        "
                                        class="w-4 h-4"
                                        :class="
                                            room.is_booked
                                                ? 'text-white'
                                                : 'text-white'
                                        "
                                    />
                                    <span>
                                        {{
                                            room.is_booked
                                                ? "Unavailable"
                                                : "Mark Unavailable"
                                        }}
                                    </span>
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
