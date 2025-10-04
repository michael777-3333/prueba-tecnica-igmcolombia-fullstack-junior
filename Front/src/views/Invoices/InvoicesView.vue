<template>
  <div class="products-container">
    <!-- Header -->
    <div class="page-header">
      <div class="header-content">
        <h1 class="page-title">Facturas</h1>
        <p class="page-subtitle">Gestiona todos las facturas del sistema</p>
      </div>
      <Button
        label="Nueva Factura"
        icon="pi pi-plus"
        @click="showCreateInvoiceDialog"
        class="create-btn"
      />
    </div>

    <!-- DataTable -->
    <DataTable
      :value="invoicesStore.invoices"
      :loading="invoicesStore.loading"
      :paginator="true"
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
      currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} productos"
      :globalFilterFields="['name', 'quantity', 'price', 'total', 'iva']"
      :emptyMessage="invoicesStore.error || 'No hay facturas registradas'"
      class="products-table"
      responsiveLayout="scroll"
    >
      <!-- Header con búsqueda -->
      <template #header>
        <div class="table-header">
          <div class="search-container">
            <i class="pi pi-search search-icon"></i>
            <InputText
              v-model="globalFilter"
              placeholder="Buscar productos..."
              class="search-input"
            />
          </div>
        </div>
      </template>

      <!-- Columna Avatar -->


      <Column header="Número de factura" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-box date-icon"></i>
            <span>{{ slotProps.data.invoice_number || 'Sin número' }}</span>
          </div>
        </template>
      </Column>

      <Column header="Descripción" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-clipboard date-icon" ></i>
            <span>{{ slotProps.data.description || 'Sin descripción' }}</span>
          </div>
        </template>
      </Column>

     <Column header="Cliente" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-user date-icon"></i>
            <span>{{ getCustomerName(slotProps.data.customer) }}</span>
          </div>
        </template>
      </Column>
      <Column header="Usuario" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-user date-icon"></i>
            <span>{{ getUserName(slotProps.data.user) }}</span>
          </div>
        </template>
      </Column>


      <!-- Columna Fecha de creación -->
      <Column header="Fecha de emisión" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-calendar date-icon"></i>
            <span>{{ slotProps.data.issued_date || 'Sin fecha' }}</span>
          </div>
        </template>
      </Column>

      <Column header="Fecha de vencimiento" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-calendar date-icon"></i>
            <span>{{ slotProps.data.due_date ? formatDate(slotProps.data.due_date) : 'Sin fecha' }}</span>
          </div>
        </template>
      </Column>

      <Column header="Total" style="min-width: 150px">
        <template #body="slotProps">
          <div class="amount-cell">
            <i class="pi pi-money-bill amount-icon"></i>
            <span class="amount-value">{{ slotProps.data.total_amount ? formatCurrency(slotProps.data.total_amount) : '$0' }}</span>
          </div>
        </template>
      </Column>
      <Column header="Estado" style="min-width: 150px">
        <template #body="slotProps">
          <div class="status-cell">
            <Tag
              :value="getStatusLabel(slotProps.data.status || 'pending')"
              :severity="getStatusSeverity(slotProps.data.status || 'pending')"
              :icon="getStatusIcon(slotProps.data.status || 'pending')"
            />
          </div>
        </template>
      </Column>

      <!-- Columna de acciones -->
      <Column header="Acciones" style="min-width: 140px" :exportable="false">
        <template #body="slotProps">
          <div class="actions-cell">
            <Button
              icon="pi pi-pencil"
              class="p-button-rounded p-button-text p-button-sm edit-btn"
              @click="editInvoice(slotProps.data)"
              v-tooltip.top="'Editar factura'"
            />
            <Button
              icon="pi pi-eye"
              class="p-button-rounded p-button-text p-button-sm details-btn"
              @click="detailsInvoice(slotProps.data)"
              v-tooltip.top="'Ver detalles completos'"
            />
          </div>
        </template>
      </Column>
    </DataTable>

    <!-- Modal para crear/editar cliente -->
    <Dialog
      v-model:visible="showDialog"
      modal
      :header="isEditMode ? 'Editar Factura' : 'Crear Factura'"
      :style="{ width: '50vw' }"
      @hide="handleDialogClose"
    >
      <DialogInvoices
        v-if="showDialog"
        :key="selectedInvoice?.id || 'new'"
        :invoiceData="selectedInvoice"
        :isEditMode="isEditMode"
        @success="handleDialogSuccess"
        @close="handleDialogClose"
      />
    </Dialog>

    <DetailsInvoices
      v-model:visible="showDetailsDialog"
      modal
      :header="selectedInvoice ? 'Detalles de la factura' : 'Detalles de la factura'"
      :style="{ width: '40vw' }"
      :invoiceData="selectedInvoice ? selectedInvoice : null"
      @success="handleDialogSuccess"
      @close="handleDialogClose"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import DialogInvoices from '@/views/Invoices/components/SimpleDialogInvoices.vue'
