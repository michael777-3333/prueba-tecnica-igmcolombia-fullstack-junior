import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import type { User } from '@/views/users/interfaces/user.interface'


export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export const useAuthStore = defineStore('auth', () => {
  // Estado
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('auth_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)

  // Acciones
  const login = async (credentials: LoginCredentials) => {
    loading.value = true
    error.value = null
    
    try {
      console.log('login', credentials);
      
      const response = await api.post('/auth/login', credentials)
      const { data } = response.data
      
      token.value = data.token
      user.value = data.user
      
      // Guardar en localStorage
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
      
      return { success: true, data: response.data }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error de inicio de sesión'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const register = async (userData: RegisterData) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.post('/auth/register', userData)
      const { data } = response.data
      
      token.value = data.token
      user.value = data.user
      
      // Guardar en localStorage
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
      
      return { success: true, data: response.data }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error de registro'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    loading.value = true
    error.value = null
    
    try {
      await api.post('/auth/logout')
    } catch (err: any) {
      console.error('Error al cerrar sesión:', err)
    } finally {
      // Limpiar estado local independientemente del resultado del servidor
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      loading.value = false
    }
  }

  const fetchUser = async () => {
    if (!token.value) return
    
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get('/auth/me')
      user.value = response.data.data
      localStorage.setItem('user', JSON.stringify(response.data.data))
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al cargar usuario'
      // Si hay error, limpiar el token
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    } finally {
      loading.value = false
    }
  }

  const updateProfile = async (profileData: Partial<User>) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.put('/auth/profile', profileData)
      user.value = response.data.data
      localStorage.setItem('user', JSON.stringify(response.data.data))
      return { success: true, data: response.data }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al actualizar perfil'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const changePassword = async (passwordData: {
    current_password: string
    password: string
    password_confirmation: string
  }) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.put('/auth/password', passwordData)
      return { success: true, data: response.data }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al cambiar contraseña'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  // Inicializar usuario desde localStorage
  const initAuth = () => {
    const storedUser = localStorage.getItem('user')
    if (storedUser && token.value) {
      try {
        user.value = JSON.parse(storedUser)
      } catch (err) {
        console.error('Error al parsear usuario del localStorage:', err)
        localStorage.removeItem('user')
        localStorage.removeItem('auth_token')
      }
    }
  }

  return {
    // Estado
    user,
    token,
    loading,
    error,
    // Getters
    isAuthenticated,
    // Acciones
    login,
    register,
    logout,
    fetchUser,
    updateProfile,
    changePassword,
    initAuth
  }
})
