<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { CheckCircle, XCircle } from 'lucide-vue-next'

const props = defineProps({
  rooms: Array,
})
</script>

<template>
  <AppLayout title="Rooms">
    <Head title="Rooms" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        Room Management
      </h2>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <Card
            v-for="room in rooms"
            :key="room.id"
            class="border rounded-2xl shadow-sm transition hover:shadow-md dark:bg-neutral-900 dark:border-neutral-800"
          >
            <CardHeader class="flex justify-between items-center">
              <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ room.name }}
              </CardTitle>
              <Badge
                :class="room.is_booked ? 'bg-red-500 text-white' : 'bg-green-500 text-white'"
              >
                {{ room.is_booked ? 'Booked' : 'Available' }}
              </Badge>
            </CardHeader>

            <CardContent class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-gray-600 dark:text-gray-400 font-medium">Room Code:</span>
                <span class="font-mono text-sm text-gray-800 dark:text-gray-100">{{ room.room_code }}</span>
              </div>

              <div class="flex items-center justify-between">
                <span class="text-gray-600 dark:text-gray-400 font-medium">Status:</span>
                <div class="flex items-center space-x-2">
                  <component
                    :is="room.is_booked ? XCircle : CheckCircle"
                    class="w-5 h-5"
                    :class="room.is_booked ? 'text-red-500' : 'text-green-500'"
                  />
                  <span :class="room.is_booked ? 'text-red-500' : 'text-green-500'">
                    {{ room.is_booked ? 'Taken' : 'Free' }}
                  </span>
                </div>
              </div>

              <div class="pt-2">
                <Button
                  :variant="room.is_booked ? 'secondary' : 'default'"
                  :disabled="room.is_booked"
                  class="w-full"
                >
                  {{ room.is_booked ? 'Unavailable' : 'Book Now' }}
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>

        <div v-if="rooms.length === 0" class="text-center py-16 text-gray-500 dark:text-gray-400">
          No rooms available.
        </div>
      </div>
    </div>
  </AppLayout>
</template>
