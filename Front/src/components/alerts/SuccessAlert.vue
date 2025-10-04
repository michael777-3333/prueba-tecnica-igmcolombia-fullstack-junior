<template>
  <div class="success-alert" :class="{ 'alert-visible': visible }">
    <div class="alert-content">
      <div class="alert-icon">
        <i class="pi pi-check-circle"></i>
      </div>

      <div class="alert-body">
        <h4 v-if="title" class="alert-title">{{ title }}</h4>
        <p class="alert-message">{{ message }}</p>
      </div>

      <button
        v-if="closable"
        @click="close"
        class="alert-close"
        type="button"
      >
        <i class="pi pi-times"></i>
      </button>
    </div>

    <!-- Progress bar -->
    <div
      v-if="duration && duration > 0"
      class="alert-progress"
      :style="{ animationDuration: `${duration}ms` }"
    ></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Props {
  message: string
  title?: string
  duration?: number
  closable?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  duration: 5000,
  closable: true
})

const emit = defineEmits<{
  close: []
}>()

const visible = ref(false)

const close = () => {
  visible.value = false
  setTimeout(() => {
    emit('close')
  }, 300)
}

onMounted(() => {
  visible.value = true

  if (props.duration && props.duration > 0) {
    setTimeout(() => {
      close()
    }, props.duration)
  }
})
</script>

<style scoped>
.success-alert {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-left: 4px solid #10b981;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
  overflow: hidden;
  transition: all 0.3s ease;
  opacity: 0;
  transform: translateY(-20px);
}

.success-alert.alert-visible {
  opacity: 1;
  transform: translateY(0);
}

.alert-content {
  display: flex;
  align-items: flex-start;
  padding: 16px;
  gap: 12px;
}

.alert-icon {
  color: #10b981;
  font-size: 18px;
  margin-top: 2px;
}

.alert-body {
  flex: 1;
}

.alert-title {
  margin: 0 0 4px 0;
  font-size: 14px;
  font-weight: 600;
  color: #065f46;
  line-height: 1.4;
}

.alert-message {
  margin: 0;
  font-size: 13px;
  color: #374151;
  line-height: 1.4;
}

.alert-close {
  background: none;
  border: none;
  padding: 4px;
  cursor: pointer;
  border-radius: 4px;
  color: #6b7280;
  font-size: 12px;
  transition: all 0.2s ease;
}

.alert-close:hover {
  background-color: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.alert-progress {
  height: 3px;
  background-color: #10b981;
  animation: progress-bar linear forwards;
}

@keyframes progress-bar {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}
</style>
