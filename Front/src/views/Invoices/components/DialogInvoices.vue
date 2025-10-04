<template>
  <div class="dialog-invoices-container">
    <form @submit.prevent="handleSubmit" class="invoices-form">
      <div class="form-group">
        <label for="name" class="form-label">Número de factura *</label>
        <InputText
          id="name"
          v-model="form.invoice_number"
          @input="clearError('invoice_number')"
          placeholder="Ingresa el número de factura"
          :class="['form-input', { 'p-invalid': errors.invoice_number }]"
          required
        />
        <small v-if="errors.invoice_number" class="p-error">{{ errors.invoice_number }}</small>
      </div>

      <div class="form-group">
        <label for="last_name" class="form-label">Descripción *</label>
        <InputText
          id="description"
          v-model="form.description"
          @input="clearError('description')"
          placeholder="Ingresa el apellido"
          :class="['form-input', { 'p-invalid': errors.description }]"
          required
        />
        <small v-if="errors.quantity" class="p-error">{{ errors.quantity }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Cliente *</label>
        <InputText
          id="customer"
          v-model="form.customer"
          @input="clearError('customer')"
          type="price"
          placeholder="Ingresa el precio"
          :class="['form-input', { 'p-invalid': errors.customer }]"
          required
        />
        <small v-if="errors.customer" class="p-error">{{ errors.customer }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Usuario *</label>
        <InputText
          id="user"
          v-model="form.user"
          @input="clearError('user')"
            type="total"
            placeholder="Ingresa el total"
            :class="['form-input', { 'p-invalid': errors.user }]"
            required
          />
          <small v-if="errors.user" class="p-error">{{ errors.user }}</small>
        </div>
        <div class="form-group">
        <label for="email" class="form-label">Fecha de emisión *</label>
        <InputText
          id="issued_date"
          v-model="form.issued_date"
          @input="clearError('issued_date')"
            type="iva"
            placeholder="Ingresa el documento"
            :class="['form-input', { 'p-invalid': errors.issued_date }]"
            required
          />
          <small v-if="errors.issued_date" class="p-error">{{ errors.issued_date }}</small>
        </div>

        <div class="form-group">
        <label for="email" class="form-label">Fecha de vencimiento *</label>
        <InputText
          id="due_date"
          v-model="form.due_date"
          @input="clearError('due_date')"
            type="iva"
            placeholder="Ingresa la fecha de vencimiento"
            :class="['form-input', { 'p-invalid': errors.due_date }]"
            required
          />
          <small v-if="errors.due_date" class="p-error">{{ errors.due_date }}</small>
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
import Password from 'primevue/password'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'

/**
 * Props estándar (receive from parent)<div class="form-group">
        <label for="email" class="form-label">Celular *</label>
        <InputText
          id="email"
          v-model="form.phone"
          @input="clearError('phone')"
            type="phone"
            placeholder="Ingresa el celular"
            :class="['form-input', { 'p-invalid': errors.phone }]"
            required
          />
          <small v-if="errors.phone" class="p-error">{{ errors.phone }}</small>
        </div>
 * - userData: datos cuando es edición (puede ser null)
 * - isEditMode: indica si estamos editando o creando
 */
const props = withDefaults(
  defineProps<{
    productData?: UpdateInvoiceData | null
    isEditMode?: boolean
  }>(),
  {
    customerData: null,
    isEditMode: false,
  },
)

/** Emits */
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'success', data: unknown): void
}>()

const invoicesStore = useInvoicesStore()

/** Form reactive */
const form = ref({
  name: '',
  description: '',
  customer: '',
  user: '',
  issued_date: '',
  due_date: '',
  total_amount: '',
  status: '',
  price: '',
  total: '',
  iva: '',
})

/** Errores de validación */
const errors = ref({
  name: '',
  description: '',
  customer: '',
  user: '',
  issued_date: '',
  due_date: '',
  total_amount: '',
  status: '',
  price: '',
  total: '',
  iva: '',
})

/** Inicializa / resetea el formulario cuando cambian las props */
watch(
  () => [props.productData, props.isEditMode],
  ([productData, isEditMode]) => {
    if (productData && isEditMode && productData !== null && typeof productData === 'object') {
      const product = productData as UpdateInvoiceData
      form.value = {
        invoice_number: product.invoice_number ?? '',
        description: product.description ?? '',
        customer: product.customer ?? '',
        user: product.user ?? '',
        issued_date: product.issued_date ?? '',
        due_date: product.due_date ?? '',
        total_amount: product.total_amount ?? '',
        status: product.status ?? '',
      }
    } else {
      form.value = {
        invoice_number: '',
        description: '',
        customer: '',
        user: '',
        issued_date: '',
        due_date: '',
        total_amount: '',
        status: '',
        iva: '',
      }
    }
  },
  { immediate: true },
)

