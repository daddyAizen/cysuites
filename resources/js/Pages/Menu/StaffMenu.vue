<template>
  <AppLayout>
    <div class="max-w-6xl mx-auto py-10 px-4">
      <h2 class="text-3xl font-bold mb-4 text-center text-gray-800">
        Staff Menu
        <p class="text-sm font-medium text-gray-600">
          Discount: 35%
        </p>
      </h2>

      <!-- Menu Grid -->
      <div v-if="menus.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="menu in menus"
          :key="menu.id"
          class="bg-white rounded-md shadow-md overflow-hidden hover:shadow-xl transition relative"
        >
          <img
            v-if="menu.picture"
            :src="`/storage/${menu.picture}`"
            alt="Food Image"
            class="h-44 w-full object-cover"
          />
          <span
            class="absolute top-3 left-3 bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full"
          >
            KES {{ discountedPrice(menu.price) }}
          </span>

          <div class="p-4 flex flex-col justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-1">
                {{ menu.name }}
              </h3>
              <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ menu.description }}
              </p>
              <p class="text-xs text-green-700 font-medium italic">
                35% off
              </p>
            </div>

            <div class="flex items-center justify-between mt-auto">
              <div class="flex items-center space-x-3">
                <button
                  @click="decrement(menu.id)"
                  class="bg-gray-200 hover:bg-gray-300 rounded-full h-8 w-8 flex items-center justify-center text-gray-700"
                >
                  −
                </button>
                <span class="text-lg font-medium text-gray-800 w-6 text-center">
                  {{ quantities[menu.id] || 0 }}
                </span>
                <button
                  @click="increment(menu.id)"
                  class="bg-green-600 hover:bg-green-700 text-white rounded-full h-8 w-8 flex items-center justify-center"
                >
                  +
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center text-gray-500 text-lg mt-10">
        No menu items available.
      </div>

      <!-- Floating Cart -->
      <transition name="fade">
        <div
          v-if="cartItemCount > 0"
          class="z-20 fixed bottom-6 right-6 bg-green-600 text-white rounded-full shadow-lg flex items-center px-6 py-3 cursor-pointer hover:bg-green-700 transition"
          @click="isCheckoutOpen = true"
        >
          <span class="font-semibold mr-3">
            🛒 {{ cartItemCount }} item{{ cartItemCount > 1 ? 's' : '' }}
          </span>
          <button class="bg-white text-green-700 font-bold px-4 py-1 rounded-full">
            Checkout
          </button>
        </div>
      </transition>

      <!-- Checkout Modal -->
      <Dialog v-model:open="isCheckoutOpen">
        <DialogContent class="max-w-lg rounded-2xl shadow-lg bg-white p-6">
          <DialogHeader>
            <DialogTitle class="text-2xl font-semibold text-gray-800">
              Checkout
            </DialogTitle>
          </DialogHeader>

          <div class="space-y-4 mt-4">
            <div
              v-for="menu in selectedMenus"
              :key="menu.id"
              class="flex items-center gap-4 border-b pb-3"
            >
              <img
                v-if="menu.picture"
                :src="`/storage/${menu.picture}`"
                class="h-14 w-14 object-cover rounded-md"
              />
              <div class="flex-1">
                <p class="font-medium text-gray-800">{{ menu.name }}</p>
                <p class="text-sm text-gray-600">
                  {{ quantities[menu.id] }} ×
                  <span class="line-through text-gray-400">
                    KES {{ menu.price }}
                  </span>
                  <span class="ml-1 text-green-700 font-semibold">
                    KES {{ discountedPrice(menu.price) }}
                  </span>
                </p>
              </div>
              <p class="font-semibold text-gray-700">
                KES {{ discountedPrice(menu.price) * quantities[menu.id] }}
              </p>
            </div>

            <div class="pt-3 border-t mt-3">
              <p class="text-sm text-green-700 font-medium">
                Discount (35% Off)
              </p>
              <p class="text-xs text-gray-600">
                You saved KES {{ discountSaved }}
              </p>
            </div>

            <div class="flex justify-between items-center pt-3 border-t mt-3">
              <span class="font-semibold text-gray-800 text-lg">Total:</span>
              <span class="font-bold text-green-700 text-xl">
                KES {{ totalAmount }}
              </span>
            </div>
          </div>

          <DialogFooter class="mt-6 flex justify-end space-x-3">
            <button
              @click="isCheckoutOpen = false"
              class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg font-medium"
            >
              Cancel
            </button>
            <button
              @click="submitOrder"
              :disabled="isSubmitting"
              class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold"
            >
              {{ isSubmitting ? 'Placing...' : 'Confirm Order' }}
            </button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      <!-- Toast Notification -->
      <transition name="fade">
        <div
          v-if="toastMessage"
          class="fixed bottom-10 right-10 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm font-medium"
        >
          {{ toastMessage }}
        </div>
      </transition>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/Components/ui/dialog'

const DISCOUNT_PERCENTAGE = 35

const page = usePage()
const menus = page.props.menus || []

const quantities = ref({})
const isSubmitting = ref(false)
const isCheckoutOpen = ref(false)
const toastMessage = ref('')

const selectedMenus = computed(() => menus.filter(m => quantities.value[m.id] > 0))
const cartItemCount = computed(() => Object.values(quantities.value).reduce((sum, q) => sum + q, 0))

const discountedPrice = (price) => Math.round(price - (price * DISCOUNT_PERCENTAGE) / 100)

const subtotal = computed(() => selectedMenus.value.reduce((sum, menu) => sum + menu.price * quantities.value[menu.id], 0))
const totalAmount = computed(() => selectedMenus.value.reduce((sum, menu) => sum + discountedPrice(menu.price) * quantities.value[menu.id], 0))
const discountSaved = computed(() => subtotal.value - totalAmount.value)

const increment = id => quantities.value[id] = (quantities.value[id] || 0) + 1
const decrement = id => { if (quantities.value[id] > 0) quantities.value[id] -= 1 }

const showToast = msg => { toastMessage.value = msg; setTimeout(() => toastMessage.value = '', 3000) }

const form = useForm({ orders: [], total: 0 })
const submitOrder = () => {
  const ordersToSubmit = Object.entries(quantities.value)
    .filter(([_, qty]) => qty > 0)
    .map(([menu_id, quantity]) => ({ menu_id: parseInt(menu_id), quantity }))

  if (!ordersToSubmit.length) return

  isSubmitting.value = true
  form.orders = ordersToSubmit
  form.total = totalAmount.value

  form.post(route('orders.store'), {
    onFinish: () => isSubmitting.value = false,
    onSuccess: () => { quantities.value = {}; isCheckoutOpen.value = false; showToast('✅ Order placed successfully!') },
    onError: () => showToast('❌ Failed to place order. Try again.')
  })
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s }
.fade-enter-from, .fade-leave-to { opacity: 0 }
</style>
