<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-sm w-full space-y-6">
      <div>
        <h2 class="mt-4 text-center text-2xl font-extrabold text-gray-900">Crear Cuenta</h2>
        <p class="mt-1 text-center text-xs text-gray-600">
          O
          <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
            iniciar sesión con tu cuenta existente
          </router-link>
        </p>
      </div>

      <form class="mt-6 space-y-4" @submit.prevent="handleRegister">
        <div class="space-y-3">
          <div>
            <label for="name" class="block text-xs font-medium text-gray-700">Nombre</label>
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              required
              class="mt-1 appearance-none relative block w-full px-2 py-1.5 border border-gray-300 placeholder-gray-500 text-gray-900 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              placeholder="Tu nombre completo"
            />
          </div>

          <div>
            <label for="email" class="block text-xs font-medium text-gray-700">Email</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="mt-1 appearance-none relative block w-full px-2 py-1.5 border border-gray-300 placeholder-gray-500 text-gray-900 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              placeholder="tu@email.com"
            />
          </div>

          <div>
            <label for="password" class="block text-xs font-medium text-gray-700">Contraseña</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              required
              class="mt-1 appearance-none relative block w-full px-2 py-1.5 border border-gray-300 placeholder-gray-500 text-gray-900 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              placeholder="Mínimo 8 caracteres"
            />
          </div>

          <div>
            <label for="password_confirmation" class="block text-xs font-medium text-gray-700"
              >Confirmar Contraseña</label
            >
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              required
              class="mt-1 appearance-none relative block w-full px-2 py-1.5 border border-gray-300 placeholder-gray-500 text-gray-900 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              placeholder="Repite tu contraseña"
            />
          </div>
        </div>

        <div v-if="authStore.error" class="rounded-md bg-red-50 p-2">
          <div class="text-xs text-red-700">
            {{ authStore.error }}
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="authStore.loading"
            class="group relative w-full flex justify-center py-1.5 px-3 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="authStore.loading" class="absolute left-0 inset-y-0 flex items-center pl-2">
              <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
            </span>
            {{ authStore.loading ? 'Creando cuenta...' : 'Crear Cuenta' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const handleRegister = async () => {
  const result = await authStore.register(form)
  if (result.success) {
    router.push('/')
  }
}
</script>
