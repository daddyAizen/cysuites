<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

import { Button } from "@/Components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

const props = defineProps({
    users: Object,
});

const form = useForm({
    name: "",
    email: "",
    employee_id: "",
    password: "",
    role_id: "",
});

const submit = () => {
    form.post(route("staff.store"), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout title="Staff Management">
        <Head title="Staff Management" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Staff Management
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-neutral-900 shadow-sm sm:rounded-lg p-6 space-y-6 border border-gray-100 dark:border-neutral-800"
                >
                    <!-- Header Row -->
                    <div class="flex justify-between items-center">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            All Staff Members
                        </h3>

                        <Dialog>
                            <DialogTrigger as-child>
                                <Button
                                    class="bg-indigo-600 text-white hover:bg-indigo-700"
                                    >Add Staff</Button
                                >
                            </DialogTrigger>

                            <DialogContent class="sm:max-w-[425px]">
                                <DialogHeader>
                                    <DialogTitle class="text-lg font-semibold"
                                        >Add New Staff</DialogTitle
                                    >
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4 mt-4"
                                >
                                    <div class="space-y-2">
                                        <Label for="name">Name</Label>
                                        <Input
                                            id="name"
                                            v-model="form.name"
                                            placeholder="Full name"
                                        />
                                        <div
                                            v-if="form.errors.name"
                                            class="text-sm text-red-500"
                                        >
                                            {{ form.errors.name }}
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="email">Email</Label>
                                        <Input
                                            id="email"
                                            v-model="form.email"
                                            placeholder="example@email.com"
                                        />
                                        <div
                                            v-if="form.errors.email"
                                            class="text-sm text-red-500"
                                        >
                                            {{ form.errors.email }}
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="employee_id"
                                            >Employee ID</Label
                                        >
                                        <Input
                                            id="employee_id"
                                            v-model="form.employee_id"
                                            placeholder="CY001"
                                        />
                                        <div
                                            v-if="form.errors.employee_id"
                                            class="text-sm text-red-500"
                                        >
                                            {{ form.errors.employee_id }}
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="role_id">Role</Label>
                                        <select
                                            id="role_id"
                                            v-model="form.role_id"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-neutral-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="" disabled>
                                                Select Role
                                            </option>
                                            <option
                                                v-for="role in $page.props
                                                    .roles"
                                                :key="role.id"
                                                :value="role.id"
                                            >
                                                {{ role.name }}
                                            </option>
                                        </select>
                                        <div
                                            v-if="form.errors.role_id"
                                            class="text-sm text-red-500"
                                        >
                                            {{ form.errors.role_id }}
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="password">Password</Label>
                                        <Input
                                            id="password"
                                            type="password"
                                            v-model="form.password"
                                            placeholder="••••••"
                                        />
                                        <div
                                            v-if="form.errors.password"
                                            class="text-sm text-red-500"
                                        >
                                            {{ form.errors.password }}
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
                                                    : "Add Staff"
                                            }}
                                        </Button>
                                    </div>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                    <div
                        class="max-w-[77rem] mx-auto my-8 aspect-w-16 aspect-h-7"
                    >
                        <img
                            class="w-full max-h-[30vh] object-cover rounded-xl"
                            src="https://images.unsplash.com/photo-1641122669951-3e2aff778d3b?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c3RhZmZ8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=900"
                            alt="Features Image"
                        />
                    </div>

                    <!-- Table -->
                    <div
                        class="rounded-md border border-gray-200 dark:border-neutral-800 overflow-hidden"
                    >
                        <Table>
                            <TableHeader>
                                <TableRow
                                    class="bg-gray-50 dark:bg-neutral-800"
                                >
                                    <TableHead class="w-12 text-left"
                                        >#</TableHead
                                    >
                                    <TableHead>Employee ID</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Role</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="(user, index) in users.data"
                                    :key="user.id"
                                    class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors"
                                >
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-mono text-sm">{{
                                        user.employee_id
                                    }}</TableCell>
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>{{ user.role.name }}</TableCell>
                                </TableRow>

                                <TableRow v-if="users.length === 0">
                                    <TableCell
                                        colspan="4"
                                        class="text-center py-6 text-gray-500 dark:text-gray-400"
                                    >
                                        No staff members found.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                        <div class="flex justify-center mt-6 space-x-2">
                            <!-- Pagination -->
                            <div class="my-3 flex justify-center gap-3">
                                <Button
                                    variant="outline"
                                    :disabled="!users.prev_page_url"
                                    @click="$inertia.get(users.prev_page_url)"
                                >
                                    Previous
                                </Button>
                                <Button
                                    variant="outline"
                                    :disabled="!users.next_page_url"
                                    @click="$inertia.get(users.next_page_url)"
                                >
                                    Next
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
