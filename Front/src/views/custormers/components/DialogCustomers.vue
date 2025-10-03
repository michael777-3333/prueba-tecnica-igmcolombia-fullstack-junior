<template>
  <div class="dialog-customer-container">
    <form @submit.prevent="handleSubmit" class="customer-form">
      <div class="form-group">
        <label for="name" class="form-label">Nombre *</label>
        <InputText
          id="first_name"
          v-model="form.first_name"
          @input="clearError('first_name')"
          placeholder="Ingresa el nombre"
          :class="['form-input', { 'p-invalid': errors.name }]"
          required
        />
        <small v-if="errors.first_name" class="p-error">{{ errors.first_name }}</small>
      </div>

      <div class="form-group">
        <label for="last_name" class="form-label">Apellido *</label>
        <InputText
          id="last_name"
          v-model="form.last_name"
          @input="clearError('last_name')"
          placeholder="Ingresa el apellido"
          :class="['form-input', { 'p-invalid': errors.last_name }]"
          required
        />
        <small v-if="errors.last_name" class="p-error">{{ errors.last_name }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email *</label>
        <InputText
          id="email"
          v-model="form.email"
          @input="clearError('email')"
          type="email"
          placeholder="Ingresa el email"
          :class="['form-input', { 'p-invalid': errors.email }]"
          required
        />
        <small v-if="errors.email" class="p-error">{{ errors.email }}</small>
      </div>

      <div class="form-group">
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
        <div class="form-group">
        <label for="email" class="form-label">Documento *</label>
        <InputText
          id="document"
          v-model="form.document"
          @input="clearError('document')"
            type="document"
            placeholder="Ingresa el documento"
            :class="['form-input', { 'p-invalid': errors.document }]"
            required
          />
          <small v-if="errors.document" class="p-error">{{ errors.document }}</small>
        </div>
        <div class="form-group">
        <label for="document_type" class="form-label">Tipo de documento *</label>
        <InputText
          id="document_type"
          v-model="form.document_type"
          @input="clearError('document_type')"
            type="document_type"
            placeholder="Ingresa el tipo de documento"
            :class="['form-input', { 'p-invalid': errors.document_type }]"
            required
          />
          <small v-if="errors.document_type" class="p-error">{{ errors.document_type }}</small>
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
        :label="customersStore.loading ? 'Guardando...' : isEditMode ? 'Actualizar' : 'Crear'"
        :icon="isEditMode ? 'pi pi-check' : 'pi pi-plus'"
        @click="handleSubmit"
        :loading="customersStore.loading"
        class="submit-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useCustomersStore } from '@/stores/customers'
import type { CreateCustomerData, UpdateCustomerData, Customer } from '@/views/custormers/interfaces/customer.interface'
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
    customerData?: UpdateCustomerData | null
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

const customersStore = useCustomersStore()

/** Form reactive */
const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  document: '',
  document_type: '',
})

/** Errores de validación */
const errors = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  document: '',
  document_type: '',
})

/** Inicializa / resetea el formulario cuando cambian las props */
watch(
  () => [props.userData, props.isEditMode],
  ([customerData, isEditMode]) => {
    if (customerData && isEditMode && customerData !== null && typeof customerData === 'object') {
      const customer = customerData as UpdateCustomerData
      form.value = {
        first_name: customer.first_name ?? '',
        last_name: customer.last_name ?? '',
        email: customer.email ?? '',
        phone: customer.phone ?? '',
        document: customer.document ?? '',
        document_type: customer.document_type ?? '',
      }
    } else {
      form.value = {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        document: '',
        document_type: '',
      }
    }
  },
  { immediate: true },
)

/** Función de validación */
const validateForm = () => {
  // Limpiar errores anteriores
  errors.value = {
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    document: '',
    document_type: '',
  }

  let isValid = true

  // Validar nombre
  if (!form.value.first_name.trim()) {
    errors.value.first_name = 'El nombre es requerido'
    isValid = false
  } else if (form.value.first_name.trim().length < 2) {
    errors.value.first_name = 'El nombre debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar email
  if (!form.value.email.trim()) {
    errors.value.email = 'El email es requerido'
    isValid = false
  } else {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(form.value.email)) {
      errors.value.email = 'El email no es válido'
      isValid = false
    }
  }

  // Validar apellido
  if (!form.value.last_name.trim()) {
    errors.value.last_name = 'El apellido es requerido'
    isValid = false
  } else if (form.value.last_name.trim().length < 2) {
    errors.value.last_name = 'El apellido debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar celular
  if (!form.value.phone.trim()) {
    errors.value.phone = 'El celular es requerido'
    isValid = false
  }

  // Validar documento
  if (!form.value.document.trim()) {
    errors.value.document = 'El documento es requerido'
    isValid = false
  }

  // Validar tipo de documento
  if (!form.value.document_type.trim()) {
    errors.value.document_type = 'El tipo de documento es requerido'
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

    if (props.isEditMode && props.customerData) {
      // Modo edición
      const updateData = {
        first_name: form.value.first_name,
        last_name: form.value.last_name,
        email: form.value.email,
        phone: form.value.phone,
        document: form.value.document,
        document_type: form.value.document_type,
      }
      result = await customersStore.updateCustomer(props.customerData.id, updateData)
    } else {
      // Modo creación
      const customerData: CreateCustomerData = {
        first_name: form.value.first_name,
        last_name: form.value.last_name,
        email: form.value.email,
        phone: form.value.phone,
        document: form.value.document,
        document_type: form.value.document_type,
      }
      result = await customersStore.createCustomer(
        customerData as Omit<Customer, 'id' | 'created_at' | 'updated_at'>,
      )
    }

    if (result.success) {
      // Emit success para que el padre reciba los datos
      emit('success', result.data)

      // Reset form
      form.value = {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        document: '',
        document_type: '',
      }

      // Avisar al padre que cierre el modal
      emit('close')
    } else {
      // aquí podrías manejar errores: mostrar toast, etc.
      console.error('Operation failed', result)
    }
  } catch (error) {
    console.error('Error al procesar usuario:', error)
  }
}

const handleCancel = () => {
  // Reset form
  form.value = {
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    document: '',
    document_type: '',
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
