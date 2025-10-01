<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8"
  >
    <Card class="w-full max-w-sm shadow-xl border-0">
      <template #header>
        <div class="text-center pt-4">
          <div
            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg"
          >
            <i class="pi pi-lock text-xl text-white"></i>
          </div>
          <h1 class="text-2xl font-bold text-gray-900 mb-1">Bienvenido</h1>
          <p class="text-gray-600 text-sm">Inicia sesión en tu cuenta</p>
        </div>
      </template>

      <template #content>
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div class="space-y-1">
            <label for="email" class="text-xs font-semibold text-gray-700">Email</label>
            <InputText
              id="email"
              v-model="form.email"
              type="email"
              placeholder="tu@email.com"
              class="w-full p-2"
            />
          </div>

          <div class="space-y-1">
            <label for="password" class="text-xs font-semibold text-gray-700">Contraseña</label>
            <Password
              id="password"
              v-model="form.password"
              placeholder="••••••••"
              toggleMask
              :feedback="false"
              class="w-full"
              inputClass="w-full p-2"
            />
          </div>

          <div class="flex justify-between items-center text-xs">
            <div class="flex items-center">
              <Checkbox v-model="rememberMe" binary />
              <label class="ml-1 text-gray-600 cursor-pointer">Recordarme</label>
            </div>
            <a href="#" class="text-blue-600 hover:text-blue-500 font-medium"
              >¿Olvidaste tu contraseña?</a
            >
          </div>

          <Button
            type="submit"
            label="Iniciar Sesión"
            icon="pi pi-arrow-right"
            :loading="authStore.loading"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 border-0 text-white py-2 text-sm font-semibold shadow-lg"
          />

          <div v-if="authStore.error" class="mt-2">
            <Message severity="error" :closable="true">
              {{ authStore.error }}
            </Message>
          </div>
        </form>
      </template>

      <template #footer>
        <div class="text-center text-gray-600 text-sm">
          ¿No tienes una cuenta?
          <router-link
            to="/register"
            class="font-semibold text-blue-600 hover:text-blue-500 transition-colors ml-1"
          >
            Regístrate aquí
          </router-link>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

// PrimeVue Components
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Message from 'primevue/message'
import Checkbox from 'primevue/checkbox'

const router = useRouter()
const authStore = useAuthStore()
const rememberMe = ref(false)

const form = reactive({
  email: '',
  password: '',
})

const handleLogin = async () => {
  const result = await authStore.login(form)
  if (result.success) {
    router.push('/')
  }
}
</script>

<style scoped>
:deep(.p-card) {
  border-radius: 12px;
  backdrop-filter: blur(10px);
}

:deep(.p-card .p-card-content) {
  padding: 1.5rem;
}

:deep(.p-card .p-card-footer) {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e5e7eb;
}

:deep(.p-inputtext) {
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  transition: all 0.3s ease;
}

:deep(.p-inputtext:focus) {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

:deep(.p-button) {
  border-radius: 6px;
  transition: all 0.3s ease;
}

:deep(.p-button:not(:disabled):hover) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

:deep(.p-password input) {
  border-radius: 6px;
  border: 1px solid #e5e7eb;
}

:deep(.p-checkbox .p-checkbox-box) {
  border-radius: 4px;
  border: 1px solid #d1d5db;
}

:deep(.p-checkbox .p-checkbox-box.p-highlight) {
  background: #3b82f6;
  border-color: #3b82f6;
}
</style>
