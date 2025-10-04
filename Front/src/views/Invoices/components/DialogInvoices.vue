<template>
  <div class="dialog-invoices-container">
    <form @submit.prevent="handleSubmit" class="invoices-form">
      <div class="form-group">
        <label for="invoice_number" class="form-label">Número de factura *</label>
        <div class="input-with-button">
          <InputText
            id="invoice_number"
            v-model="form.invoice_number"
            @input="clearError('invoice_number')"
            placeholder="Ej: INV-001"
            :class="['form-input', { 'p-invalid': errors.invoice_number }]"
            required
          />
          <Button
            v-if="!props.isEditMode"
            icon="pi pi-refresh"
            @click="generateInvoiceNumber"
            class="generate-btn"
            v-tooltip.top="'Generar número automático'"
          />
        </div>
        <small v-if="errors.invoice_number" class="p-error">{{ errors.invoice_number }}</small>
        <small v-else class="form-hint">Formato sugerido: INV-XXX o FAC-XXX</small>
      </div>

      <div class="form-group">
        <label for="description" class="form-label">Descripción *</label>
        <Textarea
          id="description"
          v-model="form.description"
          @input="clearError('description')"
          placeholder="Descripción de la factura"
          :class="['form-input', { 'p-invalid': errors.description }]"
          rows="3"
          required
        />
        <small v-if="errors.description" class="p-error">{{ errors.description }}</small>
      </div>

      <div class="form-group">
        <label for="notes" class="form-label">Notas</label>
        <Textarea
          id="notes"
          v-model="form.notes"
          @input="clearError('notes')"
          placeholder="Notas adicionales (opcional)"
          :class="['form-input', { 'p-invalid': errors.notes }]"
          rows="2"
        />
        <small v-if="errors.notes" class="p-error">{{ errors.notes }}</small>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="issued_date" class="form-label">Fecha de emisión *</label>
          <InputText
            id="issued_date"
            v-model="form.issued_date"
            @input="clearError('issued_date')"
            type="date"
            placeholder="Fecha de emisión"
            :class="['form-input', { 'p-invalid': errors.issued_date }]"
            required
          />
          <small v-if="errors.issued_date" class="p-error">{{ errors.issued_date }}</small>
        </div>

        <div class="form-group">
          <label for="due_date" class="form-label">Fecha de vencimiento *</label>
          <InputText
            id="due_date"
            v-model="form.due_date"
            @input="clearError('due_date')"
            type="date"
            placeholder="Fecha de vencimiento"
            :class="['form-input', { 'p-invalid': errors.due_date }]"
            required
          />
          <small v-if="errors.due_date" class="p-error">{{ errors.due_date }}</small>
        </div>
      </div>

      <div class="form-group">
        <Button
          v-if="!props.isEditMode"
          label="Establecer fechas automáticas"
          icon="pi pi-calendar"
          @click="setAutomaticDates"
          class="auto-dates-btn"
          type="button"
        />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="total_amount" class="form-label">Total *</label>
          <InputText
            id="total_amount"
            v-model.number="form.total_amount"
            @input="clearError('total_amount')"
            type="number"
            step="0.01"
            min="0"
            placeholder="0.00"
            :class="['form-input', { 'p-invalid': errors.total_amount }]"
            required
          />
          <small v-if="errors.total_amount" class="p-error">{{ errors.total_amount }}</small>
        </div>

        <div class="form-group">
          <label for="status" class="form-label">Estado *</label>
          <Dropdown
            id="status"
            v-model="form.status"
            @change="clearError('status')"
            :options="statusOptions"
            option-label="label"
            option-value="value"
            placeholder="Selecciona el estado"
            :class="['form-input', { 'p-invalid': errors.status }]"
            required
          />
          <small v-if="errors.status" class="p-error">{{ errors.status }}</small>
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
        :label="invoicesStore.loading ? 'Guardando...' : isEditMode ? 'Actualizar' : 'Crear'"
        :icon="isEditMode ? 'pi pi-check' : 'pi pi-plus'"
        @click="handleSubmit"
        :loading="invoicesStore.loading"
        class="submit-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useInvoicesStore } from '@/stores/invoices.store'
import type { CreateInvoiceData, UpdateInvoiceData, Invoice } from '@/views/Invoices/interfaces/invoices.interfaces'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'

