<template>
  <div class="q-pa-md">
    <!-- Header -->
    <div class="row q-mb-lg">
      <div class="col">
        <h4 class="text-h4 q-mb-sm">
          <q-icon name="people" class="q-mr-sm" />
          Gestión de Usuarios
        </h4>
        <p class="text-grey-6">Administra los usuarios de tu aplicación con herramientas modernas</p>
      </div>
    </div>

    <!-- Estadísticas -->
    <div class="row q-gutter-md q-mb-lg">
      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="bg-primary text-white">
          <q-card-section>
            <div class="row items-center">
              <div class="col">
                <div class="text-h6">{{ users.length }}</div>
                <div class="text-subtitle2">Total Usuarios</div>
              </div>
              <div class="col-auto">
                <q-icon name="people" size="2em" />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="bg-positive text-white">
          <q-card-section>
            <div class="row items-center">
              <div class="col">
                <div class="text-h6">{{ activeUsers }}</div>
                <div class="text-subtitle2">Usuarios Activos</div>
              </div>
              <div class="col-auto">
                <q-icon name="check_circle" size="2em" />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="bg-info text-white">
          <q-card-section>
            <div class="row items-center">
              <div class="col">
                <div class="text-h6">{{ newUsersToday }}</div>
                <div class="text-subtitle2">Nuevos Hoy</div>
              </div>
              <div class="col-auto">
                <q-icon name="person_add" size="2em" />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="bg-warning text-white">
          <q-card-section>
            <div class="row items-center">
              <div class="col">
                <div class="text-subtitle2">{{ lastUpdate }}</div>
                <div class="text-caption">Última Actualización</div>
              </div>
              <div class="col-auto">
                <q-icon name="schedule" size="2em" />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Panel principal -->
    <q-card>
      <q-card-section>
        <div class="row items-center q-mb-md">
          <div class="col">
            <h5 class="text-h5 q-ma-none">Lista de Usuarios</h5>
          </div>
          <div class="col-auto">
            <q-btn
              color="primary"
              icon="add"
              label="Nuevo Usuario"
              @click="showAddDialog = true"
            />
            <q-btn
              flat
              icon="refresh"
              @click="loadUsers"
              :loading="loading"
              class="q-ml-sm"
            />
          </div>
        </div>

        <!-- Barra de búsqueda y filtros -->
        <div class="row q-gutter-md q-mb-md">
          <div class="col-12 col-md-6">
            <q-input
              v-model="search"
              placeholder="Buscar usuarios..."
              outlined
              clearable
            >
              <template v-slot:prepend>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
          <div class="col-12 col-md-6">
            <div class="row q-gutter-sm">
              <q-btn
                flat
                icon="filter_list"
                label="Filtros"
                @click="showFilters = !showFilters"
              />
              <q-btn
                flat
                icon="download"
                label="Exportar"
                @click="exportData"
              />
            </div>
          </div>
        </div>

        <!-- Panel de filtros -->
        <q-slide-transition>
          <div v-show="showFilters" class="q-pa-md bg-grey-1 rounded-borders q-mb-md">
            <div class="row q-gutter-md">
              <div class="col-12 col-md-4">
                <q-input
                  v-model="filters.dateFrom"
                  label="Fecha desde"
                  type="date"
                  outlined
                />
              </div>
              <div class="col-12 col-md-4">
                <q-input
                  v-model="filters.dateTo"
                  label="Fecha hasta"
                  type="date"
                  outlined
                />
              </div>
              <div class="col-12 col-md-4">
                <q-select
                  v-model="filters.status"
                  :options="statusOptions"
                  label="Estado"
                  outlined
                  clearable
                />
              </div>
            </div>
          </div>
        </q-slide-transition>

        <!-- Tabla de usuarios -->
        <q-table
          :rows="filteredUsers"
          :columns="columns"
          :loading="loading"
          :pagination="pagination"
          @request="onRequest"
          row-key="id"
          flat
          bordered
        >
          <!-- Columna ID -->
          <template v-slot:body-cell-id="props">
            <q-td :props="props">
              <q-badge color="primary" :label="props.value" />
            </q-td>
          </template>

          <!-- Columna Nombre -->
          <template v-slot:body-cell-name="props">
            <q-td :props="props">
              <div class="row items-center">
                <q-avatar color="primary" text-color="white" class="q-mr-sm">
                  {{ props.row.name.charAt(0).toUpperCase() }}
                </q-avatar>
                <div>
                  <div class="text-weight-medium">{{ props.row.name }}</div>
                  <div class="text-caption text-grey-6">Usuario</div>
                </div>
              </div>
            </q-td>
          </template>

          <!-- Columna Email -->
          <template v-slot:body-cell-email="props">
            <q-td :props="props">
              <div class="row items-center">
                <q-icon name="email" class="q-mr-sm text-grey-6" />
                <span>{{ props.value }}</span>
              </div>
            </q-td>
          </template>

          <!-- Columna Fecha -->
          <template v-slot:body-cell-created_at="props">
            <q-td :props="props">
              <div class="row items-center">
                <q-icon name="schedule" class="q-mr-sm text-grey-6" />
                <span>{{ formatDate(props.value) }}</span>
              </div>
            </q-td>
          </template>

          <!-- Columna Estado -->
          <template v-slot:body-cell-status="props">
            <q-td :props="props">
              <q-badge
                :color="getUserStatusSeverity(props.row)"
                :label="getUserStatus(props.row)"
              />
            </q-td>
          </template>

          <!-- Columna Acciones -->
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn-group flat>
                <q-btn
                  flat
                  round
                  icon="visibility"
                  color="info"
                  @click="viewUser(props.row)"
                >
                  <q-tooltip>Ver detalles</q-tooltip>
                </q-btn>
                <q-btn
                  flat
                  round
                  icon="edit"
                  color="warning"
                  @click="editUser(props.row)"
                >
                  <q-tooltip>Editar</q-tooltip>
                </q-btn>
                <q-btn
                  flat
                  round
                  icon="delete"
                  color="negative"
                  @click="confirmDelete(props.row)"
                >
                  <q-tooltip>Eliminar</q-tooltip>
                </q-btn>
              </q-btn-group>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>

    <!-- Dialog para agregar/editar usuario -->
    <q-dialog v-model="showAddDialog" persistent>
      <q-card style="min-width: 400px">
        <q-card-section>
          <div class="text-h6">{{ editingUser ? 'Editar Usuario' : 'Nuevo Usuario' }}</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveUser" class="q-gutter-md">
            <q-input
              v-model="userForm.name"
              label="Nombre"
              outlined
              :rules="[val => !!val || 'El nombre es requerido']"
            />

            <q-input
              v-model="userForm.email"
              label="Email"
              type="email"
              outlined
              :rules="[
                val => !!val || 'El email es requerido',
                val => /.+@.+\..+/.test(val) || 'Email inválido'
              ]"
            />

            <q-input
              v-if="!editingUser"
              v-model="userForm.password"
              label="Contraseña"
              type="password"
              outlined
              :rules="[val => !!val || 'La contraseña es requerida']"
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" @click="closeDialog" />
          <q-btn
            color="primary"
            :label="editingUser ? 'Actualizar' : 'Crear'"
            @click="saveUser"
            :loading="saving"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Dialog de confirmación para eliminar -->
    <q-dialog v-model="showDeleteDialog" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="warning" color="warning" text-color="white" />
          <span class="q-ml-sm">
            ¿Estás seguro de que quieres eliminar a <strong>{{ userToDelete?.name }}</strong>?
          </span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" @click="showDeleteDialog = false" />
          <q-btn
            color="negative"
            label="Eliminar"
            @click="deleteUser"
            :loading="deleting"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { router } from '@inertiajs/vue3'

