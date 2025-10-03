import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'
import type { CreateProductData, Product, UpdateProductData } from '@/views/products/interfaces/products.interface'

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

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

  const createProduct = async (productData: CreateProductData) => {
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


  const updateProduct = async (id: number, productData: UpdateProductData) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.put(`/items/${id}`, productData)
      return { success: true, data: response.data.data || response.data }
    }catch(err: any){
      error.value = err.response?.data?.message || 'Error al actualizar producto'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }

  }

  return {
    products,
    loading,
    error,
    fetchProducts,
    createProduct,
    updateProduct,
  }


})


