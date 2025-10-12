<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

defineProps({
  status: String,
})

const form = useForm({
  room_name: '',
  room_code: '',
})

const submit = () => {
  form.post(route('guests.login'), {
    onFinish: () => form.reset('room_code'),
  })
}
</script>

<template>
  <Head title="Guest Login" />

  <AuthenticationCard >


    <template #logo>
      <AuthenticationCardLogo />
    </template>


    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="room_number" value="Room Number" />
        <TextInput
          id="room_number"
          v-model="form.room_name"
          type="text"
          class="mt-1 block w-full"
          required
          autofocus
        />
        <InputError class="mt-2" :message="form.errors.room_number" />
      </div>

      <div class="mt-4">
        <InputLabel for="room_code" value="Room Code" />
        <TextInput
          id="room_code"
          v-model="form.room_code"
          type="text"
          class="mt-1 block w-full"
          required
        />
        <InputError class="mt-2" :message="form.errors.room_code" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Log in
        </PrimaryButton>
      </div>
    </form>

  </AuthenticationCard>

</template>
