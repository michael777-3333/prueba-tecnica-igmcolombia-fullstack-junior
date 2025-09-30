<template>
  <div class="min-h-screen flex bg-surface-50">
    <!-- SIDEBAR -->
    <aside
      :class="[
        'flex-shrink-0 h-screen transition-all duration-300 border-r',
        collapsed ? 'w-16' : 'w-64'
      ]"
      class="bg-white/70 backdrop-blur-md"
    >
      <div class="flex flex-col h-full">
        <!-- Brand + collapse -->
        <div class="flex items-center justify-between p-4 border-b">
          <router-link to="/" class="flex items-center gap-3">
            <img src="" alt="logo" class="w-8 h-8 rounded" />
            <span v-if="!collapsed" class="text-lg font-semibold text-slate-700">MiApp</span>
          </router-link>

          <Button
            :icon="collapsed ? 'pi pi-angle-double-right' : 'pi pi-angle-double-left'"
            class="p-button-text p-button-rounded"
            @click="collapsed = !collapsed"
            aria-label="Toggle sidebar"
            size="small"
          />
        </div>

        <!-- User -->
        <div class="p-4 border-b flex items-center gap-3">
          <Avatar
            label="MS"
            :size="collapsed ? 'normal' : 'large'"
            class="bg-primary text-white"
          />
          <div v-if="!collapsed" class="flex flex-col">
            <div class="font-semibold text-slate-700">{{ userName }}</div>
            <div class="text-sm text-slate-500">{{ userEmail }}</div>
          </div>
        </div>

        <!-- Search (hidden when collapsed) -->
        <div v-if="!collapsed" class="p-3 border-b">
          <div class="relative">
            <i class="pi pi-search absolute left-3 top-3 text-slate-400"></i>
            <InputText
              v-model="search"
              placeholder="Buscar..."
              class="w-full pl-10"
            />
          </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-auto p-2">
          <ul class="space-y-1">
            <li
              v-for="item in nav"
              :key="item.label"
              :class="[
                'nav-item flex items-center gap-3 cursor-pointer group',
                activeRoute === item.to ? 'nav-item-active' : ''
              ]"
              @click="go(item)"
            >
              <i :class="['pi', item.icon, 'text-lg w-6 text-center']"></i>

              <div class="flex-1 min-w-0">
                <div v-if="!collapsed" class="truncate">{{ item.label }}</div>
              </div>

              <span v-if="item.badge && !collapsed" class="ml-2">
                <Badge :value="item.badge" size="large" severity="info" />
              </span>
            </li>
          </ul>
        </nav>

        <!-- Sidebar footer -->
        <div class="p-3 border-t">
          <div v-if="!collapsed" class="flex items-center justify-between">
            <div class="text-sm text-slate-600">v1.0</div>
            <div class="flex gap-2">
              <Button icon="pi pi-cog" class="p-button-text" @click="goTo('/settings')" />
              <Button icon="pi pi-power-off" class="p-button-text" @click="logout" />
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <header class="flex items-center justify-between px-6 py-4 border-b bg-white/60 backdrop-blur-md">
        <div class="flex items-center gap-4">
          <h1 class="text-xl font-semibold text-slate-800">{{ pageTitle }}</h1>
          <span v-if="pageDescription" class="text-sm text-slate-500 hidden md:inline">{{ pageDescription }}</span>
        </div>

        <div class="flex items-center gap-3">
          <Button icon="pi pi-bell" class="p-button-text" @click="notify" />
          <Menubar :model="userMenu" :popup="false" class="hidden md:block" />
        </div>
      </header>

      <!-- Content area -->
      <main class="p-6 overflow-auto main-content">
        <router-view />
      </main>

      <!-- Optional footer -->
      <footer class="text-center py-4 text-sm text-slate-500 border-t bg-white/40">
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
import { useAuthStore } from '@/stores/auth' // ajusta según tu store

// local state
const router = useRouter()
const route = useRoute()
const collapsed = ref(false)
const search = ref('')

// fake auth data (sustituir por store real)
const auth = useAuthStore && useAuthStore() || { user: { name: 'Michael Stiven', email: 'michael@example.com' } }
const userName = computed(() => auth.user?.name || 'Usuario')
const userEmail = computed(() => auth.user?.email || 'usuario@domain.com')

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
function go(item: any) {
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
watch(search, (val) => {
  // Puedes disparar búsqueda global, ejemplo:
  // router.push({ name: 'search', query: { q: val } })
})
</script>

<style scoped>
/* Some small refinements on top of Tailwind */
.nav-item {
  padding: 0.65rem 0.9rem;
  border-radius: 0.75rem;
  color: #475569;
  display: flex;
  align-items: center;
  transition: background-color .15s ease, color .15s ease, transform .15s ease;
}
.nav-item:hover {
  background-color: rgba(59,130,246,0.06);
  color: #1d4ed8;
  transform: translateX(3px);
}
.nav-item-active {
  background: linear-gradient(90deg, rgba(59,130,246,0.12), rgba(96,165,250,0.06));
  color: #1d4ed8;
  font-weight: 600;
}
/* make icon slightly larger when active */
.nav-item-active i {
  transform: scale(1.07);
}

/* main content subtle bg */
.main-content {
  background: linear-gradient(180deg, rgba(248,250,252,0.6), rgba(255,255,255,0.6));
  min-height: calc(100vh - 64px);
}

/* small responsive fixes */
@media (max-width: 768px) {
  aside { position: fixed; z-index: 40; height: 100vh; left: 0; top: 0; }
  .main-content { padding-top: 6rem; }
}
</style>
