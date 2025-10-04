import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'
import type { Client, Product } from '@/views/generate-invoices/interfaces/invoice.interface'

export const useCatalogStore = defineStore('catalog', () => {
  // Estado
  const clients = ref<Client[]>([])
  const products = ref<Product[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Acciones para clientes
  const fetchClients = async () => {
    loading.value = true
    error.value = null

    try {
      const response = await api.get('/customers')

      const clientsData = response.data.data || response.data

      clients.value = Array.isArray(clientsData) ? clientsData : []

      return { success: true, data: clients.value }
    } catch (err: any) {
      console.error('Error fetching clients:', err)
      error.value = err.response?.data?.message || 'Error al cargar clientes'
      clients.value = [] // Asegurar que sea un array vacío en caso de error
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const createClient = async (clientData: Omit<Client, 'id' | 'created_at' | 'updated_at'>) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/customers', clientData)
      const newClient = response.data.data || response.data
      clients.value.push(newClient)
      return { success: true, data: newClient }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al crear cliente'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  // Acciones para productos
  const fetchProducts = async () => {
    loading.value = true
    error.value = null

    try {
      const response = await api.get('/items')
      products.value = response.data.data || response.data
      return { success: true, data: products.value }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al cargar productos'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const createProduct = async (productData: Omit<Product, 'id' | 'created_at' | 'updated_at'>) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/items', productData)
      const newProduct = response.data.data || response.data
      products.value.push(newProduct)
      return { success: true, data: newProduct }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al crear producto'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  return {
    // Estado
    clients,
    products,
    loading,
    error,

    // Acciones
    fetchClients,
    createClient,
    fetchProducts,
    createProduct,
  }
})
