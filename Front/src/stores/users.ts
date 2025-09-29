import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'
import type { User } from './auth'

export const useUsersStore = defineStore('users', () => {
  // Estado
  const users = ref<User[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Acciones
  const fetchUsers = async () => {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get('/users')
      users.value = response.data.data || response.data
      return { success: true, data: users.value }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al cargar usuarios'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const createUser = async (userData: Omit<User, 'id' | 'created_at' | 'updated_at'>) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.post('/users', userData)
      const newUser = response.data.data || response.data
      users.value.unshift(newUser)
      return { success: true, data: newUser }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al crear usuario'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const updateUser = async (id: number, userData: Partial<User>) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.put(`/users/${id}`, userData)
      const updatedUser = response.data.data || response.data
      const index = users.value.findIndex(user => user.id === id)
      if (index !== -1) {
        users.value[index] = updatedUser
      }
      return { success: true, data: updatedUser }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al actualizar usuario'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const deleteUser = async (id: number) => {
    loading.value = true
    error.value = null
    
    try {
      await api.delete(`/users/${id}`)
      users.value = users.value.filter(user => user.id !== id)
      return { success: true }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al eliminar usuario'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const getUserById = (id: number) => {
    return users.value.find(user => user.id === id)
  }

  return {
    // Estado
    users,
    loading,
    error,
    // Acciones
    fetchUsers,
    createUser,
    updateUser,
    deleteUser,
    getUserById
  }
})
