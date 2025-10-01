<template>
  <div class="generate-invoices-container">
    <!-- Header -->
    <div class="page-header">
      <h1 class="page-title">Generar Nueva Factura</h1>
      <p class="page-subtitle">Crea una factura de forma rápida y sencilla</p>
    </div>

    <!-- Formulario de factura -->
    <div class="invoice-form">
      <div class="form-section">
        <!-- Selección de Cliente -->
        <div class="client-section">
          <h3 class="section-title">
            <i class="pi pi-user"></i>
            Cliente
          </h3>

          <div class="client-selector">
            <Dropdown
              v-model="selectedClientId"
              :options="clients"
              option-label="name"
              option-value="id"
              placeholder="Selecciona un cliente"
              :class="{ 'p-invalid': errors.clientId }"
              @change="clearError('clientId')"
              class="client-dropdown"
            >
              <template #option="{ option }">
                <div class="client-option">
                  <div class="client-name">{{ option.name }}</div>
                  <div class="client-email">{{ option.email }}</div>
                </div>
              </template>
            </Dropdown>

            <Button
              icon="pi pi-plus"
              label="Nuevo Cliente"
              @click="showCreateClientDialog"
              class="p-button-outlined add-client-btn"
            />
          </div>

          <small v-if="errors.clientId" class="p-error">{{ errors.clientId }}</small>

          <!-- Información del cliente seleccionado -->
          <div v-if="selectedClient" class="client-info">
            <div class="client-details">
              <div class="detail-item">
                <i class="pi pi-envelope"></i>
                <span>{{ selectedClient.email }}</span>
              </div>
              <div v-if="selectedClient.phone" class="detail-item">
                <i class="pi pi-phone"></i>
                <span>{{ selectedClient.phone }}</span>
              </div>
              <div v-if="selectedClient.address" class="detail-item">
                <i class="pi pi-map-marker"></i>
                <span>{{ selectedClient.address }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Productos -->
        <div class="products-section">
          <div class="section-header">
            <h3 class="section-title">
              <i class="pi pi-shopping-cart"></i>
              Productos
            </h3>
            <Button
              icon="pi pi-plus"
              label="Agregar Producto"
              @click="showAddProductDialog"
              class="add-product-btn"
            />
          </div>

          <!-- Lista de productos agregados -->
          <div v-if="invoiceItems.length > 0" class="invoice-items">
            <div v-for="(item, index) in invoiceItems" :key="index" class="invoice-item">
              <div class="item-info">
                <div class="item-name">{{ item.product_name }}</div>
                <div class="item-price">${{ item.unit_price.toFixed(2) }}</div>
              </div>

              <div class="item-controls">
                <div class="quantity-controls">
                  <Button
                    icon="pi pi-minus"
                    @click="decreaseQuantity(index)"
                    :disabled="item.quantity <= 1"
                    class="p-button-text p-button-sm"
                  />
                  <span class="quantity">{{ item.quantity }}</span>
                  <Button
                    icon="pi pi-plus"
                    @click="increaseQuantity(index)"
                    class="p-button-text p-button-sm"
                  />
                </div>

                <div class="item-total">${{ item.total.toFixed(2) }}</div>

                <Button
                  icon="pi pi-trash"
                  @click="removeItem(index)"
                  class="p-button-text p-button-sm p-button-danger"
                />
              </div>
            </div>
          </div>

          <!-- Mensaje cuando no hay productos -->
          <div v-else class="no-items">
            <i class="pi pi-shopping-cart"></i>
            <p>No hay productos agregados</p>
            <small>Haz clic en "Agregar Producto" para comenzar</small>
          </div>
        </div>

        <!-- Totales -->
        <div v-if="invoiceItems.length > 0" class="totals-section">
          <div class="totals-card">
            <div class="total-row">
              <span>Subtotal:</span>
              <span>${{ subtotal.toFixed(2) }}</span>
            </div>
            <div class="total-row">
              <span>IVA (21%):</span>
              <span>${{ tax.toFixed(2) }}</span>
            </div>
            <div class="total-row total-final">
              <span>Total:</span>
              <span>${{ total.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Notas -->
        <div class="notes-section">
          <h3 class="section-title">
            <i class="pi pi-file-edit"></i>
            Notas (Opcional)
          </h3>
          <Textarea
            v-model="invoice.notes"
            placeholder="Agrega notas adicionales para la factura..."
            rows="3"
            class="notes-textarea"
          />
        </div>

        <!-- Botones de acción -->
        <div class="form-actions">
          <Button
            label="Cancelar"
            icon="pi pi-times"
            @click="resetForm"
            class="p-button-text cancel-btn"
          />
          <Button
            label="Guardar Borrador"
            icon="pi pi-save"
            @click="saveDraft"
            :loading="invoicesStore.loading"
            class="p-button-outlined draft-btn"
          />
          <Button
            label="Generar Factura"
            icon="pi pi-check"
            @click="generateInvoice"
            :loading="invoicesStore.loading"
            :disabled="!canGenerateInvoice"
            class="generate-btn"
          />
        </div>
      </div>
    </div>

    <!-- Dialog para agregar producto -->
    <Dialog
      v-model:visible="showProductDialog"
      modal
      header="Agregar Producto"
      :style="{ width: '50vw' }"
    >
      <AddProductDialog
        :products="availableProducts"
        @add="addProductToInvoice"
        @close="showProductDialog = false"
      />
    </Dialog>

    <!-- Dialog para crear cliente -->
    <Dialog
      v-model:visible="showClientDialog"
      modal
      header="Nuevo Cliente"
      :style="{ width: '40vw' }"
    >
      <CreateClientDialog @success="handleClientCreated" @close="showClientDialog = false" />
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useInvoicesStore } from '@/stores/invoices.store'
import { useCatalogStore } from '@/stores/catalog.store'
import type {
  InvoiceItem,
  CreateInvoiceData,
} from '@/views/generate-invoices/interfaces/invoice.interface'
import { AddProductDialog, CreateClientDialog } from './components'
// PrimeVue Components
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import Dialog from 'primevue/dialog'

const invoicesStore = useInvoicesStore()
const catalogStore = useCatalogStore()

// Estado del formulario
const selectedClientId = ref<number | null>(null)
const invoiceItems = ref<InvoiceItem[]>([])
const invoice = ref({
  notes: '',
})

// Estados de los dialogs
const showProductDialog = ref(false)
const showClientDialog = ref(false)

// Errores de validación
const errors = ref({
  clientId: '',
})

// Computed
const clients = computed(() => catalogStore.clients)
const products = computed(() => catalogStore.products)
const availableProducts = computed(() => products.value.filter((product) => product.stock > 0))

const selectedClient = computed(() => {
  if (!selectedClientId.value) return null
  return clients.value.find((client) => client.id === selectedClientId.value)
})

const subtotal = computed(() => {
  return invoiceItems.value.reduce((sum, item) => sum + item.total, 0)
})

const tax = computed(() => {
  return subtotal.value * 0.21 // 21% IVA
})

const total = computed(() => {
  return subtotal.value + tax.value
})

const canGenerateInvoice = computed(() => {
  return selectedClientId.value && invoiceItems.value.length > 0
})

// Funciones de validación
const validateForm = () => {
  errors.value = { clientId: '' }
  let isValid = true

  if (!selectedClientId.value) {
    errors.value.clientId = 'Debes seleccionar un cliente'
    isValid = false
  }

  if (invoiceItems.value.length === 0) {
    // Podríamos agregar un error aquí también
    isValid = false
  }

  return isValid
}

const clearError = (field: keyof typeof errors.value) => {
  if (errors.value[field]) {
    errors.value[field] = ''
  }
}

// Funciones de productos
const showAddProductDialog = () => {
  // if (availableProducts.value.length === 0) {
  //   alert('No hay productos disponibles')
  //   return
  // }
  showProductDialog.value = true
}

const addProductToInvoice = (product: { id: number; name: string; price: number }) => {
  const existingItem = invoiceItems.value.find((item) => item.product_id === product.id)

  if (existingItem) {
    // Si ya existe, aumentar cantidad
    increaseQuantity(invoiceItems.value.indexOf(existingItem))
  } else {
    // Si no existe, agregar nuevo item
    const newItem: InvoiceItem = {
      id: Date.now(), // ID temporal
      product_id: product.id,
      product_name: product.name,
      quantity: 1,
      unit_price: product.price,
      total: product.price,
    }
    invoiceItems.value.push(newItem)
  }

  showProductDialog.value = false
}

const increaseQuantity = (index: number) => {
  const item = invoiceItems.value[index]
  if (item) {
    item.quantity += 1
    item.total = item.quantity * item.unit_price
  }
}

const decreaseQuantity = (index: number) => {
  const item = invoiceItems.value[index]
  if (item && item.quantity > 1) {
    item.quantity -= 1
    item.total = item.quantity * item.unit_price
  }
}

const removeItem = (index: number) => {
  invoiceItems.value.splice(index, 1)
}

// Funciones de cliente
const showCreateClientDialog = () => {
  showClientDialog.value = true
}

const handleClientCreated = (client: { id: number }) => {
  selectedClientId.value = client.id
  showClientDialog.value = false
}

// Funciones de factura
const saveDraft = async () => {
  if (!validateForm()) return

  const invoiceData: CreateInvoiceData = {
    client_id: selectedClientId.value!,
    items: invoiceItems.value.map((item) => ({
      product_id: item.product_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
      total: item.total,
    })),
    notes: invoice.value.notes,
  }

  const result = await invoicesStore.createInvoice(invoiceData)

  if (result.success) {
    alert('Factura guardada como borrador exitosamente')
    resetForm()
  } else {
    alert('Error al guardar la factura: ' + result.error)
  }
}

const generateInvoice = async () => {
  if (!validateForm()) return

  // Aquí podrías agregar lógica adicional para generar la factura
  // Por ejemplo, cambiar el estado a 'sent' o generar PDF

  await saveDraft()
  alert('¡Factura generada exitosamente!')
}

const resetForm = () => {
  selectedClientId.value = null
  invoiceItems.value = []
  invoice.value.notes = ''
  errors.value = { clientId: '' }
}

// Lifecycle
onMounted(async () => {
  await Promise.all([catalogStore.fetchClients(), catalogStore.fetchProducts()])
})
</script>

<style scoped>
.generate-invoices-container {
  padding: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
  text-align: center;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: #6b7280;
  font-size: 1rem;
}

.invoice-form {
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.form-section {
  padding: 1.5rem;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.125rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 1rem;
}

.section-title i {
  color: #3b82f6;
}

/* Sección de Cliente */
.client-section {
  margin-bottom: 2rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #e5e7eb;
}

.client-selector {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}

.client-dropdown {
  flex: 1;
}

.add-client-btn {
  white-space: nowrap;
}

.client-option {
  display: flex;
  flex-direction: column;
}

.client-name {
  font-weight: 500;
}

.client-email {
  font-size: 0.875rem;
  color: #6b7280;
}

.client-info {
  margin-top: 1rem;
  padding: 1rem;
  background: #f9fafb;
  border-radius: 0.5rem;
}

.client-details {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #374151;
}

.detail-item i {
  color: #6b7280;
  width: 1rem;
}

/* Sección de Productos */
.products-section {
  margin-bottom: 2rem;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.add-product-btn {
  background-color: #10b981;
  border-color: #10b981;
}

.add-product-btn:hover {
  background-color: #059669;
  border-color: #059669;
}

.invoice-items {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.invoice-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: #f9fafb;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
}

.item-info {
  flex: 1;
}

.item-name {
  font-weight: 500;
  color: #374151;
}

.item-price {
  font-size: 0.875rem;
  color: #6b7280;
}

.item-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.quantity {
  min-width: 2rem;
  text-align: center;
  font-weight: 500;
}

.item-total {
  font-weight: 600;
  color: #1f2937;
  min-width: 4rem;
  text-align: right;
}

.no-items {
  text-align: center;
  padding: 3rem 1rem;
  color: #6b7280;
}

.no-items i {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.no-items p {
  font-size: 1.125rem;
  margin-bottom: 0.5rem;
}

/* Sección de Totales */
.totals-section {
  margin-bottom: 2rem;
}

.totals-card {
  background: #f3f4f6;
  border-radius: 0.5rem;
  padding: 1.5rem;
  border: 1px solid #e5e7eb;
}

.total-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  font-size: 1rem;
}

.total-row:last-child {
  margin-bottom: 0;
}

.total-final {
  font-weight: 700;
  font-size: 1.125rem;
  color: #1f2937;
  padding-top: 0.75rem;
  border-top: 2px solid #d1d5db;
}

/* Sección de Notas */
.notes-section {
  margin-bottom: 2rem;
}

.notes-textarea {
  width: 100%;
}

/* Botones de Acción */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.cancel-btn {
  color: #6b7280;
}

.draft-btn {
  border-color: #6b7280;
  color: #6b7280;
}

.draft-btn:hover {
  background-color: #6b7280;
  color: white;
}

.generate-btn {
  background-color: #3b82f6;
  border-color: #3b82f6;
}

.generate-btn:hover {
  background-color: #2563eb;
  border-color: #2563eb;
}

.generate-btn:disabled {
  background-color: #d1d5db;
  border-color: #d1d5db;
  color: #9ca3af;
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

/* Responsive */
@media (max-width: 768px) {
  .generate-invoices-container {
    padding: 1rem;
  }

  .client-selector {
    flex-direction: column;
  }

  .item-controls {
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-end;
  }

  .form-actions {
    flex-direction: column;
  }

  .form-actions .p-button {
    width: 100%;
  }
}
</style>
