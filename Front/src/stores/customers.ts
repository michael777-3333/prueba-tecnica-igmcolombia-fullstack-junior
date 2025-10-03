import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'
import type { CreateCustomerData, Customer, UpdateCustomerData } from '@/views/custormers/interfaces/customer.interface'

export const useCustomersStore = defineStore('customers', () => {
  const customers = ref<Customer[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchCustomers = async () => {
    loading.value = true
    error.value = null
    console.log('fetchCustomers')

    try {
      const response = await api.get('/customers')
      customers.value = response.data.data || response.data
      return { success: true, data: customers.value }
    } catch (error) {
      error.value = error as string
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const createCustomer = async (customer: CreateCustomerData) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/customers', customer)
      customers.value.unshift(response.data.data || response.data)
      return { success: true, data: response.data.data || response.data }
    } catch (error) {
      error.value = error as string
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const updateCustomer = async (id: number, customer: UpdateCustomerData) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/customers/${id}`, customer)
      return { success: true, data: response.data.data || response.data }
    } catch (error) {
      error.value = error as string
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const getCustomerById = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/customers/${id}`)
      return { success: true, data: response.data.data || response.data }
    } catch (error) {
      error.value = error as string
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  return {
    customers,
    loading,
    error,
    fetchCustomers,
    createCustomer,
    updateCustomer,
    getCustomerById,
  }

})

