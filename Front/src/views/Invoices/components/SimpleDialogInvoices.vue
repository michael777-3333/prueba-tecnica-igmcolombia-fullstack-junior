<template>
  <div class="simple-dialog-invoices">
    <form @submit.prevent="handleSubmit" class="invoices-form">
      <div class="form-group">
        <label for="invoice_number" class="form-label">Número de factura *</label>
        <InputText
          id="invoice_number"
          v-model="form.invoice_number"
          placeholder="Ej: INV-001"
          required
        />
      </div>

      <div class="form-group">
        <label for="description" class="form-label">Descripción *</label>
        <Textarea
          id="description"
          v-model="form.description"
          placeholder="Descripción de la factura"
          rows="3"
          required
        />
      </div>

      <div class="form-group">
        <label for="notes" class="form-label">Notas</label>
        <Textarea
          id="notes"
          v-model="form.notes"
          placeholder="Notas adicionales (opcional)"
          rows="2"
        />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="issued_date" class="form-label">Fecha de emisión *</label>
          <InputText
            id="issued_date"
            v-model="form.issued_date"
            type="date"
            required
          />
        </div>

        <div class="form-group">
          <label for="due_date" class="form-label">Fecha de vencimiento *</label>
          <InputText
            id="due_date"
            v-model="form.due_date"
            type="date"
            required
          />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="total_amount" class="form-label">Total *</label>
          <InputText
            id="total_amount"
            v-model.number="form.total_amount"
            type="number"
            step="0.01"
            min="0"
            placeholder="0.00"
            required
          />
        </div>

        <div class="form-group">
          <label for="status" class="form-label">Estado *</label>
          <Dropdown
            id="status"
            v-model="form.status"
            :options="statusOptions"
            option-label="label"
            option-value="value"
            placeholder="Selecciona el estado"
            required
          />
        </div>
      </div>
    </form>

    <div class="modal-footer">
      <Button
        label="Cancelar"
        icon="pi pi-times"
        @click="handleCancel"
        class="p-button-text cancel-btn"
      />
      <Button
        :label="loading ? 'Guardando...' : isEditMode ? 'Actualizar' : 'Crear'"
        :icon="isEditMode ? 'pi pi-check' : 'pi pi-plus'"
        @click="handleSubmit"
        :loading="loading"
        :disabled="loading"
        class="submit-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useInvoicesStore } from '@/stores/invoices.store'
import type { Invoice } from '@/views/Invoices/interfaces/invoices.interfaces'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'

const props = defineProps<{
  invoiceData?: Invoice | null
  isEditMode?: boolean
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'success', data: unknown): void
}>()

const invoicesStore = useInvoicesStore()
const loading = ref(false)

const statusOptions = [
  { label: 'Pendiente', value: 'pending' },
  { label: 'Pagado', value: 'paid' },
  { label: 'Vencido', value: 'overdue' },
  { label: 'Cancelado', value: 'cancelled' }
]

const form = ref({
  invoice_number: '',
  description: '',
  notes: '',
  issued_date: '',
  due_date: '',
  total_amount: 0,
  status: 'pending',
})

// Inicializar formulario cuando el componente se monta
onMounted(() => {
  if (props.invoiceData && props.isEditMode) {
    const invoice = props.invoiceData

    const formatDateForInput = (dateString: string) => {
      if (!dateString) return ''
      const date = new Date(dateString)
      return date.toISOString().split('T')[0]
    }

    form.value = {
      invoice_number: invoice.invoice_number || '',
      description: invoice.description || '',
      notes: invoice.notes || '',
      issued_date: formatDateForInput(invoice.issued_date),
      due_date: formatDateForInput(invoice.due_date),
      total_amount: invoice.total_amount || 0,
      status: invoice.status || 'pending',
    }
  }
})

const validateForm = () => {
  if (!form.value.invoice_number.trim()) return false
  if (!form.value.description.trim()) return false
  if (!form.value.issued_date) return false
  if (!form.value.due_date) return false
  if (!form.value.total_amount || form.value.total_amount <= 0) return false
  if (!form.value.status) return false

  const dueDate = new Date(form.value.due_date)
  const issuedDate = new Date(form.value.issued_date)

  return dueDate > issuedDate
}

const handleSubmit = async () => {
  if (!validateForm()) {
    alert('Por favor completa todos los campos requeridos correctamente')
    return
  }

  loading.value = true

  try {
    if (props.isEditMode && props.invoiceData) {
      const updateData = {
        invoice_number: form.value.invoice_number,
        description: form.value.description,
        notes: form.value.notes,
        issued_date: form.value.issued_date,
        due_date: form.value.due_date,
        total_amount: form.value.total_amount,
        status: form.value.status,
        customer: props.invoiceData.customer?.id?.toString() || '1',
        user: props.invoiceData.user?.id?.toString() || '1',
        products: props.invoiceData.products?.map(p => p.id.toString()) || [],
      }

      const result = await invoicesStore.updateInvoice(props.invoiceData.id, updateData)

      if (result.success) {
        emit('success', result.data)
      } else {
        alert('Error al actualizar la factura')
      }
    } else {
      emit('close')
    }
  } catch (error) {
    console.error('Error:', error)
    alert('Error al procesar la factura')
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
  emit('close')
}
</script>

<style scoped>
.simple-dialog-invoices {
  padding: 1.5rem;
}

.invoices-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.cancel-btn {
  color: #6b7280;
}

.submit-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  color: white;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }

  .modal-footer {
    flex-direction: column;
  }
}
</style>
