<template>
  <AppLayout>
    <div class="px-4 py-6 sm:px-0">
      <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 flex items-center justify-center">
        <div class="text-center">
          <h1 class="text-3xl font-bold text-gray-900 mb-4">
            ¡Bienvenido, {{ authStore.user?.name }}!
          </h1>
          <p class="text-lg text-gray-600 mb-8">
            Has iniciado sesión exitosamente en tu aplicación Vue.js
          </p>
          <div class="space-x-4">
            <router-link
              to="/dashboard"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Ver Dashboard
            </router-link>
            <router-link
              to="/users"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Gestionar Usuarios
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/AppLayout.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>
