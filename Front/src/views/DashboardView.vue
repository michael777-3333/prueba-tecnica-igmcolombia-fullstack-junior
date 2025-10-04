<template>
  <div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
      <div class="header-content">
        <div class="header-text">
          <h1 class="dashboard-title">
            <i class="pi pi-chart-line"></i>
            Dashboard
          </h1>
          <p class="dashboard-subtitle">Bienvenido de vuelta, aquí tienes un resumen de tu aplicación</p>
        </div>
        <div class="header-actions">
          <Button
            icon="pi pi-refresh"
            label="Actualizar"
            @click="fetchStats"
            :loading="loading"
            class="refresh-btn"
          />
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card stat-card-blue">
        <div class="stat-icon">
          <i class="pi pi-users"></i>
        </div>
        <div class="stat-content">
          <div class="stat-label">Total Usuarios</div>
          <div class="stat-value">{{ stats.totalUsers || 0 }}</div>
          <div class="stat-trend positive">
            <i class="pi pi-arrow-up"></i>
            <span>+12% este mes</span>
          </div>
        </div>
      </div>

      <div class="stat-card stat-card-green">
        <div class="stat-icon">
          <i class="pi pi-user-plus"></i>
        </div>
        <div class="stat-content">
          <div class="stat-label">Clientes</div>
          <div class="stat-value">{{ stats.totalCustomers || 0 }}</div>
          <div class="stat-trend positive">
            <i class="pi pi-arrow-up"></i>
            <span>+8% este mes</span>
          </div>
        </div>
      </div>

      <div class="stat-card stat-card-orange">
        <div class="stat-icon">
          <i class="pi pi-file-text"></i>
        </div>
        <div class="stat-content">
          <div class="stat-label">Facturas</div>
          <div class="stat-value">{{ stats.totalInvoices || 0 }}</div>
          <div class="stat-trend positive">
            <i class="pi pi-arrow-up"></i>
            <span>+15% este mes</span>
          </div>
        </div>
      </div>

      <div class="stat-card stat-card-purple">
        <div class="stat-icon">
          <i class="pi pi-calendar-plus"></i>
        </div>
        <div class="stat-content">
          <div class="stat-label">Nuevos Hoy</div>
          <div class="stat-value">{{ stats.newUsersToday || 0 }}</div>
          <div class="stat-trend neutral">
            <i class="pi pi-minus"></i>
            <span>Sin cambios</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
      <!-- Quick Actions -->
      <div class="quick-actions-section">
        <div class="section-header">
          <h2 class="section-title">
            <i class="pi pi-bolt"></i>
            Acciones Rápidas
          </h2>
          <p class="section-subtitle">Accede rápidamente a las funciones más utilizadas</p>
        </div>

        <div class="actions-grid">
          <router-link to="/users" class="action-card action-blue">
            <div class="action-icon">
              <i class="pi pi-users"></i>
            </div>
            <div class="action-content">
              <h3 class="action-title">Gestionar Usuarios</h3>
              <p class="action-description">Ver, crear, editar y eliminar usuarios del sistema</p>
            </div>
            <div class="action-arrow">
              <i class="pi pi-arrow-right"></i>
            </div>
          </router-link>

          <router-link to="/generate-invoices" class="action-card action-green">
            <div class="action-icon">
              <i class="pi pi-file-plus"></i>
            </div>
            <div class="action-content">
              <h3 class="action-title">Generar Factura</h3>
              <p class="action-description">Crear nuevas facturas de forma rápida y sencilla</p>
            </div>
            <div class="action-arrow">
              <i class="pi pi-arrow-right"></i>
            </div>
          </router-link>

          <router-link to="/invoices" class="action-card action-orange">
            <div class="action-icon">
              <i class="pi pi-list"></i>
            </div>
            <div class="action-content">
              <h3 class="action-title">Ver Facturas</h3>
              <p class="action-description">Consultar y gestionar todas las facturas</p>
            </div>
            <div class="action-arrow">
              <i class="pi pi-arrow-right"></i>
            </div>
          </router-link>

          <router-link to="/profile" class="action-card action-purple">
            <div class="action-icon">
              <i class="pi pi-user"></i>
            </div>
            <div class="action-content">
              <h3 class="action-title">Mi Perfil</h3>
              <p class="action-description">Actualizar tu información personal y configuración</p>
            </div>
            <div class="action-arrow">
              <i class="pi pi-arrow-right"></i>
            </div>
          </router-link>

          <div class="action-card action-gray">
            <div class="action-icon">
              <i class="pi pi-chart-bar"></i>
            </div>
            <div class="action-content">
              <h3 class="action-title">Reportes</h3>
              <p class="action-description">Generar reportes y análisis de datos</p>
            </div>
            <div class="action-arrow">
              <i class="pi pi-arrow-right"></i>
            </div>
          </div>

          <div class="action-card action-gray">
            <div class="action-icon">
              <i class="pi pi-cog"></i>
            </div>
            <div class="action-content">
              <h3 class="action-title">Configuración</h3>
              <p class="action-description">Ajustar configuraciones del sistema</p>
            </div>
            <div class="action-arrow">
              <i class="pi pi-arrow-right"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="recent-activity-section">
        <div class="section-header">
          <h2 class="section-title">
            <i class="pi pi-clock"></i>
            Actividad Reciente
          </h2>
        </div>

        <div class="activity-list">
          <div class="activity-item">
            <div class="activity-icon activity-success">
              <i class="pi pi-check"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Nueva factura creada</div>
              <div class="activity-time">Hace 2 horas</div>
            </div>
          </div>

          <div class="activity-item">
            <div class="activity-icon activity-info">
              <i class="pi pi-user-plus"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Cliente registrado</div>
              <div class="activity-time">Hace 4 horas</div>
            </div>
          </div>

          <div class="activity-item">
            <div class="activity-icon activity-warning">
              <i class="pi pi-exclamation-triangle"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Factura pendiente de pago</div>
              <div class="activity-time">Hace 1 día</div>
            </div>
          </div>

          <div class="activity-item">
            <div class="activity-icon activity-success">
              <i class="pi pi-dollar"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Pago recibido</div>
              <div class="activity-time">Hace 2 días</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { useAlert } from '@/composables/useAlert'
