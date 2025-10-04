<template>
  <div class="alert-system">
    <TransitionGroup name="alert" tag="div" class="alerts-container">
      <div
        v-for="alert in alerts"
        :key="alert.id"
        class="alert"
        :class="[
          `alert-${alert.type}`,
          { 'alert-with-title': alert.title }
        ]"
      >
        <div class="alert-content">
          <div class="alert-icon">
            <i :class="getIconClass(alert.type)"></i>
          </div>

          <div class="alert-body">
            <h4 v-if="alert.title" class="alert-title">{{ alert.title }}</h4>
            <p class="alert-message">{{ alert.message }}</p>
          </div>

          <div class="alert-actions">
            <button
              v-if="alert.closable"
              @click="removeAlert(alert.id)"
              class="alert-close"
              type="button"
            >
              <i class="pi pi-times"></i>
            </button>
          </div>
        </div>

        <!-- Progress bar para alertas con duración -->
        <div
          v-if="alert.duration && alert.duration > 0"
          class="alert-progress"
          :style="{ animationDuration: `${alert.duration}ms` }"
        ></div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import { useAlert } from '@/composables/useAlert'

const { alerts, removeAlert } = useAlert()

const getIconClass = (type: string) => {
  const icons = {
    success: 'pi pi-check-circle',
    error: 'pi pi-times-circle',
    warning: 'pi pi-exclamation-triangle',
    info: 'pi pi-info-circle'
  }
  return icons[type as keyof typeof icons] || 'pi pi-info-circle'
}
</script>

<style scoped>
.alert-system {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  max-width: 400px;
}

.alerts-container {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.alert {
  position: relative;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border-left: 4px solid;
  overflow: hidden;
  min-width: 320px;
}

.alert-content {
  display: flex;
  align-items: flex-start;
  padding: 16px;
  gap: 12px;
}

.alert-icon {
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  margin-top: 2px;
}

.alert-body {
  flex: 1;
  min-width: 0;
}

.alert-title {
  margin: 0 0 4px 0;
  font-size: 14px;
  font-weight: 600;
  line-height: 1.4;
}

.alert-message {
  margin: 0;
  font-size: 13px;
  line-height: 1.4;
  color: #374151;
}

.alert-actions {
  flex-shrink: 0;
}

.alert-close {
  background: none;
  border: none;
  padding: 4px;
  cursor: pointer;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  font-size: 12px;
  transition: background-color 0.2s ease;
}

.alert-close:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

.alert-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  background-color: currentColor;
  opacity: 0.3;
  animation: progress-bar linear forwards;
}

/* Estilos por tipo */
.alert-success {
  border-left-color: #10b981;
}

.alert-success .alert-icon {
  color: #10b981;
}

.alert-success .alert-progress {
  background-color: #10b981;
}

.alert-success .alert-title {
  color: #065f46;
}

.alert-error {
  border-left-color: #ef4444;
}

.alert-error .alert-icon {
  color: #ef4444;
}

.alert-error .alert-progress {
  background-color: #ef4444;
}

.alert-error .alert-title {
  color: #991b1b;
}

.alert-warning {
  border-left-color: #f59e0b;
}

.alert-warning .alert-icon {
  color: #f59e0b;
}

.alert-warning .alert-progress {
  background-color: #f59e0b;
}

.alert-warning .alert-title {
  color: #92400e;
}

.alert-info {
  border-left-color: #3b82f6;
}

.alert-info .alert-icon {
  color: #3b82f6;
}

.alert-info .alert-progress {
  background-color: #3b82f6;
}

.alert-info .alert-title {
  color: #1e40af;
}

/* Animaciones */
.alert-enter-active {
  transition: all 0.3s ease-out;
}

.alert-leave-active {
  transition: all 0.3s ease-in;
}

.alert-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.alert-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.alert-move {
  transition: transform 0.3s ease;
}

@keyframes progress-bar {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}

/* Responsive */
@media (max-width: 480px) {
  .alert-system {
    top: 10px;
    right: 10px;
    left: 10px;
    max-width: none;
  }

  .alert {
    min-width: auto;
  }

  .alert-content {
    padding: 12px;
  }
}
</style>
