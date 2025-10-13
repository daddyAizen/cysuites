<script setup>
import { ref, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/Components/ui/dialog'

defineProps({ promotions: Array })

const page = usePage()
const showDialog = ref(false)
const editing = ref(false)
const selectedPromo = ref(null)

const form = useForm({
  id: null,
  name: '',
  description: '',
  start_date: '',
  end_date: ''
})

// const successMessage = ref(page.props.flash.success || null)
// watch(() => page.props.flash.success, (val) => { successMessage.value = val })

const openCreate = () => {
  editing.value = false
  form.reset()
  showDialog.value = true
}

const openEdit = (promo) => {
  editing.value = true
  selectedPromo.value = promo
  form.id = promo.id
  form.name = promo.name
  form.description = promo.description
  form.start_date = promo.start_date
  form.end_date = promo.end_date
  showDialog.value = true
}

const submit = () => {
  if (editing.value) {
    form.put(route('promotions.update', form.id), {
      preserveScroll: true,
      onSuccess: () => {
        showDialog.value = false
      }
    })
  } else {
    form.post(route('promotions.store'), {
      preserveScroll: true,
      onSuccess: () => {
        showDialog.value = false
      }
    })
  }
}
</script>

<template>
  <AppLayout>
    <div class="p-8 max-w-6xl mx-auto space-y-6">

      <!-- Alert -->
      <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ successMessage }}</span>
        <span @click="successMessage = null" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
          ✕
        </span>
      </div>

      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-semibold tracking-tight">Promotions</h1>
        <Button class="bg-blue-600 text-white hover:bg-blue-700" @click="openCreate">+ Add Promotion</Button>
      </div>

         <div class="max-w-[85rem] mx-auto my-8 aspect-w-16 aspect-h-7">
                <img
                    class="w-full max-h-[30vh] object-cover rounded-xl"
                    src="https://images.pexels.com/photos/5872364/pexels-photo-5872364.jpeg"
                    alt="Features Image"
                />
            </div>
      <!-- Promotions Table -->
      <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
        <table class="w-full border-collapse min-w-[700px]">
          <thead class="bg-muted/50">
            <tr>
              <th class="text-left p-4 font-medium">Name</th>
              <th class="text-left p-4 font-medium">Description</th>
              <th class="text-left p-4 font-medium">Start Date</th>
              <th class="text-left p-4 font-medium">End Date</th>
              <th class="text-right p-4 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="promo in promotions"
              :key="promo.id"
              class="border-t hover:bg-muted/30 transition"
            >
              <td class="p-4">{{ promo.name }}</td>
              <td class="p-4 line-clamp-2">{{ promo.description }}</td>
              <td class="p-4">{{ promo.start_date }}</td>
              <td class="p-4">{{ promo.end_date }}</td>
              <td class="p-4 text-right">
                <Button variant="outline" size="sm" @click="openEdit(promo)">Edit</Button>
              </td>
            </tr>
            <tr v-if="promotions.length === 0">
              <td colspan="5" class="p-6 text-center text-muted-foreground">No promotions yet.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add/Edit Promotion Modal -->
      <Dialog v-model:open="showDialog">
        <DialogContent class="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>{{ editing ? 'Edit Promotion' : 'Add Promotion' }}</DialogTitle>
          </DialogHeader>

          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Name</label>
              <Input v-model="form.name" placeholder="Promotion name" />
              <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Description</label>
              <Textarea v-model="form.description" placeholder="Promotion description" />
              <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Start Date</label>
                <Input type="date" v-model="form.start_date" />
                <div v-if="form.errors.start_date" class="text-red-500 text-sm mt-1">{{ form.errors.start_date }}</div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">End Date</label>
                <Input type="date" v-model="form.end_date" />
                <div v-if="form.errors.end_date" class="text-red-500 text-sm mt-1">{{ form.errors.end_date }}</div>
              </div>
            </div>

            <DialogFooter class="flex justify-end gap-3 mt-4">
              <Button type="button" variant="outline" @click="showDialog = false">Cancel</Button>
              <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : editing ? 'Update Promotion' : 'Create Promotion' }}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>