import DetailsInvoices from '@/views/Invoices/components/DetailsInvoices.vue'
// PrimeVue Components
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Avatar from 'primevue/avatar'
import Dialog from 'primevue/dialog'
import Tag from 'primevue/tag'
import { useInvoicesStore } from '@/stores/invoices.store'
import type { Invoice } from './interfaces/invoices.interfaces'
const invoicesStore = useInvoicesStore()
const router = useRouter()

const globalFilter = ref('')

const showDialog = ref(false)
const isEditMode = ref(false)
const selectedInvoice = ref<Invoice | null>(null)
const showDetailsDialog = ref(false)

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const getStatusLabel = (status: string) => {
  const statusMap: Record<string, string> = {
    pending: 'Pendiente',
    paid: 'Pagado',
    overdue: 'Vencido',
    cancelled: 'Cancelado'
  }
  return statusMap[status] || status
}

const getStatusSeverity = (status: string) => {
  const severityMap: Record<string, string> = {
    pending: 'warning',
    paid: 'success',
    overdue: 'danger',
    cancelled: 'secondary'
  }
  return severityMap[status] || 'info'
}

const getStatusIcon = (status: string) => {
  const iconMap: Record<string, string> = {
    pending: 'pi pi-clock',
    paid: 'pi pi-check-circle',
    overdue: 'pi pi-exclamation-triangle',
    cancelled: 'pi pi-times-circle'
  }
  return iconMap[status] || 'pi pi-info-circle'
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount)
}

const getCustomerName = (customer: any) => {
  if (!customer) return 'Sin cliente'
  if (customer.first_name && customer.last_name) {
    return `${customer.first_name} ${customer.last_name}`
  }
  if (customer.name) {
    return customer.name
  }
  return 'Cliente desconocido'
}

const getUserName = (user: any) => {
  if (!user) return 'Sin usuario'
  if (user.name) {
    return user.name
  }
  if (user.first_name && user.last_name) {
    return `${user.first_name} ${user.last_name}`
  }
  return 'Usuario desconocido'
}

const showCreateInvoiceDialog = () => {
  router.push('/generate-invoices')
}

const editInvoice = (invoice: Invoice) => {
  selectedInvoice.value = invoice
  isEditMode.value = true
  showDialog.value = true
  console.log('editInvoice', invoice)
}


const handleDialogClose = () => {
  showDialog.value = false
  selectedInvoice.value = null
  isEditMode.value = false
}

const handleDialogSuccess = (data: unknown) => {
  console.log('handleDialogSuccess', data)

  // Cerrar el modal inmediatamente
  showDialog.value = false
  selectedInvoice.value = null
  isEditMode.value = false

  // Actualizar la lista después
  invoicesStore.fetchInvoices().catch(error => {
    console.error('Error al actualizar facturas:', error)
  })
}

const detailsInvoice = (invoice: Invoice) => {
  console.log('detailsInvoice', invoice)
  selectedInvoice.value = invoice
  showDetailsDialog.value = true
}

onMounted(() => {
  invoicesStore.fetchInvoices().then(() => {
    console.log('Facturas cargadas:', invoicesStore.invoices)
    // Verificar la estructura de los datos
    if (invoicesStore.invoices.length > 0) {
      console.log('Primera factura:', invoicesStore.invoices[0])
      console.log('Customer de primera factura:', invoicesStore.invoices[0].customer)
    }
  })
})
</script>

