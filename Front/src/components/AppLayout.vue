<template>
  <div class="app-container">
    <!-- SIDEBAR -->
    <aside 
      class="sidebar" 
      :class="{ 'sidebar-collapsed': collapsed }"
    >
      <!-- Header -->
      <div class="sidebar-header">
        <div class="brand">
          <i class="pi pi-building brand-icon"></i>
          <span v-if="!collapsed" class="brand-text">MiApp</span>
        </div>
        <button 
          class="collapse-button"
          @click="collapsed = !collapsed"
          :title="collapsed ? 'Expandir' : 'Colapsar'"
        >
          <i :class="collapsed ? 'pi pi-angle-double-right' : 'pi pi-angle-double-left'"></i>
        </button>
      </div>

      <!-- User Info -->
      <div class="user-section">
        <div class="user-avatar">{{ userInitials }}</div>
        <div v-if="!collapsed" class="user-details">
          <div class="user-name">{{ userName }}</div>
          <div class="user-email">{{ userEmail }}</div>
        </div>
      </div>

      <!-- Search -->
      <div v-if="!collapsed" class="search-section">
        <div class="search-box">
          <i class="pi pi-search search-icon"></i>
          <input 
            v-model="search"
            type="text" 
            placeholder="Buscar..." 
            class="search-input"
          />
        </div>
      </div>

      <!-- Navigation -->
      <nav class="navigation">
        <ul class="nav-menu">
          <li 
            v-for="item in nav" 
            :key="item.label"
            class="nav-item"
            :class="{ 'nav-item-active': activeRoute === item.to }"
            @click="go(item)"
          >
            <i :class="['pi', item.icon, 'nav-icon']"></i>
            <span v-if="!collapsed" class="nav-text">{{ item.label }}</span>
            <span v-if="item.badge && !collapsed" class="nav-badge">{{ item.badge }}</span>
          </li>
        </ul>
      </nav>

      <!-- Footer -->
      <div class="sidebar-footer">
        <div v-if="!collapsed" class="footer-info">
          <span class="version">v1.0</span>
          <div class="footer-buttons">
            <button class="footer-btn" @click="goTo('/settings')" title="Configuración">
              <i class="pi pi-cog"></i>
            </button>
            <button class="footer-btn logout-btn" @click="logout" title="Cerrar sesión">
              <i class="pi pi-power-off"></i>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-wrapper">
      <!-- Top Bar -->
      <header class="top-bar">
        <div class="top-left">
          <h1 class="page-title">{{ pageTitle }}</h1>
          <span v-if="pageDescription" class="page-subtitle">{{ pageDescription }}</span>
        </div>
        <div class="top-right">
          <button class="notification-btn" @click="notify" title="Notificaciones">
            <i class="pi pi-bell"></i>
          </button>
          <Menubar :model="userMenu" :popup="false" class="user-menu" />
        </div>
      </header>

      <!-- Content -->
      <main class="content-main">
        <slot />
      </main>

      <!-- Footer -->
      <footer class="app-footer">
        © {{ new Date().getFullYear() }} MiApp — Hecho con ❤️
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Menubar from 'primevue/menubar'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Avatar from 'primevue/avatar'
import Badge from 'primevue/badge'
import { useAuthStore } from '@/stores/auth.store' // ajusta según tu store

// local state
const router = useRouter()
const route = useRoute()
const collapsed = ref(false)
const search = ref('')

