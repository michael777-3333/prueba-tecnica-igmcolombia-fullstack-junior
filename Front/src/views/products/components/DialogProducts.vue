<template>
  <div class="dialog-product-container">
    <form @submit.prevent="handleSubmit" class="product-form">
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
        <label for="last_name" class="form-label">Cantidad *</label>
        <InputText
          id="quantity"
          v-model="form.quantity"
          @input="clearError('quantity')"
          placeholder="Ingresa el apellido"
          :class="['form-input', { 'p-invalid': errors.quantity }]"
          required
        />
        <small v-if="errors.quantity" class="p-error">{{ errors.quantity }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Precio *</label>
        <InputText
          id="price"
          v-model="form.price"
          @input="clearError('price')"
          type="price"
          placeholder="Ingresa el precio"
          :class="['form-input', { 'p-invalid': errors.price }]"
          required
        />
        <small v-if="errors.price" class="p-error">{{ errors.price }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Total *</label>
        <InputText
          id="total"
          v-model="form.total"
          @input="clearError('total')"
            type="total"
            placeholder="Ingresa el total"
            :class="['form-input', { 'p-invalid': errors.total }]"
            required
          />
          <small v-if="errors.total" class="p-error">{{ errors.total }}</small>
        </div>
        <div class="form-group">
        <label for="email" class="form-label">IVA *</label>
        <InputText
          id="iva"
          v-model="form.iva"
          @input="clearError('iva')"
            type="iva"
            placeholder="Ingresa el documento"
            :class="['form-input', { 'p-invalid': errors.iva }]"
            required
          />
          <small v-if="errors.document" class="p-error">{{ errors.document }}</small>
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
        :label="productsStore.loading ? 'Guardando...' : isEditMode ? 'Actualizar' : 'Crear'"
        :icon="isEditMode ? 'pi pi-check' : 'pi pi-plus'"
        @click="handleSubmit"
        :loading="productsStore.loading"
        class="submit-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useProductsStore } from '@/stores/products.store'
import type { CreateProductData, UpdateProductData, Product } from '@/views/products/interfaces/products.interface'
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
    productData?: UpdateProductData | null
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

const productsStore = useProductsStore()

/** Form reactive */
const form = ref({
  name: '',
  quantity: '',
  price: '',
  total: '',
  iva: '',
})

/** Errores de validación */
const errors = ref({
  name: '',
  quantity: '',
  price: '',
  total: '',
  iva: '',
})

/** Inicializa / resetea el formulario cuando cambian las props */
watch(
  () => [props.productData, props.isEditMode],
  ([productData, isEditMode]) => {
    if (productData && isEditMode && productData !== null && typeof productData === 'object') {
      const product = productData as UpdateProductData
      form.value = {
        name: product.name ?? '',
        quantity: product.quantity ?? '',
        price: product.price ?? '',
        total: product.total ?? '',
        iva: product.iva ?? '',
      }
    } else {
      form.value = {
        name: '',
        quantity: '',
        price: '',
        total: '',
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
    quantity: '',
    price: '',
    total: '',
    iva: ''
  }

  let isValid = true
  console.log('form.value', form.value);

  // Validar nombre
  if (!form.value.name.trim()) {
    errors.value.name = 'El nombre es requerido'
    isValid = false
  } else if (form.value.name.trim().length < 2) {
    errors.value.name = 'El nombre debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar email
  if (String(form.value.quantity).trim() === '') {
    errors.value.quantity = 'La cantidad es requerida'
    isValid = false
  }

  // Validar apellido
  if (!form.value.price.trim()) {
    errors.value.price = 'El precio es requerido'
    isValid = false
  } else if (form.value.price.trim().length < 2) {
    errors.value.price = 'El precio debe tener al menos 2 caracteres'
    isValid = false
  }

  // Validar celular
  if (!form.value.total.trim()) {
    errors.value.total = 'El total es requerido'
    isValid = false
  }

  // Validar documento
  if (!form.value.iva.trim()) {
    errors.value.iva = 'El IVA es requerido'
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
        name: form.value.name,
        quantity: form.value.quantity,
        price: form.value.price,
        total: form.value.total,
        iva: form.value.iva,
      }
      result = await productsStore.updateProduct(props.productData.id, updateData)
    } else {
      // Modo creación
      const productData: CreateProductData = {
        name: form.value.name,
        quantity: form.value.quantity,
        price: form.value.price,
        total: form.value.total,
        iva: form.value.iva,
      }
      result = await productsStore.createProduct(
        productData as Omit<Product, 'id' | 'created_at' | 'updated_at'>,
      )
    }

    if (result.success) {
      // Emit success para que el padre reciba los datos
      emit('success', result.data)

      // Reset form
      form.value = {
        name: '',
        quantity: '',
        price: '',
        total: '',
        iva: '',
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
    name: '',
    quantity: '',
    price: '',
    total: '',
    iva: '',
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
