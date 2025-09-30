<template>
  <div class="px-4 py-6 sm:px-0">
      <div class="max-w-3xl mx-auto">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Mi Perfil</h1>
          <p class="mt-2 text-gray-600">Gestiona tu información personal</p>
        </div>

        <div class="space-y-6">
          <!-- Profile Information -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Información Personal
              </h3>
              
              <form @submit.prevent="updateProfile">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                      Nombre
                    </label>
                    <input
                      id="name"
                      v-model="profileForm.name"
                      type="text"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                  
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                      Email
                    </label>
                    <input
                      id="email"
                      v-model="profileForm.email"
                      type="email"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                  
                  <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                      Teléfono
                    </label>
                    <input
                      id="phone"
                      v-model="profileForm.phone"
                      type="tel"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                  
                  <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">
                      Posición
                    </label>
                    <input
                      id="position"
                      v-model="profileForm.position"
                      type="text"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                </div>
                
                <div class="mt-6">
                  <label for="bio" class="block text-sm font-medium text-gray-700">
                    Biografía
                  </label>
                  <textarea
                    id="bio"
                    v-model="profileForm.bio"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Cuéntanos algo sobre ti..."
                  ></textarea>
                </div>
                
                <div class="mt-6">
                  <button
                    type="submit"
                    :disabled="authStore.loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                  >
                    {{ authStore.loading ? 'Guardando...' : 'Actualizar Perfil' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Change Password -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Cambiar Contraseña
              </h3>
              
              <form @submit.prevent="changePassword">
                <div class="space-y-4">
                  <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">
                      Contraseña Actual
                    </label>
                    <input
                      id="current_password"
                      v-model="passwordForm.current_password"
                      type="password"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                  
                  <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">
                      Nueva Contraseña
                    </label>
                    <input
                      id="new_password"
                      v-model="passwordForm.password"
                      type="password"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                  
                  <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                      Confirmar Nueva Contraseña
                    </label>
                    <input
                      id="password_confirmation"
                      v-model="passwordForm.password_confirmation"
                      type="password"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>
                </div>
                
                <div class="mt-6">
                  <button
                    type="submit"
                    :disabled="authStore.loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
                  >
                    {{ authStore.loading ? 'Cambiando...' : 'Cambiar Contraseña' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Account Information -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Información de la Cuenta
              </h3>
              
              <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500">ID de Usuario</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ authStore.user?.id }}</dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Miembro desde</dt>
                  <dd class="mt-1 text-sm text-gray-900">
                    {{ formatDate(authStore.user?.created_at || '') }}
                  </dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                  <dd class="mt-1 text-sm text-gray-900">
                    {{ formatDate(authStore.user?.updated_at || '') }}
                  </dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Estado</dt>
                  <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      Activo
                    </span>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth.store'

const authStore = useAuthStore()

const profileForm = reactive({
  name: '',
  email: '',
  phone: '',
  position: '',
  bio: ''
})

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const formatDate = (dateString: string) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const updateProfile = async () => {
  const result = await authStore.updateProfile(profileForm)
  if (result.success) {
    // Perfil actualizado exitosamente
    alert('Perfil actualizado correctamente')
  }
}

const changePassword = async () => {
  if (passwordForm.password !== passwordForm.password_confirmation) {
    alert('Las contraseñas no coinciden')
    return
  }
  
  const result = await authStore.changePassword(passwordForm)
  if (result.success) {
    alert('Contraseña cambiada correctamente')
    passwordForm.current_password = ''
    passwordForm.password = ''
    passwordForm.password_confirmation = ''
  }
}

onMounted(() => {
  if (authStore.user) {
    profileForm.name = authStore.user.name
    profileForm.email = authStore.user.email
    profileForm.phone = authStore.user.phone || ''
    profileForm.position = authStore.user.position || ''
    profileForm.bio = authStore.user.bio || ''
  }
})
</script>
