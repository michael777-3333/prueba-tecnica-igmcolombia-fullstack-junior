<template>
  <div class="dialog-user-container">
    <form @submit.prevent="handleSubmit" class="user-form">
      <div class="form-group">
        <label for="name" class="form-label">Nombre *</label>
        <InputText
          id="name"
          v-model="form.name"
          @input="clearError('name')"
          placeholder="Ingresa el nombre"
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
          placeholder="Ingresa el email"
          :class="['form-input', { 'p-invalid': errors.email }]"
          required
        />
        <small v-if="errors.email" class="p-error">{{ errors.email }}</small>
      </div>

      <div v-if="!isEditMode" class="form-group">
        <label for="password" class="form-label">Contraseña *</label>
        <Password
          id="password"
          v-model="form.password"
          @input="clearError('password')"
          placeholder="Mínimo 8 caracteres"
          :feedback="false"
          toggleMask
          :class="['form-input', { 'p-invalid': errors.password }]"
          required
        />
        <small v-if="errors.password" class="p-error">{{ errors.password }}</small>
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
        :label="usersStore.loading ? 'Guardando...' : isEditMode ? 'Actualizar' : 'Crear'"
        :icon="isEditMode ? 'pi pi-check' : 'pi pi-plus'"
        @click="handleSubmit"
        :loading="usersStore.loading"
        class="submit-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useUsersStore } from '@/stores/users.store'
import type { CreateUserData, UpdateUserData, User } from '@/views/users/interfaces/user.interface'
import Password from 'primevue/password'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'

/**
 * Props estándar (receive from parent)
 * - userData: datos cuando es edición (puede ser null)
 * - isEditMode: indica si estamos editando o creando
 */
const props = withDefaults(
  defineProps<{
    userData?: UpdateUserData | null
    isEditMode?: boolean
  }>(),
  {
    userData: null,
    isEditMode: false,
  },
)

/** Emits */
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'success', data: unknown): void
}>()

const usersStore = useUsersStore()

/** Form reactive */
const form = ref({
  name: '',
  email: '',
  password: '',
})

/** Errores de validación */
const errors = ref({
  name: '',
  email: '',
  password: '',
})

/** Inicializa / resetea el formulario cuando cambian las props */
watch(
  () => [props.userData, props.isEditMode],
  ([userData, isEditMode]) => {
    if (userData && isEditMode && userData !== null && typeof userData === 'object') {
      const user = userData as UpdateUserData
      form.value = {
        name: user.name ?? '',
        email: user.email ?? '',
        password: '',
      }
    } else {
      form.value = {
        name: '',
        email: '',
        password: '',
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
    email: '',
    password: '',
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

  // Validar contraseña (solo en modo creación)
  if (!props.isEditMode) {
    if (!form.value.password) {
      errors.value.password = 'La contraseña es requerida'
      isValid = false
    } else if (form.value.password.length < 8) {
      errors.value.password = 'La contraseña debe tener al menos 8 caracteres'
      isValid = false
    }
  }

  return isValid
}

/** Limpiar error específico cuando el usuario empiece a escribir */
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

    if (props.isEditMode && props.userData) {
      // Modo edición
      const updateData = {
        name: form.value.name,
        email: form.value.email,
      }
      result = await usersStore.updateUser(props.userData.id, updateData)
    } else {
      // Modo creación
      const userData: CreateUserData = {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
      }
      result = await usersStore.createUser(
        userData as Omit<User, 'id' | 'created_at' | 'updated_at'>,
      )
    }

    if (result.success) {
      // Emit success para que el padre reciba los datos
      emit('success', result.data)

      // Reset form
      form.value = {
        name: '',
        email: '',
        password: '',
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
    name: '',
    email: '',
    password: '',
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
.user-form {
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
  .dialog-user-container {
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