/**
 * Props estándar (receive from parent)
 * - invoiceData: datos cuando es edición (puede ser null)
 * - isEditMode: indica si estamos editando o creando
 */
const props = withDefaults(
  defineProps<{
    invoiceData?: Invoice | null
    isEditMode?: boolean
  }>(),
  {
    invoiceData: null,
    isEditMode: false,
  },
)

/** Emits */
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'success', data: unknown): void
}>()

const invoicesStore = useInvoicesStore()

/** Opciones para el dropdown de estado */
const statusOptions = [
  { label: 'Pendiente', value: 'pending' },
  { label: 'Pagado', value: 'paid' },
  { label: 'Vencido', value: 'overdue' },
  { label: 'Cancelado', value: 'cancelled' }
]

/** Form reactive */
const form = ref({
  invoice_number: '',
  description: '',
  notes: '',
  issued_date: '',
  due_date: '',
  total_amount: 0,
  status: 'pending',
})

/** Errores de validación */
const errors = ref({
  invoice_number: '',
  description: '',
  notes: '',
  issued_date: '',
  due_date: '',
  total_amount: '',
  status: '',
})

/** Inicializa / resetea el formulario cuando cambian las props */
watch(
  () => [props.invoiceData, props.isEditMode],
  ([invoiceData, isEditMode]) => {
    if (invoiceData && isEditMode && invoiceData !== null && typeof invoiceData === 'object') {
      const invoice = invoiceData as Invoice

      // Formatear fechas para el input type="date"
      const formatDateForInput = (dateString: string) => {
        if (!dateString) return ''
        const date = new Date(dateString)
        return date.toISOString().split('T')[0]
      }

      form.value = {
        invoice_number: invoice.invoice_number ?? '',
        description: invoice.description ?? '',
        notes: invoice.notes ?? '',
        issued_date: formatDateForInput(invoice.issued_date),
        due_date: formatDateForInput(invoice.due_date),
        total_amount: invoice.total_amount ?? 0,
        status: invoice.status ?? 'pending',
      }
    } else {
      form.value = {
        invoice_number: '',
        description: '',
        notes: '',
        issued_date: '',
        due_date: '',
        total_amount: 0,
        status: 'pending',
      }
    }
  },
  { immediate: true },
)

