<template>
  <div class="p-4">
    <h2 class="text-2xl font-bold mb-4">Prueba de PrimeVue</h2>
    
    <!-- Botón de PrimeVue -->
    <Button label="Botón PrimeVue" class="mr-2" />
    
    <!-- Input de PrimeVue -->
    <InputText v-model="value" placeholder="Escribe algo..." class="mr-2" />
    
    <!-- Card de PrimeVue -->
    <Card class="mt-4">
      <template #title>
        <div class="flex align-items-center">
          <span>Usuario</span>
        </div>
      </template>
      <template #content>
        <p>Este es un ejemplo de Card de PrimeVue funcionando correctamente.</p>
        <p>Valor del input: {{ value }}</p>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Card from 'primevue/card'

const value = ref('')
</script>