// Quasar
const $q = useQuasar()

// Props
const props = defineProps({
  users: {
    type: Array,
    default: () => []
  }
})

// Estado reactivo
const users = ref(props.users || [])
const loading = ref(false)
const saving = ref(false)
const deleting = ref(false)
const search = ref('')
const showFilters = ref(false)
const showAddDialog = ref(false)
const showDeleteDialog = ref(false)
const editingUser = ref(null)
const userToDelete = ref(null)

// Formulario de usuario
const userForm = ref({
  name: '',
  email: '',
  password: ''
})

// Filtros
const filters = ref({
  dateFrom: null,
  dateTo: null,
  status: null
})

const statusOptions = ref([
  { label: 'Todos', value: null },
  { label: 'Activos', value: 'active' },
  { label: 'Inactivos', value: 'inactive' }
])

// Configuración de la tabla
const columns = [
  {
    name: 'id',
    required: true,
    label: 'ID',
    align: 'left',
    field: 'id',
    sortable: true
  },
  {
    name: 'name',
    required: true,
    label: 'Nombre',
    align: 'left',
    field: 'name',
    sortable: true
  },
  {
    name: 'email',
    required: true,
    label: 'Email',
    align: 'left',
    field: 'email',
    sortable: true
  },
  {
    name: 'created_at',
    required: true,
    label: 'Fecha de Creación',
    align: 'left',
    field: 'created_at',
    sortable: true
  },
  {
    name: 'status',
    required: true,
    label: 'Estado',
    align: 'center',
    field: 'status',
    sortable: false
  },
  {
    name: 'actions',
    required: true,
    label: 'Acciones',
    align: 'center',
    field: 'actions',
    sortable: false
  }
]

const pagination = ref({
  sortBy: 'created_at',
  descending: true,
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0
})

