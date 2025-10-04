<template>
  <div class="welcome-container">
    <div class="welcome-card">
      <div class="welcome-header">
        <div class="welcome-icon">
          <i class="pi pi-home"></i>
        </div>
        <div class="welcome-content">
          <h1 class="welcome-title">
            ¡Bienvenido de vuelta!
          </h1>
          <p class="welcome-subtitle">
            {{ authStore.user?.name || 'Usuario' }}
          </p>
        </div>
      </div>

      <div class="welcome-message">
        <p class="message-text">
          Has iniciado sesión exitosamente en tu aplicación de gestión empresarial.
          <br>
          Accede a todas las funcionalidades disponibles desde el menú lateral.
        </p>
      </div>

      <div class="welcome-actions">
        <router-link to="/dashboard" class="action-btn primary">
          <i class="pi pi-chart-line"></i>
          <span>Dashboard</span>
        </router-link>
        <router-link to="/users" class="action-btn secondary">
          <i class="pi pi-users"></i>
          <span>Usuarios</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store'

const authStore = useAuthStore()
</script>

<style scoped>
.welcome-container {
  min-height: calc(100vh - 200px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
  overflow: hidden;
}

.welcome-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  pointer-events: none;
}

.welcome-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 24px;
  padding: 3rem;
  box-shadow:
    0 25px 50px -12px rgba(0, 0, 0, 0.25),
    0 0 0 1px rgba(255, 255, 255, 0.3);
  max-width: 600px;
  width: 100%;
  text-align: center;
  position: relative;
  z-index: 1;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.welcome-header {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
  gap: 1.5rem;
}

.welcome-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
  position: relative;
}

.welcome-icon::before {
  content: '';
  position: absolute;
  inset: -2px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  z-index: -1;
  opacity: 0.3;
  filter: blur(10px);
}

.welcome-icon i {
  font-size: 2rem;
  color: white;
}

.welcome-content {
  text-align: left;
}

.welcome-title {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 0 0 0.5rem 0;
  line-height: 1.2;
}

.welcome-subtitle {
  font-size: 1.25rem;
  color: #6b7280;
  margin: 0;
  font-weight: 500;
}

.welcome-message {
  margin-bottom: 2.5rem;
}

.message-text {
  font-size: 1.1rem;
  color: #4b5563;
  line-height: 1.6;
  margin: 0;
}

.welcome-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.action-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-radius: 16px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  min-width: 160px;
  justify-content: center;
}

.action-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.action-btn:hover::before {
  left: 100%;
}

.action-btn.primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.action-btn.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.action-btn.secondary {
  background: rgba(255, 255, 255, 0.8);
  color: #4b5563;
  border: 2px solid rgba(102, 126, 234, 0.2);
  backdrop-filter: blur(10px);
}

.action-btn.secondary:hover {
  background: rgba(255, 255, 255, 1);
  border-color: rgba(102, 126, 234, 0.4);
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.action-btn i {
  font-size: 1.25rem;
}

/* Responsive */
@media (max-width: 768px) {
  .welcome-container {
    padding: 1rem;
  }

  .welcome-card {
    padding: 2rem;
  }

  .welcome-header {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }

  .welcome-content {
    text-align: center;
  }

  .welcome-title {
    font-size: 2rem;
  }

  .welcome-icon {
    width: 60px;
    height: 60px;
  }

  .welcome-icon i {
    font-size: 1.5rem;
  }

  .welcome-actions {
    flex-direction: column;
    align-items: center;
  }

  .action-btn {
    width: 100%;
    max-width: 280px;
  }
}

@media (max-width: 480px) {
  .welcome-card {
    padding: 1.5rem;
  }

  .welcome-title {
    font-size: 1.75rem;
  }

  .message-text {
    font-size: 1rem;
  }
}
</style>
