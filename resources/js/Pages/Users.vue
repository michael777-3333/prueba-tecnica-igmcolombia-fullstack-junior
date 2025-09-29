<template>
  <div class="min-h-screen bg-gray-50">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="mb-6">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">👥 Lista de Usuarios</h1>
              <p class="text-gray-600">Gestiona los usuarios de tu aplicación</p>
            </div>

            <!-- Tabla de usuarios -->
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      ID
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Nombre
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Email
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Fecha de Creación
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="4" class="px-6 py-4 text-center">
                      <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Cargando usuarios...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="error" class="bg-red-50">
                    <td colspan="4" class="px-6 py-4 text-center text-red-600">
                      ❌ {{ error }}
                    </td>
                  </tr>
                  <tr v-else-if="users.length === 0">
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                      📭 No hay usuarios disponibles
                    </td>
                  </tr>
                  <tr v-else v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ user.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ user.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ user.email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(user.created_at) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Botón de recarga -->
            <div class="mt-6 flex justify-center">
              <button 
                @click="loadUsers" 
                :disabled="loading"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
              >
                <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                {{ loading ? 'Cargando...' : '🔄 Recargar Usuarios' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const users = ref([])
const loading = ref(false)
const error = ref(null)

const loadUsers = async () => {
  loading.value = true
  error.value = null
  
  try {
    router.reload({ only: ['users'] })
  } catch (err) {
    console.error('Error loading users:', err)
    error.value = 'Error al cargar los usuarios'
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadUsers()
})
</script>

<script>
export default {
  props: {
    users: {
      type: Array,
      default: () => []
    }
  },
  setup(props) {
    const users = ref(props.users || [])
    const loading = ref(false)
    const error = ref(null)

    const loadUsers = async () => {
      loading.value = true
      error.value = null
      
      try {
        router.reload({ only: ['users'] })
      } catch (err) {
        console.error('Error loading users:', err)
        error.value = 'Error al cargar los usuarios'
      } finally {
        loading.value = false
      }
    }

    const formatDate = (dateString) => {
      if (!dateString) return '-'
      const date = new Date(dateString)
      return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    return {
      users,
      loading,
      error,
      loadUsers,
      formatDate
    }
  }
}
</script>