// Computed properties
const filteredUsers = computed(() => {
  let filtered = users.value

  // Filtro de búsqueda
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(user =>
      user.name.toLowerCase().includes(searchLower) ||
      user.email.toLowerCase().includes(searchLower)
    )
  }

  // Filtros específicos
  if (filters.value.dateFrom) {
    filtered = filtered.filter(user =>
      new Date(user.created_at) >= new Date(filters.value.dateFrom)
    )
  }

  if (filters.value.dateTo) {
    filtered = filtered.filter(user =>
      new Date(user.created_at) <= new Date(filters.value.dateTo)
    )
  }

  if (filters.value.status) {
    filtered = filtered.filter(user => getUserStatus(user) === filters.value.status)
  }

  return filtered
})

const activeUsers = computed(() => {
  return users.value.filter(user => getUserStatus(user) === 'Activo').length
})

const newUsersToday = computed(() => {
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  return users.value.filter(user => {
    const userDate = new Date(user.created_at)
    userDate.setHours(0, 0, 0, 0)
    return userDate.getTime() === today.getTime()
  }).length
})

const lastUpdate = computed(() => {
  return new Date().toLocaleTimeString('es-ES')
})

// Métodos
const loadUsers = async () => {
  loading.value = true
  try {
    router.reload({ only: ['users'] })
    $q.notify({
      type: 'positive',
      message: 'Usuarios actualizados correctamente',
      position: 'top'
    })
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Error al cargar los usuarios',
      position: 'top'
    })
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getUserStatus = (user) => {
  const daysSinceCreation = (new Date() - new Date(user.created_at)) / (1000 * 60 * 60 * 24)
  return daysSinceCreation > 30 ? 'Activo' : 'Nuevo'
}

const getUserStatusSeverity = (user) => {
  const status = getUserStatus(user)
  return status === 'Activo' ? 'positive' : 'info'
}

const viewUser = (user) => {
  $q.dialog({
    title: 'Detalles del Usuario',
    message: `
      <div>
        <p><strong>ID:</strong> ${user.id}</p>
        <p><strong>Nombre:</strong> ${user.name}</p>
        <p><strong>Email:</strong> ${user.email}</p>
        <p><strong>Fecha de Creación:</strong> ${formatDate(user.created_at)}</p>
        <p><strong>Estado:</strong> ${getUserStatus(user)}</p>
      </div>
    `,
    html: true
  })
}

const editUser = (user) => {
  editingUser.value = user
  userForm.value = {
    name: user.name,
    email: user.email,
    password: ''
  }
  showAddDialog.value = true
}

const confirmDelete = (user) => {
  userToDelete.value = user
  showDeleteDialog.value = true
}

const deleteUser = async () => {
  deleting.value = true
  try {
    await new Promise(resolve => setTimeout(resolve, 1000)) // Simulación
    
    users.value = users.value.filter(u => u.id !== userToDelete.value.id)
    
    $q.notify({
      type: 'positive',
      message: `Usuario ${userToDelete.value.name} eliminado correctamente`,
      position: 'top'
    })
    
    showDeleteDialog.value = false
    userToDelete.value = null
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Error al eliminar el usuario',
      position: 'top'
    })
  } finally {
    deleting.value = false
  }
}

const saveUser = async () => {
  if (!userForm.value.name || !userForm.value.email) {
    $q.notify({
      type: 'warning',
      message: 'Por favor completa todos los campos requeridos',
      position: 'top'
    })
    return
  }

  saving.value = true
  try {
    await new Promise(resolve => setTimeout(resolve, 1000)) // Simulación
    
    if (editingUser.value) {
      // Actualizar usuario existente
      const index = users.value.findIndex(u => u.id === editingUser.value.id)
      if (index !== -1) {
        users.value[index] = { ...users.value[index], ...userForm.value }
      }
      
      $q.notify({
        type: 'positive',
        message: 'Usuario actualizado correctamente',
        position: 'top'
      })
    } else {
      // Crear nuevo usuario
      const newUser = {
        id: Date.now(),
        ...userForm.value,
        created_at: new Date().toISOString()
      }
      users.value.unshift(newUser)
      
      $q.notify({
        type: 'positive',
        message: 'Usuario creado correctamente',
        position: 'top'
      })
    }
    
    closeDialog()
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Error al guardar el usuario',
      position: 'top'
    })
  } finally {
    saving.value = false
  }
}

const closeDialog = () => {
  showAddDialog.value = false
  editingUser.value = null
  userForm.value = {
    name: '',
    email: '',
    password: ''
  }
}

const exportData = () => {
  $q.notify({
    type: 'info',
    message: 'Función de exportación en desarrollo',
    position: 'top'
  })
}

const onRequest = (props) => {
  const { page, rowsPerPage, sortBy, descending } = props.pagination
  
  pagination.value.page = page
  pagination.value.rowsPerPage = rowsPerPage
  pagination.value.sortBy = sortBy
  pagination.value.descending = descending
  pagination.value.rowsNumber = filteredUsers.value.length
}

// Lifecycle
onMounted(() => {
  pagination.value.rowsNumber = filteredUsers.value.length
})
</script>

<style scoped>
.q-card {
  box-shadow: 0 1px 5px rgba(0,0,0,0.2), 0 2px 2px rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12);
}
</style>