// fake auth data (sustituir por store real)
const auth = useAuthStore && useAuthStore() || { user: { name: 'Michael Stiven', email: 'michael@example.com' } }
const userName = computed(() => auth.user?.name || 'Usuario')
const userEmail = computed(() => auth.user?.email || 'usuario@domain.com')
const userInitials = computed(() => {
  const name = userName.value
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

// navigation
const nav = [
  { label: 'Inicio', to: '/', icon: 'pi-home' },
  { label: 'Dashboard', to: '/dashboard', icon: 'pi-chart-line' },
  { label: 'Usuarios', to: '/users', icon: 'pi-users', badge: 6 },
  { label: 'Facturas', to: '/invoices', icon: 'pi-file-invoice' },
  { label: 'Productos', to: '/products', icon: 'pi-box' },
  { label: 'Ajustes', to: '/settings', icon: 'pi-cog' }
]

// computed
const activeRoute = computed(() => route.path)
const pageTitle = computed(() => {
  const first = route.meta?.title || route.name || 'Panel'
  return typeof first === 'string' ? first : String(first)
})
const pageDescription = computed(() => route.meta?.description || '')

// menus (Menubar)
const userMenu = [
  {
    label: 'Cuenta',
    items: [
      { label: 'Perfil', icon: 'pi pi-user', command: () => goTo('/profile') },
      { label: 'Configuración', icon: 'pi pi-cog', command: () => goTo('/settings') }
    ]
  }
]

// methods
function go(item: { to?: string; label: string; icon: string; badge?: number }) {
  if (item.to) router.push(item.to)
}
function goTo(path: string) {
  router.push(path)
}
function notify() {
  // use your notification system (PrimeVue Notify or Toast)
  // $q.notify or toast - depending on your setup
  // as example:
  // $q.notify({ type: 'info', message: 'Notificación de ejemplo' })
  console.log('notify')
}
async function logout() {
  if (auth.logout) await auth.logout()
  router.push('/login')
}

// watch search key to navigate or filter (example)
watch(search, (searchValue) => {
  // Puedes disparar búsqueda global, ejemplo:
  // router.push({ name: 'search', query: { q: searchValue } })
  console.log('Search:', searchValue)
})
</script>

<style scoped>
/* Reset y layout principal */
.app-container {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* SIDEBAR */
.sidebar {
  width: 280px;
  height: 100vh;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
  border-right: 1px solid #e2e8f0;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 100;
}

.sidebar-collapsed {
  width: 70px;
}

/* Header del sidebar */
.sidebar-header {
  padding: 20px;
  border-bottom: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 80px;
}

.brand {
  display: flex;
  align-items: center;
  gap: 12px;
  color: white;
  text-decoration: none;
}

.brand-icon {
  font-size: 24px;
  color: white;
}

.brand-text {
  font-size: 20px;
  font-weight: 700;
  color: white;
  letter-spacing: -0.5px;
}

.collapse-button {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  border-radius: 8px;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.collapse-button:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: scale(1.05);
}

/* Sección de usuario */
.user-section {
  padding: 20px;
  border-bottom: 1px solid #e2e8f0;
  background: white;
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 16px;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.user-details {
  display: flex;
  flex-direction: column;
  min-width: 0;
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

/* Sección de búsqueda */
.search-section {
  padding: 16px 20px;
  border-bottom: 1px solid #e2e8f0;
  background: white;
}

.search-box {
  position: relative;
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
  border-radius: 10px;
  background: #f8fafc;
  font-size: 14px;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Navegación */
.navigation {
  flex: 1;
  overflow-y: auto;
  padding: 16px 12px;
  background: white;
}

.nav-menu {
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-item {
  margin-bottom: 4px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
  overflow: hidden;
}

.nav-item:hover {
  background: #f1f5f9;
  transform: translateX(4px);
}

.nav-item-active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.nav-item-active:hover {
  transform: translateX(4px);
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  gap: 12px;
}

.nav-icon {
  font-size: 18px;
  width: 20px;
  text-align: center;
  color: #64748b;
}

.nav-item-active .nav-icon {
  color: white;
}

.nav-text {
  flex: 1;
  font-weight: 500;
  color: #374151;
  font-size: 14px;
}

.nav-item-active .nav-text {
  color: white;
  font-weight: 600;
}

.nav-badge {
  background: #3b82f6;
  color: white;
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 12px;
  min-width: 20px;
  text-align: center;
}

.nav-item-active .nav-badge {
  background: rgba(255, 255, 255, 0.2);
}

/* Footer del sidebar */
.sidebar-footer {
  padding: 16px 20px;
  border-top: 1px solid #e2e8f0;
  background: #f8fafc;
}

.footer-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.version {
  font-size: 11px;
  color: #94a3b8;
  font-weight: 500;
}

.footer-buttons {
  display: flex;
  gap: 4px;
}

.footer-btn {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 8px;
  background: transparent;
  color: #64748b;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.footer-btn:hover {
  background: #e2e8f0;
  color: #374151;
}

.logout-btn:hover {
  background: #fef2f2;
  color: #dc2626;
}

/* CONTENIDO PRINCIPAL */
.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Top bar */
.top-bar {
  background: white;
  border-bottom: 1px solid #e2e8f0;
  padding: 20px 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.top-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-title {
  font-size: 24px;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.page-subtitle {
  font-size: 14px;
  color: #64748b;
  display: none;
}

.top-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.notification-btn {
  width: 40px;
  height: 40px;
  border: none;
  border-radius: 10px;
  background: transparent;
  color: #64748b;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.notification-btn:hover {
  background: #f1f5f9;
  color: #374151;
}

.user-menu {
  display: none;
}

/* Contenido principal */
.content-main {
  flex: 1;
  padding: 32px;
  background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
  overflow-y: auto;
}

/* Footer de la aplicación */
.app-footer {
  background: white;
  border-top: 1px solid #e2e8f0;
  padding: 20px 32px;
  text-align: center;
  color: #64748b;
  font-size: 14px;
}

/* Responsive */
@media (min-width: 768px) {
  .page-subtitle {
    display: inline;
  }
  
  .user-menu {
    display: block;
  }
}

@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    transform: translateX(-100%);
  }
  
  .sidebar:not(.sidebar-collapsed) {
    transform: translateX(0);
  }
  
  .main-wrapper {
    margin-left: 0;
  }
  
  .content-main {
    padding: 20px;
  }
  
  .top-bar {
    padding: 16px 20px;
  }
  
  .page-title {
    font-size: 20px;
  }
}

/* Scrollbar personalizado */
.navigation::-webkit-scrollbar {
  width: 4px;
}

.navigation::-webkit-scrollbar-track {
  background: transparent;
}

.navigation::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 2px;
}

.navigation::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Animaciones */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.nav-item {
  animation: slideIn 0.3s ease forwards;
}

.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.2s; }
.nav-item:nth-child(3) { animation-delay: 0.3s; }
.nav-item:nth-child(4) { animation-delay: 0.4s; }
.nav-item:nth-child(5) { animation-delay: 0.5s; }
.nav-item:nth-child(6) { animation-delay: 0.6s; }
</style>
