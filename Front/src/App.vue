<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { RouterView } from 'vue-router'
import AppLayout from '@/components/AppLayout.vue'
import AlertSystem from '@/components/AlertSystem.vue'

const route = useRoute()

// Determinar si debe mostrar el layout basado en la ruta actual
const shouldShowLayout = computed(() => {
  // Rutas que NO deben mostrar el layout
  const noLayoutRoutes = ['login', 'register']
  return !noLayoutRoutes.includes(route.name as string)
})
</script>

<template>
  <AppLayout v-if="shouldShowLayout">
    <RouterView />
    <DynamicDialog />
  </AppLayout>
  <RouterView v-else />

  <!-- Sistema global de alertas -->
  <AlertSystem />
</template>

<style>
/* Estilos globales */
</style>
