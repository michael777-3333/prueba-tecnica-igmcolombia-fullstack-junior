<template>
  <div class="add-product-dialog">
    <!-- Búsqueda -->
    <div class="search-section">
      <InputText
        v-model="searchTerm"
        placeholder="Buscar productos..."
        class="search-input"
        @input="() => {}"
      />
      <i class="pi pi-search search-icon"></i>
    </div>

    <!-- Lista de productos -->
    <div class="products-list">
      <div
        v-for="product in filteredProducts"
        :key="product.id"
        class="product-item"
        :class="{ 'out-of-stock': product.stock === 0 }"
      >
        <div class="product-info">
          <div class="product-name">{{ product.name }}</div>
          <div v-if="product.description" class="product-description">
            {{ product.description }}
          </div>
          <div class="product-details">
            <span class="product-price">${{Number(product.price).toFixed(2) }}</span>
            <span class="product-stock" :class="{ 'low-stock': product.stock <= 5 }">
              Stock: {{ product.stock }}
            </span>
          </div>
        </div>

        <div class="product-actions">
          <Button
            icon="pi pi-plus"
            label="Agregar"
            @click="addProduct(product)"
            :disabled="product.stock === 0"
            class="add-btn"
          />
        </div>
      </div>
    </div>

    <!-- Mensaje cuando no hay productos -->
    <div v-if="filteredProducts.length === 0" class="no-products">
      <i class="pi pi-search"></i>
      <p>No se encontraron productos</p>
      <small>Intenta con otros términos de búsqueda</small>
    </div>

    <!-- Footer -->
    <div class="dialog-footer">
      <Button
        label="Cerrar"
        icon="pi pi-times"
        @click="handleClose"
        class="p-button-text close-btn"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { Product } from '../interfaces/invoice.interface'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { useProductsStore } from '@/stores/products.store'

// Props
interface Props {
  products: Product[]
}
const productsStore = useProductsStore()

const propsProducts = defineProps<Props>()

// Emits
const emit = defineEmits<{
  (e: 'add', product: Product): void
  (e: 'close'): void
}>()

// Estado
const searchTerm = ref('')

// Computed

const filteredProducts = computed(() => {
  const list = propsProducts.products
  if (!searchTerm.value) return list

  const term = searchTerm.value.toLowerCase()
  return list.filter(
    (product) =>
      product.name?.toLowerCase().includes(term) ||
      (product.description && product.description.toLowerCase().includes(term)),
  )
})

// Funciones
const addProduct = (product: Product) => {
  emit('add', product)
}

const handleClose = () => {
  searchTerm.value = ''
  emit('close')
}


onMounted(() => {
  console.log('onMounted')
  productsStore.fetchProducts()
})

</script>

<style scoped>
.add-product-dialog {
  display: flex;
  flex-direction: column;
  height: 100%;
  max-height: 70vh;
}

.search-section {
  margin-bottom: 1rem;
}

.search-input {
  width: 100%;
  padding-left: 2.5rem;
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
  pointer-events: none;
}

.search-section {
  position: relative;
}

.products-list {
  flex: 1;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1rem;
  max-height: 400px;
}

.product-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: #f9fafb;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  transition: all 0.2s ease;
}

.product-item:hover {
  background: #f3f4f6;
  border-color: #d1d5db;
}

.product-item.out-of-stock {
  opacity: 0.6;
  background: #fef2f2;
  border-color: #fecaca;
}

.product-info {
  flex: 1;
}

.product-name {
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.25rem;
}

.product-description {
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 0.5rem;
}

.product-details {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.product-price {
  font-weight: 600;
  color: #059669;
  font-size: 1.125rem;
}

.product-stock {
  font-size: 0.875rem;
  color: #6b7280;
  padding: 0.25rem 0.5rem;
  background: #e5e7eb;
  border-radius: 0.25rem;
}

.product-stock.low-stock {
  color: #dc2626;
  background: #fecaca;
}

.product-actions {
  margin-left: 1rem;
}

.add-btn {
  background-color: #10b981;
  border-color: #10b981;
  white-space: nowrap;
}

.add-btn:hover:not(:disabled) {
  background-color: #059669;
  border-color: #059669;
}

.add-btn:disabled {
  background-color: #d1d5db;
  border-color: #d1d5db;
  color: #9ca3af;
}

.no-products {
  text-align: center;
  padding: 3rem 1rem;
  color: #6b7280;
}

.no-products i {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.no-products p {
  font-size: 1.125rem;
  margin-bottom: 0.5rem;
}

.dialog-footer {
  display: flex;
  justify-content: flex-end;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}

.close-btn {
  color: #6b7280;
}

/* Responsive */
@media (max-width: 640px) {
  .product-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .product-actions {
    margin-left: 0;
    width: 100%;
  }

  .add-btn {
    width: 100%;
  }

  .product-details {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>