<style scoped>
/* Container principal */
.customers-container {
  padding: 8px;
  background: #f8fafc;
  min-height: 100vh;
}

/* Header de la página */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 8px;
  padding: 12px;
  background: white;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-content {
  flex: 1;
}

.page-title {
  font-size: 18px;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 4px 0;
}

.page-subtitle {
  font-size: 12px;
  color: #64748b;
  margin: 0;
}

.create-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 4px;
  padding: 8px 16px;
  font-weight: 600;
  font-size: 13px;
  box-shadow: 0 1px 4px rgba(102, 126, 234, 0.3);
  transition: all 0.2s ease;
}

.create-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* DataTable */
.products-table {
  background: white;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

/* Header de la tabla */
.table-header {
  padding: 12px 16px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.search-container {
  position: relative;
  max-width: 250px;
}

.search-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 12px;
}

.search-input {
  width: 100%;
  padding: 8px 8px 8px 32px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  background: white;
  font-size: 13px;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Celdas de usuario */
.product-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}

.product-avatar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
}

.product-info {
  display: flex;
  flex-direction: column;
}

.product-name {
  font-weight: 600;
  color: #1e293b;
  font-size: 13px;
  margin-bottom: 1px;
}

.product-email {
  font-size: 11px;
  color: #64748b;
}

/* Celdas de fecha */
.date-cell {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #64748b;
  font-size: 12px;
}

.date-icon {
  color: #94a3b8;
  font-size: 10px;
}

/* Celda de estado */
.status-cell {
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Celda de cantidad */
.amount-cell {
  display: flex;
  align-items: center;
  gap: 6px;
  justify-content: flex-end;
}

.amount-icon {
  color: #10b981;
  font-size: 12px;
}

.amount-value {
  font-weight: 600;
  color: #10b981;
  font-size: 13px;
}

/* Celdas de acciones */
.actions-cell {
  display: flex;
  gap: 6px;
}

.edit-btn {
  color: #3b82f6;
}

.edit-btn:hover {
  background: #dbeafe;
  color: #1d4ed8;
}

.details-btn {
  color: #10b981;
}

.details-btn:hover {
  background: #d1fae5;
  color: #047857;
}

.delete-btn {
  color: #ef4444;
}

.delete-btn:hover {
  background: #fef2f2;
  color: #dc2626;
}

/* Modal */
.product-modal {
  border-radius: 12px;
}

.product-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-weight: 600;
  color: #374151;
  font-size: 14px;
}

.form-input {
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 12px;
  font-size: 14px;
  transition: all 0.2s ease;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.cancel-btn {
  color: #64748b;
}

.submit-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 8px;
  font-weight: 600;
}

.submit-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
  .products-container {
    padding: 16px;
  }

  .page-header {
    flex-direction: column;
    gap: 16px;
    align-items: stretch;
  }

  .page-title {
    font-size: 24px;
  }

  .create-btn {
    width: 100%;
    justify-content: center;
  }

  .table-header {
    padding: 12px 16px;
  }

  .search-container {
    max-width: none;
  }
}

/* Personalización de PrimeVue DataTable */
:deep(.p-datatable) {
  border-radius: 12px;
  overflow: hidden;
}

:deep(.p-datatable-header) {
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  padding: 16px 24px;
}

:deep(.p-datatable-thead > tr > th) {
  background: #f8fafc;
  color: #374151;
  font-weight: 600;
  font-size: 14px;
  border-bottom: 1px solid #e2e8f0;
  padding: 16px 24px;
}

:deep(.p-datatable-tbody > tr > td) {
  padding: 16px 24px;
  border-bottom: 1px solid #f1f5f9;
}

:deep(.p-datatable-tbody > tr:hover) {
  background: #f8fafc;
}

:deep(.p-paginator) {
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  padding: 16px 24px;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page) {
  border-radius: 6px;
  margin: 0 2px;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page.p-highlight) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: transparent;
}

:deep(.p-datatable .p-datatable-loading-overlay) {
  background: rgba(255, 255, 255, 0.8);
}

:deep(.p-datatable .p-datatable-loading-icon) {
  color: #667eea;
}
</style>
