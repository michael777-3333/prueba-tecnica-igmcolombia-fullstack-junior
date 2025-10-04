<template>
  <div class="create-client-dialog">
    <form @submit.prevent="handleSubmit" class="client-form">
      <div class="form-group">
        <label for="name" class="form-label">Nombre *</label>
        <InputText
          id="name"
          v-model="form.name"
          @input="clearError('name')"
          placeholder="Nombre del cliente"
          :class="['form-input', { 'p-invalid': errors.name }]"
          required
        />
        <small v-if="errors.name" class="p-error">{{ errors.name }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email *</label>
        <InputText
          id="email"
          v-model="form.email"
          @input="clearError('email')"
          type="email"
          placeholder="cliente@ejemplo.com"
          :class="['form-input', { 'p-invalid': errors.email }]"
          required
        />
        <small v-if="errors.email" class="p-error">{{ errors.email }}</small>
      </div>

      <div class="form-group">
        <label for="phone" class="form-label">Teléfono</label>
        <InputText
          id="phone"
          v-model="form.phone"
          @input="clearError('phone')"
          placeholder="+34 123 456 789"
          :class="['form-input', { 'p-invalid': errors.phone }]"
        />
        <small v-if="errors.phone" class="p-error">{{ errors.phone }}</small>
      </div>

      <div class="form-group">
        <label for="address" class="form-label">Dirección</label>
        <Textarea
          id="address"
          v-model="form.address"
          @input="clearError('address')"
          placeholder="Dirección completa del cliente..."
          :class="['form-input', { 'p-invalid': errors.address }]"
          rows="3"
        />
        <small v-if="errors.address" class="p-error">{{ errors.address }}</small>
      </div>
    </form>

    <div class="dialog-footer">
      <Button
        label="Cancelar"
        icon="pi pi-times"
        @click="handleCancel"
        class="p-button-text cancel-btn"
      />
      <Button
        label="Crear Cliente"
        icon="pi pi-check"
        @click="handleSubmit"
        :loading="catalogStore.loading"
        class="create-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useCatalogStore } from '@/stores/catalog.store'
import { useAlert } from '@/composables/useAlert'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'

const catalogStore = useCatalogStore()
const { success, error } = useAlert()

// Emits
const emit = defineEmits<{
  (e: 'success', client: { id: number; name: string; email: string }): void
  (e: 'close'): void
}>()

// Estado del formulario
const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
})

// Errores de validación
const errors = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
})

// Funciones de validación
const validateForm = () => {
  errors.value = {
    name: '',
    email: '',
    phone: '',
    address: '',
  }

  let isValid = true

  // Validar nombre
  if (!form.value.name.trim()) {
    errors.value.name = 'El nombre es requerido'
    isValid = false
  } else if (form.value.name.trim().length < 2) {
    errors.value.name = 'El nombre debe tener al menos 2 caracteres'
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

  // Validar teléfono (opcional pero si se proporciona debe ser válido)
  if (form.value.phone.trim()) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{7,}$/
    if (!phoneRegex.test(form.value.phone)) {
      errors.value.phone = 'El teléfono no es válido'
      isValid = false
    }
  }

  return isValid
}

const clearError = (field: keyof typeof errors.value) => {
  if (errors.value[field]) {
    errors.value[field] = ''
  }
}

const handleSubmit = async () => {
  if (!validateForm()) return

  try {
    // Split name into first_name and last_name
    const nameParts = form.value.name.trim().split(' ')
    const first_name = nameParts[0] || ''
    const last_name = nameParts.slice(1).join(' ') || ''

    const result = await catalogStore.createClient({
      first_name,
      last_name,
      email: form.value.email.trim(),
      phone: form.value.phone.trim() || undefined,
      address: form.value.address.trim() || undefined,
    })

    if (result.success) {
      // Emitir success con el cliente creado
      emit('success', result.data)

      // Reset form
      form.value = {
        name: '',
        email: '',
        phone: '',
        address: '',
      }

      success('Cliente creado exitosamente')
    } else {
      error('Error al crear cliente: ' + result.error)
    }
  } catch (err) {
    console.error('Error al crear cliente:', err)
    error('Error inesperado al crear cliente')
  }
}

const handleCancel = () => {
  // Reset form
  form.value = {
    name: '',
    email: '',
    phone: '',
    address: '',
  }

  // Emitir close
  emit('close')
}
</script>

<style scoped>
.create-client-dialog {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.client-form {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
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
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: border-color 0.2s ease;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.p-invalid {
  border-color: #dc2626 !important;
}

.p-invalid:focus {
  border-color: #dc2626 !important;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.p-error {
  color: #dc2626;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.dialog-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}

.cancel-btn {
  color: #6b7280;
}

.create-btn {
  background-color: #10b981;
  border-color: #10b981;
}

.create-btn:hover {
  background-color: #059669;
  border-color: #059669;
}

/* Responsive */
@media (max-width: 640px) {
  .dialog-footer {
    flex-direction: column;
  }

  .dialog-footer .p-button {
    width: 100%;
  }
}
</style>
