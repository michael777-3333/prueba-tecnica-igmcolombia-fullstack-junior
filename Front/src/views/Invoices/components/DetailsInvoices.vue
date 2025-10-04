<template>
  <Dialog
    v-model:visible="visible"
    modal
    :header="header"
    :style="style"
    @hide="handleClose"
    class="invoice-details-dialog"
  >
    <div class="invoice-details-container">
      <!-- Header Info -->
      <div class="dialog-header-info">
        <div class="header-left">
          <div class="header-info">
            <h2 class="dialog-title">Detalles de Factura</h2>
            <p class="invoice-number">#{{ invoice?.invoice_number || 'Cargando...' }}</p>
          </div>
        </div>

        <div class="header-right">
          <div class="status-badge" :class="statusClass">
            <i :class="statusIcon"></i>
            <span>{{ statusText }}</span>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="loading-container">
        <div class="loading-spinner">
          <i class="pi pi-spinner pi-spin"></i>
          <p>Cargando detalles de la factura...</p>
        </div>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="error-container">
        <div class="error-message">
          <i class="pi pi-exclamation-triangle"></i>
          <h3>Error al cargar la factura</h3>
          <p>{{ error }}</p>
          <Button
            label="Intentar de nuevo"
            @click="fetchInvoiceDetails"
            class="retry-btn"
          />
        </div>
      </div>

      <!-- Invoice Details -->
      <div v-else-if="invoice" class="invoice-content">
        <!-- Invoice Info Card -->
        <div class="invoice-info-card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="pi pi-file-text"></i>
              Información de la Factura
            </h3>
          </div>

          <div class="card-content">
            <div class="info-grid">
              <div class="info-item">
                <label>Número de Factura:</label>
                <span class="value">{{ invoice.invoice_number }}</span>
              </div>

              <div class="info-item">
                <label>Descripción:</label>
                <span class="value">{{ invoice.description || 'Sin descripción' }}</span>
              </div>

              <div class="info-item">
                <label>Fecha de Emisión:</label>
                <span class="value">{{ formatDate(invoice.issued_date) }}</span>
              </div>

              <div class="info-item">
                <label>Fecha de Vencimiento:</label>
                <span class="value">{{ formatDate(invoice.due_date) }}</span>
              </div>

              <div class="info-item">
                <label>Total:</label>
                <span class="value total-amount">${{ formatCurrency(invoice.total_amount) }}</span>
              </div>

              <div class="info-item">
                <label>Estado:</label>
                <span class="value">
                  <div class="status-badge inline" :class="statusClass">
                    <i :class="statusIcon"></i>
                    <span>{{ statusText }}</span>
                  </div>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer Info Card -->
        <div class="customer-info-card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="pi pi-user"></i>
              Información del Cliente
            </h3>
          </div>

          <div class="card-content">
            <div class="customer-details">
              <div class="customer-avatar">
                <Avatar
                  :label="customerInitials"
                  size="large"
                  class="customer-avatar-img"
                />
              </div>

              <div class="customer-info">
                <h4 class="customer-name">{{ customerFullName }}</h4>
                <div class="customer-details-grid">
                  <div class="detail-item">
                    <i class="pi pi-envelope"></i>
                    <span>{{ invoice.customer.email }}</span>
                  </div>

                  <div class="detail-item">
                    <i class="pi pi-phone"></i>
                    <span>{{ invoice.customer.phone }}</span>
                  </div>

                  <div class="detail-item">
                    <i class="pi pi-id-card"></i>
                    <span>{{ invoice.customer.document_type }}: {{ invoice.customer.document }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Products Card -->
        <div class="products-card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="pi pi-shopping-cart"></i>
              Productos ({{ invoice.products.length }})
            </h3>
          </div>

          <div class="card-content">
            <div v-if="invoice.products.length === 0" class="no-products">
              <i class="pi pi-shopping-cart"></i>
              <p>No hay productos en esta factura</p>
            </div>

            <div v-else class="products-table">
              <div class="table-header">
                <div class="header-cell">Producto</div>
                <div class="header-cell">Cantidad</div>
                <div class="header-cell">Precio Unit.</div>
                <div class="header-cell">IVA</div>
                <div class="header-cell">Total</div>
              </div>

              <div
                v-for="product in invoice.products"
                :key="product.id"
                class="table-row"
              >
                <div class="cell product-cell">
                  <div class="product-info">
                    <div class="product-name">{{ product.name }}</div>
                  </div>
                </div>

                <div class="cell quantity-cell">
                  <span class="quantity-badge">{{ product.quantity }}</span>
                </div>

                <div class="cell price-cell">
                  ${{ formatCurrency(product.price) }}
                </div>

                <div class="cell iva-cell">
                  {{ product.iva }}%
                </div>

                <div class="cell total-cell">
                  <span class="total-price">${{ formatCurrency(product.total) }}</span>
                </div>
              </div>
            </div>

            <!-- Totals Summary -->
            <div class="totals-summary">
              <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ formatCurrency(subtotal) }}</span>
              </div>
              <div class="total-row">
                <span>IVA Total:</span>
                <span>${{ formatCurrency(totalIva) }}</span>
              </div>
              <div class="total-row total-final">
                <span>Total:</span>
                <span>${{ formatCurrency(invoice.total_amount) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Notes Card -->
        <div v-if="invoice.notes" class="notes-card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="pi pi-file-edit"></i>
              Notas
            </h3>
          </div>

          <div class="card-content">
            <div class="notes-content">
              {{ invoice.notes }}
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="actions-section">
          <Button
            label="Imprimir"
            icon="pi pi-print"
            @click="printInvoice"
            class="action-btn print-btn"
          />
          <Button
            label="Exportar PDF"
            icon="pi pi-file-pdf"
            @click="exportToPDF"
            class="action-btn pdf-btn"
          />
          <Button
            label="Enviar por Email"
            icon="pi pi-send"
            @click="sendByEmail"
            class="action-btn email-btn"
          />
        </div>
      </div>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useInvoicesStore } from '@/stores/invoices.store'
import type { Invoice } from '@/views/Invoices/interfaces/invoices.interfaces'
import Button from 'primevue/button'
import Avatar from 'primevue/avatar'
import Dialog from 'primevue/dialog'

// Props
interface Props {
  visible: boolean
  header?: string
  style?: any
  invoiceData?: Invoice | null
}

const props = withDefaults(defineProps<Props>(), {
  header: 'Detalles de la Factura',
  style: () => ({ width: '80vw', maxWidth: '1200px' }),
  invoiceData: null
})

// Emits
const emit = defineEmits<{
  (e: 'update:visible', value: boolean): void
  (e: 'success'): void
  (e: 'close'): void
}>()

const invoicesStore = useInvoicesStore()

// Estado
const invoice = ref<Invoice | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

// Computed para v-model
const visible = computed({
  get: () => props.visible,
  set: (value: boolean) => emit('update:visible', value)
})

// Computed
const customerFullName = computed(() => {
  if (!invoice.value?.customer) return 'Cliente no encontrado'
  return `${invoice.value.customer.first_name} ${invoice.value.customer.last_name}`
})

const customerInitials = computed(() => {
  if (!invoice.value?.customer) return 'C'
  const { first_name, last_name } = invoice.value.customer
  return `${first_name.charAt(0)}${last_name.charAt(0)}`.toUpperCase()
})

const statusClass = computed(() => {
  if (!invoice.value) return ''

  const status = invoice.value.status.toLowerCase()
  switch (status) {
    case 'paid':
      return 'status-paid'
    case 'pending':
    case 'sent':
      return 'status-pending'
    case 'cancelled':
      return 'status-cancelled'
    case 'draft':
      return 'status-draft'
    default:
      return 'status-default'
  }
})

const statusIcon = computed(() => {
  if (!invoice.value) return 'pi pi-circle'

  const status = invoice.value.status.toLowerCase()
  switch (status) {
    case 'paid':
      return 'pi pi-check-circle'
    case 'pending':
    case 'sent':
      return 'pi pi-clock'
    case 'cancelled':
      return 'pi pi-times-circle'
    case 'draft':
      return 'pi pi-file-edit'
    default:
      return 'pi pi-circle'
  }
})

const statusText = computed(() => {
  if (!invoice.value) return 'Desconocido'

  const status = invoice.value.status.toLowerCase()
  switch (status) {
    case 'paid':
      return 'Pagada'
    case 'pending':
      return 'Pendiente'
    case 'sent':
      return 'Enviada'
    case 'cancelled':
      return 'Cancelada'
    case 'draft':
      return 'Borrador'
    default:
      return invoice.value.status
  }
})

const subtotal = computed(() => {
  if (!invoice.value?.products) return 0
  return invoice.value.products.reduce((sum, product) => sum + (product.price * product.quantity), 0)
})

const totalIva = computed(() => {
  if (!invoice.value?.products) return 0
  return invoice.value.products.reduce((sum, product) => {
    const ivaAmount = (product.price * product.quantity * product.iva) / 100
    return sum + ivaAmount
  }, 0)
})

// Funciones
const fetchInvoiceDetails = async () => {
  if (!props.invoiceData?.id) {
    error.value = 'Datos de factura no válidos'
    return
  }

  loading.value = true
  error.value = null

  try {
    const response = await invoicesStore.fetchInvoiceById(props.invoiceData.id)

    if (response.success && response.data) {
      invoice.value = response.data
    } else {
      error.value = response.error || 'No se pudo cargar la factura'
    }
  } catch (err) {
    console.error('Error al cargar factura:', err)
    error.value = 'Error inesperado al cargar la factura'
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  emit('close')
}

const formatDate = (dateString: string) => {
  if (!dateString) return 'N/A'

  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  } catch {
    return dateString
  }
}

const formatCurrency = (amount: number) => {
  // return amount.toLocaleString('es-ES', {
  //   minimumFractionDigits: 2,
  //   maximumFractionDigits: 2
  // })
}

const printInvoice = () => {
  window.print()
}

const exportToPDF = () => {
  // Aquí implementarías la lógica para exportar a PDF
  alert('Funcionalidad de exportar PDF en desarrollo')
}

const sendByEmail = () => {
  // Aquí implementarías la lógica para enviar por email
  alert('Funcionalidad de envío por email en desarrollo')
}

// Watchers
watch(() => props.visible, (newVisible) => {
  if (newVisible && props.invoiceData) {
    invoice.value = props.invoiceData
    // Si ya tenemos los datos del prop, no necesitamos hacer fetch
    // Solo si necesitamos datos más detallados del servidor
    // fetchInvoiceDetails()
  }
})

watch(() => props.invoiceData, (newInvoiceData) => {
  if (newInvoiceData && props.visible) {
    invoice.value = newInvoiceData
  }
})
</script>

<style scoped>
.invoice-details-dialog {
  max-height: 90vh;
}

.invoice-details-container {
  padding: 0;
  max-height: 70vh;
  overflow-y: auto;
}

/* Header */
.dialog-header-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.header-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.dialog-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.invoice-number {
  color: #6b7280;
  font-size: 0.875rem;
  margin: 0;
}

.status-badge {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 500;
  font-size: 0.875rem;
}

.status-badge.inline {
  display: inline-flex;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.status-paid {
  background: #d1fae5;
  color: #065f46;
}

.status-pending {
  background: #fef3c7;
  color: #92400e;
}

.status-cancelled {
  background: #fee2e2;
  color: #991b1b;
}

.status-draft {
  background: #e5e7eb;
  color: #374151;
}

/* Loading & Error */
.loading-container,
.error-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
}

.loading-spinner {
  text-align: center;
  color: #6b7280;
}

.loading-spinner i {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.error-message {
  text-align: center;
  color: #dc2626;
}

.error-message i {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.retry-btn {
  margin-top: 1rem;
  background-color: #dc2626;
  border-color: #dc2626;
}

/* Cards */
.invoice-info-card,
.customer-info-card,
.products-card,
.notes-card {
  background: #f9fafb;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  margin-bottom: 1rem;
  overflow: hidden;
}

.card-header {
  padding: 1rem 1rem 0;
  border-bottom: 1px solid #e5e7eb;
  margin-bottom: 1rem;
}

.card-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin: 0 0 1rem 0;
}

.card-title i {
  color: #3b82f6;
}

.card-content {
  padding: 0 1rem 1rem;
}

/* Info Grid */
.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 0.75rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.info-item label {
  font-size: 0.75rem;
  font-weight: 500;
  color: #6b7280;
}

.info-item .value {
  font-size: 0.875rem;
  color: #374151;
}

.total-amount {
  font-weight: 700;
  font-size: 1.125rem;
  color: #059669;
}

/* Customer Details */
.customer-details {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.customer-avatar-img {
  background-color: #3b82f6;
  color: white;
  font-weight: 600;
}

.customer-info {
  flex: 1;
}

.customer-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0 0 0.75rem 0;
}

.customer-details-grid {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.75rem;
  color: #374151;
}

.detail-item i {
  color: #6b7280;
  width: 1rem;
}

/* Products Table */
.no-products {
  text-align: center;
  padding: 2rem 1rem;
  color: #6b7280;
}

.no-products i {
  font-size: 2rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.products-table {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
  background: #f3f4f6;
  border-bottom: 1px solid #e5e7eb;
}

.header-cell {
  padding: 0.75rem;
  font-weight: 600;
  color: #374151;
  font-size: 0.75rem;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
  border-bottom: 1px solid #e5e7eb;
}

.table-row:last-child {
  border-bottom: none;
}

.cell {
  padding: 0.75rem;
  display: flex;
  align-items: center;
  font-size: 0.75rem;
}

.product-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.product-name {
  font-weight: 500;
  color: #374151;
}

.quantity-badge {
  background: #e5e7eb;
  color: #374151;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-weight: 500;
  font-size: 0.75rem;
}

.total-price {
  font-weight: 600;
  color: #1f2937;
}

/* Totals Summary */
.totals-summary {
  margin-top: 1rem;
  padding: 1rem;
  background: #f3f4f6;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
}

.total-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.total-row:last-child {
  margin-bottom: 0;
}

.total-final {
  font-weight: 700;
  font-size: 1rem;
  color: #1f2937;
  padding-top: 0.5rem;
  border-top: 2px solid #d1d5db;
}

/* Notes */
.notes-content {
  background: #f3f4f6;
  padding: 1rem;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  white-space: pre-wrap;
  color: #374151;
  font-size: 0.875rem;
}

/* Actions */
.actions-section {
  display: flex;
  justify-content: center;
  gap: 0.75rem;
  padding: 1rem 0;
  border-top: 1px solid #e5e7eb;
  margin-top: 1rem;
}

.action-btn {
  min-width: 120px;
  font-size: 0.875rem;
}

.print-btn {
  background-color: #6b7280;
  border-color: #6b7280;
}

.pdf-btn {
  background-color: #dc2626;
  border-color: #dc2626;
}

.email-btn {
  background-color: #059669;
  border-color: #059669;
}

/* Responsive */
@media (max-width: 768px) {
  .dialog-header-info {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .customer-details {
    flex-direction: column;
    text-align: center;
  }

  .table-header,
  .table-row {
    grid-template-columns: 1fr;
    gap: 0.5rem;
  }

  .header-cell,
  .cell {
    padding: 0.5rem;
  }

  .actions-section {
    flex-direction: column;
  }

  .action-btn {
    width: 100%;
  }
}

@media print {
  .actions-section {
    display: none;
  }

  .invoice-details-container {
    padding: 0;
  }

  .invoice-info-card,
  .customer-info-card,
  .products-card,
  .notes-card {
    box-shadow: none;
    border: 1px solid #e5e7eb;
    page-break-inside: avoid;
  }
}
</style>
