import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'
import type {
  Invoice,
  CreateInvoiceData,
} from '@/views/generate-invoices/interfaces/invoice.interface'

export const useInvoicesStore = defineStore('invoices', () => {
  // Estado
  const invoices = ref<Invoice[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Acciones
  const fetchInvoices = async () => {
    loading.value = true
    error.value = null

    try {
      const response = await api.get('/invoices')
      invoices.value = response.data.data || response.data
      return { success: true, data: invoices.value }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al cargar facturas'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const createInvoice = async (invoiceData: CreateInvoiceData) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/invoices', invoiceData)
      const newInvoice = response.data.data || response.data
      invoices.value.push(newInvoice)
      return { success: true, data: newInvoice }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al crear factura'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const updateInvoice = async (id: number, invoiceData: Partial<CreateInvoiceData>) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.put(`/invoices/${id}`, invoiceData)
      const updatedInvoice = response.data.data || response.data
      const index = invoices.value.findIndex((invoice) => invoice.id === id)
      if (index !== -1) {
        invoices.value[index] = updatedInvoice
      }
      return { success: true, data: updatedInvoice }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al actualizar factura'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const deleteInvoice = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      await api.delete(`/invoices/${id}`)
      invoices.value = invoices.value.filter((invoice) => invoice.id !== id)
      return { success: true }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al eliminar factura'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  return {
    // Estado
    invoices,
    loading,
    error,

    // Acciones
    fetchInvoices,
    createInvoice,
    updateInvoice,
    deleteInvoice,
  }
})
