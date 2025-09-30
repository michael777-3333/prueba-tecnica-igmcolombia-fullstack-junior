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
          @click="showCreateModal = true"
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
        <Column field="position" header="Posición" style="min-width: 150px">
          <template #body="slotProps">
            <Tag 
              :value="slotProps.data.position || 'Sin posición'" 
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
        v-model:visible="showCreateModal" 
        :header="showEditModal ? 'Editar Usuario' : 'Crear Usuario'"
        :modal="true" 
        :style="{ width: '450px' }"
        class="user-modal"
      >
        <form @submit.prevent="handleSubmit" class="user-form">
          <div class="form-group">
            <label for="name" class="form-label">Nombre *</label>
            <InputText 
              id="name"
              v-model="form.name" 
              placeholder="Ingresa el nombre completo"
              class="form-input"
              required
            />
          </div>

          <div class="form-group">
            <label for="email" class="form-label">Email *</label>
            <InputText 
              id="email"
              v-model="form.email" 
              type="email"
              placeholder="usuario@ejemplo.com"
              class="form-input"
              required
            />
          </div>

          <div class="form-group">
            <label for="position" class="form-label">Posición</label>
            <InputText 
              id="position"
              v-model="form.position" 
              placeholder="Ej: Desarrollador, Administrador"
              class="form-input"
            />
          </div>

          <div v-if="!showEditModal" class="form-group">
            <label for="password" class="form-label">Contraseña *</label>
            <Password 
              id="password"
              v-model="form.password" 
              placeholder="Mínimo 8 caracteres"
              :feedback="false"
              toggleMask
              class="form-input"
              required
            />
          </div>
        </form>

        <template #footer>
          <div class="modal-footer">
            <Button 
              label="Cancelar" 
              icon="pi pi-times" 
              @click="closeModal" 
              class="p-button-text cancel-btn"
            />
            <Button 
              :label="usersStore.loading ? 'Guardando...' : (showEditModal ? 'Actualizar' : 'Crear')"
              :icon="showEditModal ? 'pi pi-check' : 'pi pi-plus'"
              @click="handleSubmit" 
              :loading="usersStore.loading"
              class="submit-btn"
            />
          </div>
        </template>
      </Dialog>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useUsersStore } from '@/stores/users.store'
import type { User } from '@/views/users/interfaces/user.interface'

// PrimeVue Components
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Dialog from 'primevue/dialog'
import Avatar from 'primevue/avatar'
import Tag from 'primevue/tag'
import Password from 'primevue/password'
import Tooltip from 'primevue/tooltip'

const usersStore = useUsersStore()

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingUser = ref<User | null>(null)
const globalFilter = ref('')

const form = reactive({
  name: '',
  email: '',
  position: '',
  password: ''
})

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const editUser = (user: User) => {
  editingUser.value = user
  form.name = user.name
  form.email = user.email
  form.position = user.position || ''
  form.password = ''
  showEditModal.value = true
}

const deleteUser = async (user: User) => {
  if (confirm(`¿Estás seguro de que quieres eliminar a ${user.name}?`)) {
    const result = await usersStore.deleteUser(user.id)
    if (result.success) {
      // Usuario eliminado exitosamente
    }
  }
}

const handleSubmit = async () => {
  if (showEditModal.value && editingUser.value) {
    const result = await usersStore.updateUser(editingUser.value.id, {
      name: form.name,
      email: form.email,
      position: form.position
    })
    if (result.success) {
      closeModal()
    }
  } else {
    const result = await usersStore.createUser({
      name: form.name,
      email: form.email,
      position: form.position,
      password: form.password
    })
    if (result.success) {
      closeModal()
    }
  }
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  editingUser.value = null
  form.name = ''
  form.email = ''
  form.position = ''
  form.password = ''
}

onMounted(() => {
  usersStore.fetchUsers()
})
</script>

<style scoped>
/* Container principal */
.users-container {
  padding: 24px;
  background: #f8fafc;
  min-height: 100vh;
}

/* Header de la página */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 24px;
  padding: 24px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-content {
  flex: 1;
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 8px 0;
}

.page-subtitle {
  font-size: 14px;
  color: #64748b;
  margin: 0;
}

.create-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 8px;
  padding: 12px 24px;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  transition: all 0.2s ease;
}

.create-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* DataTable */
.users-table {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

/* Header de la tabla */
.table-header {
  padding: 16px 24px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.search-container {
  position: relative;
  max-width: 300px;
}

.search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 14px;
}

.search-input {
  width: 100%;
  padding: 12px 12px 12px 40px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background: white;
  font-size: 14px;
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
  gap: 12px;
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
  font-size: 14px;
  margin-bottom: 2px;
}

.user-email {
  font-size: 12px;
  color: #64748b;
}

/* Celdas de fecha */
.date-cell {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #64748b;
  font-size: 14px;
}

.date-icon {
  color: #94a3b8;
  font-size: 12px;
}

/* Celdas de acciones */
.actions-cell {
  display: flex;
  gap: 8px;
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