import Button from 'primevue/button'

const { success, error } = useAlert()

const stats = ref({
  totalUsers: 0,
  totalCustomers: 0,
  totalInvoices: 0,
  newUsersToday: 0,
  activeUsers: 0
})

const loading = ref(false)

const fetchStats = async () => {
  loading.value = true
  try {
    const response = await api.get('/dashboard/stats')
    stats.value = response.data.data
    success('Estadísticas actualizadas correctamente')
  } catch (err) {
    console.error('Error al cargar estadísticas:', err)
    error('Error al cargar las estadísticas')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchStats()
})
</script>

<style scoped>
.dashboard-container {
  padding: 1.5rem;
  max-width: 1400px;
  margin: 0 auto;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  min-height: 100vh;
}

/* Header Section */
.dashboard-header {
  margin-bottom: 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.dashboard-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 2.25rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.dashboard-title i {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.dashboard-subtitle {
  color: #64748b;
  font-size: 1.125rem;
  margin: 0.5rem 0 0 0;
}

.refresh-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.refresh-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(135deg, currentColor 0%, transparent 100%);
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.stat-card-blue {
  color: #3b82f6;
}

.stat-card-green {
  color: #10b981;
}

.stat-card-orange {
  color: #f59e0b;
}

.stat-card-purple {
  color: #8b5cf6;
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 1rem;
  background: rgba(currentColor, 0.1);
}

.stat-content {
  flex: 1;
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #64748b;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.75rem;
}

.stat-trend {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.stat-trend.positive {
  color: #10b981;
}

.stat-trend.negative {
  color: #ef4444;
}

.stat-trend.neutral {
  color: #6b7280;
}

/* Content Grid */
.content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
}

/* Quick Actions Section */
.quick-actions-section {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.section-header {
  margin-bottom: 1.5rem;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.section-title i {
  color: #667eea;
}

.section-subtitle {
  color: #64748b;
  margin: 0;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
}

.action-card {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  border-radius: 12px;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.action-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.action-card:hover::before {
  opacity: 1;
}

.action-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.action-blue {
  background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
  color: #1e40af;
  border-color: #bfdbfe;
}

.action-blue:hover {
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.action-green {
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  color: #166534;
  border-color: #bbf7d0;
}

.action-green:hover {
  background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
}

.action-orange {
  background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
  color: #92400e;
  border-color: #fde68a;
}

.action-orange:hover {
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

.action-purple {
  background: linear-gradient(135deg, #faf5ff 0%, #e9d5ff 100%);
  color: #6b21a8;
  border-color: #d8b4fe;
}

.action-purple:hover {
  background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);
}

.action-gray {
  background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  color: #374151;
  border-color: #d1d5db;
  cursor: not-allowed;
}

.action-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  margin-right: 1rem;
  background: rgba(255, 255, 255, 0.5);
}

.action-content {
  flex: 1;
}

.action-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin: 0 0 0.25rem 0;
}

.action-description {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
  line-height: 1.4;
}

.action-arrow {
  font-size: 1.125rem;
  opacity: 0.6;
  transition: all 0.3s ease;
}

.action-card:hover .action-arrow {
  opacity: 1;
  transform: translateX(4px);
}

/* Recent Activity Section */
.recent-activity-section {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.activity-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: 10px;
  background: #f8fafc;
  transition: all 0.3s ease;
}

.activity-item:hover {
  background: #f1f5f9;
  transform: translateX(4px);
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}

.activity-icon.activity-success {
  background: #dcfce7;
  color: #16a34a;
}

.activity-icon.activity-info {
  background: #dbeafe;
  color: #2563eb;
}

.activity-icon.activity-warning {
  background: #fef3c7;
  color: #d97706;
}

.activity-icon.activity-error {
  background: #fecaca;
  color: #dc2626;
}

.activity-content {
  flex: 1;
}

.activity-title {
  font-weight: 500;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.activity-time {
  font-size: 0.75rem;
  color: #64748b;
}

/* Responsive */
@media (max-width: 1024px) {
  .content-grid {
    grid-template-columns: 1fr;
  }

  .actions-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .dashboard-container {
    padding: 1rem;
  }

  .header-content {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .dashboard-title {
    font-size: 1.875rem;
  }
}

@media (max-width: 480px) {
  .quick-actions-section,
  .recent-activity-section {
    padding: 1rem;
  }

  .action-card {
    padding: 1rem;
  }

  .stat-card {
    padding: 1rem;
  }
}
</style>