/** Función de validación */
const validateForm = () => {
  // Limpiar errores anteriores
  errors.value = {
    name: '',
    description: '',
    customer: '',
    user: '',
    issued_date: '',
    due_date: '',
    total_amount: '',
    status: '',
  }

  let isValid = true
  console.log('form.value', form.value);

  // Validar nombre
  if (!form.value.invoice_number.trim()) {
    errors.value.invoice_number = 'El número de factura es requerido'
    isValid = false
  } else if (form.value.invoice_number.trim().length < 2) {
    errors.value.invoice_number = 'El número de factura debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar email
  if (String(form.value.description).trim() === '') {
    errors.value.description = 'La descripción es requerida'
    isValid = false
  }

  // Validar apellido
  if (!form.value.customer.trim()) {
    errors.value.customer = 'El cliente es requerido'
    isValid = false
  } else if (form.value.customer.trim().length < 2) {
    errors.value.customer = 'El cliente debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar celular
  if (!form.value.user.trim()) {
    errors.value.user = 'El usuario es requerido'
    isValid = false
  }

  // Validar documento
  if (!form.value.issued_date.trim()) {
    errors.value.issued_date = 'La fecha de emisión es requerida'
    isValid = false
  }

  // Validar documento
  if (!form.value.due_date.trim()) {
    errors.value.due_date = 'La fecha de vencimiento es requerida'
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

const handleSubmit = async () => {
  // Validar formulario antes de enviar
  if (!validateForm()) {
    return
  }
  try {
    let result: { success: boolean; data?: unknown; error?: string | null } = {
      success: false,
    }

    if (props.isEditMode && props.productData) {
      // Modo edición
      const updateData = {
        invoice_number: form.value.invoice_number,
        description: form.value.description,
        customer: form.value.customer,
        user: form.value.user,
        issued_date: form.value.issued_date,
        due_date: form.value.due_date,
        total_amount: form.value.total_amount,
        status: form.value.status,
      }
      result = await invoicesStore.updateInvoice(props.productData.id, updateData)
    } else {
      // Modo creación
      const productData: CreateInvoiceData = {
        invoice_number: form.value.invoice_number,
        description: form.value.description,
        customer: form.value.customer,
        user: form.value.user,
        issued_date: form.value.issued_date,
        due_date: form.value.due_date,
        total_amount: form.value.total_amount,
        status: form.value.status,
        notes: '',
        products: [],
      }
      result = await invoicesStore.createInvoice(
        productData as Omit<Invoice, 'id' | 'created_at' | 'updated_at'>,
      )
    }

    if (result.success) {
      // Emit success para que el padre reciba los datos
      emit('success', result.data)

      // Reset form
      form.value = {
        invoice_number: '',
        description: '',
        customer: '',
        user: '',
        issued_date: '',
        due_date: '',
        total_amount: '',
        status: '',
      }


      // Avisar al padre que cierre el modal
      emit('close')
    } else {
      console.error('Operation failed', result)
    }
  } catch (error) {
    console.error('Error al procesar usuario:', error)
  }
}

const handleCancel = () => {
  // Reset form
  form.value = {
    invoice_number: '',
    description: '',
    customer: '',
    user: '',
    issued_date: '',
    due_date: '',
    total_amount: '',
    status: '',
    }

  // Emitir close para que el padre cierre el diálogo
  emit('close')
}
</script>

<style scoped>
/* (mismo CSS que tenías, lo conservé tal cual) */
.dialog-user-container {
  padding: 1rem;
  max-width: 100%;
}
.customer-form {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1rem;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}
.form-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.25rem;
}
.form-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}
.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}
.cancel-btn {
  color: #6b7280;
}
.submit-btn {
  background-color: #3b82f6;
  border-color: #3b82f6;
}
.submit-btn:hover {
  background-color: #2563eb;
  border-color: #2563eb;
}

/* Estilos para errores */
.p-error {
  color: #dc2626;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.p-invalid {
  border-color: #dc2626 !important;
}

.p-invalid:focus {
  border-color: #dc2626 !important;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

/* Responsive */
@media (max-width: 640px) {
  .dialog-customer-container {
    padding: 0.75rem;
  }
  .user-form {
    gap: 0.5rem;
  }
  .form-label {
    font-size: 0.8125rem;
  }
  .form-input {
    padding: 0.375rem;
    font-size: 0.8125rem;
  }
}
</style>