/** Función de validación */
const validateForm = () => {
  // Limpiar errores anteriores
  errors.value = {
    invoice_number: '',
    description: '',
    notes: '',
    issued_date: '',
    due_date: '',
    total_amount: '',
    status: '',
  }

  let isValid = true
  console.log('form.value', form.value);

  // Validar número de factura
  if (!form.value.invoice_number.trim()) {
    errors.value.invoice_number = 'El número de factura es requerido'
    isValid = false
  } else if (form.value.invoice_number.trim().length < 2) {
    errors.value.invoice_number = 'El número de factura debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar descripción
  if (!form.value.description.trim()) {
    errors.value.description = 'La descripción es requerida'
    isValid = false
  } else if (form.value.description.trim().length < 5) {
    errors.value.description = 'La descripción debe tener al menos 5 caracteres'
    isValid = false
  }

  // Validar fecha de emisión
  if (!form.value.issued_date.trim()) {
    errors.value.issued_date = 'La fecha de emisión es requerida'
    isValid = false
  } else {
    const issuedDate = new Date(form.value.issued_date)
    const today = new Date()
    today.setHours(0, 0, 0, 0)

    if (issuedDate < today) {
      errors.value.issued_date = 'La fecha de emisión no puede ser anterior a hoy'
      isValid = false
    }
  }

  // Validar fecha de vencimiento
  if (!form.value.due_date.trim()) {
    errors.value.due_date = 'La fecha de vencimiento es requerida'
    isValid = false
  } else {
    const dueDate = new Date(form.value.due_date)
    const issuedDate = new Date(form.value.issued_date)

    if (dueDate <= issuedDate) {
      errors.value.due_date = 'La fecha de vencimiento debe ser posterior a la fecha de emisión'
      isValid = false
    }
  }

  // Validar total
  if (!form.value.total_amount || form.value.total_amount <= 0) {
    errors.value.total_amount = 'El total debe ser mayor a 0'
    isValid = false
  }

  // Validar estado
  if (!form.value.status) {
    errors.value.status = 'El estado es requerido'
    isValid = false
  }

  return isValid
}

/** Limpiar error específico cuando el cliente empiece a escribir */
const clearError = (field: keyof typeof errors.value) => {
  if (errors.value[field]) {
    errors.value[field] = ''
  }
}

/** Generar número de factura automático */
const generateInvoiceNumber = () => {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const day = String(now.getDate()).padStart(2, '0')
  const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0')

  form.value.invoice_number = `INV-${year}${month}${day}-${random}`
  clearError('invoice_number')
}

/** Establecer fechas automáticas */
const setAutomaticDates = () => {
  const today = new Date()
  const dueDate = new Date(today)
  dueDate.setDate(today.getDate() + 30) // 30 días después

  form.value.issued_date = today.toISOString().split('T')[0]
  form.value.due_date = dueDate.toISOString().split('T')[0]

  clearError('issued_date')
  clearError('due_date')
}

const handleSubmit = async () => {
  // Validar formulario antes de enviar
  if (!validateForm()) {
    return
  }

  try {
    let result: { success: boolean; data?: unknown; error?: string | null } = {
      success: false,
    }

    if (props.isEditMode && props.invoiceData) {
      // Modo edición
      const updateData: UpdateInvoiceData = {
        invoice_number: form.value.invoice_number,
        description: form.value.description,
        notes: form.value.notes,
        issued_date: form.value.issued_date,
        due_date: form.value.due_date,
        total_amount: form.value.total_amount,
        status: form.value.status,
        customer: props.invoiceData.customer.id.toString(),
        user: props.invoiceData.user.id.toString(),
        products: props.invoiceData.products.map(p => p.id.toString()),
      }
      result = await invoicesStore.updateInvoice(props.invoiceData.id, updateData)
    } else {
      // Modo creación - redirigir a generate-invoices
      console.log('Crear factura desde DialogInvoices - redirigir a generate-invoices')
      emit('close')
      return
    }

    if (result.success) {
      // Emit success para que el padre reciba los datos
      emit('success', result.data)

      // Reset form
      form.value = {
        invoice_number: '',
        description: '',
        notes: '',
        issued_date: '',
        due_date: '',
        total_amount: 0,
        status: 'pending',
      }

      // Avisar al padre que cierre el modal
      emit('close')
    } else {
      console.error('Operation failed', result)
    }
  } catch (error) {
    console.error('Error al procesar factura:', error)
  }
}

const handleCancel = () => {
  // Reset form
  form.value = {
    invoice_number: '',
    description: '',
    notes: '',
    issued_date: '',
    due_date: '',
    total_amount: 0,
    status: 'pending',
  }

  // Emitir close para que el padre cierre el diálogo
  emit('close')
}
</script>

<style scoped>
.dialog-invoices-container {
  padding: 1.5rem;
  max-width: 100%;
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
  margin-bottom: 0.25rem;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-with-button {
  position: relative;
  display: flex;
  align-items: center;
}

.input-with-button .form-input {
  padding-right: 3rem;
}

.generate-btn {
  position: absolute;
  right: 0.5rem;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background: #667eea;
  border: none;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.generate-btn:hover {
  background: #5a67d8;
  transform: scale(1.05);
}

.auto-dates-btn {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  color: white;
  transition: all 0.2s ease;
  width: 100%;
  justify-content: center;
}

.auto-dates-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
  border: 1px solid #d1d5db;
}

.cancel-btn:hover {
  background-color: #f3f4f6;
  color: #374151;
}

.submit-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  transition: all 0.2s ease;
}

.submit-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Estilos para errores */
.p-error {
  color: #dc2626;
  font-size: 0.75rem;
  margin-top: 0.25rem;
  font-weight: 500;
}

/* Estilos para hints */
.form-hint {
  color: #6b7280;
  font-size: 0.75rem;
  margin-top: 0.25rem;
  font-style: italic;
}

.p-invalid {
  border-color: #dc2626 !important;
}

.p-invalid:focus {
  border-color: #dc2626 !important;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
  .dialog-invoices-container {
    padding: 1rem;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 0.75rem;
  }

  .invoices-form {
    gap: 0.75rem;
  }

  .form-label {
    font-size: 0.8125rem;
  }

  .form-input {
    padding: 0.625rem;
    font-size: 0.8125rem;
  }

  .modal-footer {
    flex-direction: column;
    gap: 0.5rem;
  }

  .cancel-btn,
  .submit-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
