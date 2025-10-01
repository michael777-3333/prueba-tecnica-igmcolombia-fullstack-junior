<template>
  <div class="users-container">
    <!-- Header -->
    <div class="page-header">
      <div class="header-content">
        <h1 class="page-title">Usuarios</h1>
        <p class="page-subtitle">Gestiona todos los usuarios del sistema</p>
      </div>
      <Button
        label="Nuevo Usuario"
        icon="pi pi-plus"
        @click="showCreateUserDialog"
        class="create-btn"
      />
    </div>

    <!-- DataTable -->
    <DataTable
      :value="usersStore.users"
      :loading="usersStore.loading"
      :paginator="true"
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
      currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} usuarios"
      :globalFilterFields="['name', 'email', 'position']"
      :emptyMessage="usersStore.error || 'No hay usuarios registrados'"
      class="users-table"
      responsiveLayout="scroll"
    >
      <!-- Header con búsqueda -->
      <template #header>
        <div class="table-header">
          <div class="search-container">
            <i class="pi pi-search search-icon"></i>
            <InputText
              v-model="globalFilter"
              placeholder="Buscar usuarios..."
              class="search-input"
            />
          </div>
        </div>
      </template>

      <!-- Columna Avatar -->
      <Column header="Usuario" style="min-width: 200px">
        <template #body="slotProps">
          <div class="user-cell">
            <Avatar
              :label="slotProps.data.name.charAt(0).toUpperCase()"
              class="user-avatar"
              size="normal"
            />
            <div class="user-info">
              <div class="user-name">{{ slotProps.data.name }}</div>
              <div class="user-email">{{ slotProps.data.email }}</div>
            </div>
          </div>
        </template>
      </Column>

      <!-- Columna Posición -->
      <Column field="rol" header="Rol" style="min-width: 150px">
        <template #body="slotProps">
          <Tag
            :value="slotProps.data.position || 'Sin rol'"
            :severity="slotProps.data.position ? 'info' : 'secondary'"
          />
        </template>
      </Column>

      <!-- Columna Fecha de creación -->
      <Column header="Fecha de registro" style="min-width: 150px">
        <template #body="slotProps">
          <div class="date-cell">
            <i class="pi pi-calendar date-icon"></i>
            <span>{{ formatDate(slotProps.data.created_at) }}</span>
          </div>
        </template>
      </Column>

      <!-- Columna de acciones -->
      <Column header="Acciones" style="min-width: 120px" :exportable="false">
        <template #body="slotProps">
          <div class="actions-cell">
            <Button
              icon="pi pi-pencil"
              class="p-button-rounded p-button-text p-button-sm edit-btn"
              @click="editUser(slotProps.data)"
              v-tooltip.top="'Editar usuario'"
            />
            <Button
              icon="pi pi-trash"
              class="p-button-rounded p-button-text p-button-sm delete-btn"
              @click="deleteUser(slotProps.data)"
              v-tooltip.top="'Eliminar usuario'"
            />
          </div>
        </template>
      </Column>
    </DataTable>

    <!-- Modal para crear/editar usuario -->
    <Dialog
      v-model:visible="showDialog"
      modal
      :header="isEditMode ? 'Editar Usuario' : 'Crear Usuario'"
      :style="{ width: '40vw' }"
    >
      <DialogUser
        :userData="selectedUser ? { ...selectedUser, password: '' } : null"
        :isEditMode="isEditMode"
        @success="handleDialogSuccess"
        @close="handleDialogClose"
      />
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useUsersStore } from '@/stores/users.store'
import type { User } from '@/views/users/interfaces/user.interface'
import DialogUser from '@/views/users/components/DialogUser.vue'
// PrimeVue Components
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Avatar from 'primevue/avatar'
import Tag from 'primevue/tag'
import Dialog from 'primevue/dialog'
const usersStore = useUsersStore()

const globalFilter = ref('')

const showDialog = ref(false)
const isEditMode = ref(false)
const selectedUser = ref<User | null>(null)

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const showCreateUserDialog = () => {
  selectedUser.value = null
  isEditMode.value = false
  showDialog.value = true
}

const editUser = (user: User) => {
  selectedUser.value = user
  isEditMode.value = true
  showDialog.value = true
  console.log('editUser', user)
}

const deleteUser = async (user: User) => {
  if (confirm(`¿Estás seguro de que quieres eliminar a ${user.name}?`)) {
    const result = await usersStore.deleteUser(user.id)
    if (result.success) {
      // Usuario eliminado exitosamente
    }
  }
}

// Funciones para manejar eventos del DialogUser
const handleDialogClose = () => {
  showDialog.value = false
  selectedUser.value = null
  isEditMode.value = false
}

const handleDialogSuccess = (data: unknown) => {
  console.log('Usuario creado/editado exitosamente:', data)
  usersStore.fetchUsers() // Recargar la lista de usuarios
  handleDialogClose()
}

onMounted(() => {
  usersStore.fetchUsers()
})
</script>

<style scoped>
/* Container principal */
.users-container {
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
.users-table {
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
.user-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-avatar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  color: #1e293b;
  font-size: 13px;
  margin-bottom: 1px;
}

.user-email {
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

.delete-btn {
  color: #ef4444;
}

.delete-btn:hover {
  background: #fef2f2;
  color: #dc2626;
}

/* Modal */
.user-modal {
  border-radius: 12px;
}

.user-form {
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
  .users-container {
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
