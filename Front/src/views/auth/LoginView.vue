<template>
  <div class="login-container">
    <div class="login-background">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
      </div>
    </div>

    <Card class="login-card">
      <template #header>
        <div class="login-header">
          <div class="logo-container">
            <div class="logo-circle">
              <i class="pi pi-lock"></i>
            </div>
            <div class="logo-glow"></div>
          </div>
          <h1 class="login-title">Bienvenido</h1>
          <p class="login-subtitle">Inicia sesión en tu cuenta</p>
        </div>
      </template>

      <template #content>
        <form @submit.prevent="handleLogin" class="login-form">
          <div class="form-group">
            <label for="email" class="form-label">
              <i class="pi pi-envelope"></i>
              Email
            </label>
            <InputText
              id="email"
              v-model="form.email"
              type="email"
              placeholder="tu@email.com"
              class="form-input"
            />
          </div>

          <div class="form-group">
            <label for="password" class="form-label">
              <i class="pi pi-key"></i>
              Contraseña
            </label>
            <Password
              id="password"
              v-model="form.password"
              placeholder="••••••••"
              toggleMask
              :feedback="false"
              class="password-input"
              inputClass="form-input"
            />
          </div>

          <div class="form-options">
            <div class="remember-section">
              <Checkbox v-model="rememberMe" binary />
              <label class="remember-label">Recordarme</label>
            </div>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
          </div>

          <Button
            type="submit"
            label="Iniciar Sesión"
            icon="pi pi-arrow-right"
            :loading="authStore.loading"
            class="login-button"
          />

          <div v-if="authStore.error" class="error-container">
            <Message severity="error" :closable="true">
              {{ authStore.error }}
            </Message>
          </div>
        </form>
      </template>

      <template #footer>
        <div class="login-footer">
          ¿No tienes una cuenta?
          <router-link to="/register" class="register-link">
            Regístrate aquí
          </router-link>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

// PrimeVue Components
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Message from 'primevue/message'
import Checkbox from 'primevue/checkbox'

const router = useRouter()
const authStore = useAuthStore()
const rememberMe = ref(false)

const form = reactive({
  email: '',
  password: '',
})

const handleLogin = async () => {
  const result = await authStore.login(form)
  if (result.success) {
    router.push('/')
  }
}
</script>

<style scoped>
/* Container principal */
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1rem;
}

/* Fondo animado */
.login-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
}

.floating-shapes {
  position: absolute;
  width: 100%;
  height: 100%;
}

.shape {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  animation: float 6s ease-in-out infinite;
}

.shape-1 {
  width: 80px;
  height: 80px;
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape-2 {
  width: 120px;
  height: 120px;
  top: 60%;
  right: 15%;
  animation-delay: 2s;
}

.shape-3 {
  width: 60px;
  height: 60px;
  bottom: 20%;
  left: 20%;
  animation-delay: 4s;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

/* Card principal */
.login-card {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 420px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
  border-radius: 20px;
  overflow: hidden;
}

/* Header */
.login-header {
  text-align: center;
  padding: 2rem 2rem 1rem;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  position: relative;
}

.logo-container {
  position: relative;
  display: inline-block;
  margin-bottom: 1.5rem;
}

.logo-circle {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
}

.logo-circle:hover {
  transform: scale(1.05);
  box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
}

.logo-circle i {
  font-size: 2rem;
  color: white;
}

.logo-glow {
  position: absolute;
  top: -10px;
  left: -10px;
  right: -10px;
  bottom: -10px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  opacity: 0.3;
  filter: blur(20px);
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.6;
    transform: scale(1.1);
  }
}

.login-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.login-subtitle {
  color: #64748b;
  font-size: 0.95rem;
  font-weight: 500;
}

/* Formulario */
.login-form {
  padding: 2rem;
  background: white;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.form-label i {
  color: #667eea;
  font-size: 0.9rem;
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  font-size: 0.9rem;
}

.remember-section {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.remember-label {
  color: #6b7280;
  cursor: pointer;
  font-weight: 500;
}

.forgot-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.forgot-link:hover {
  color: #764ba2;
  text-decoration: underline;
}

.error-container {
  margin-top: 1rem;
}

/* Footer */
.login-footer {
  text-align: center;
  padding: 1.5rem 2rem;
  background: #f8fafc;
  color: #6b7280;
  font-size: 0.9rem;
  border-top: 1px solid #e2e8f0;
}

.register-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 700;
  margin-left: 0.5rem;
  transition: all 0.3s ease;
}

.register-link:hover {
  color: #764ba2;
  text-decoration: underline;
}

/* Estilos PrimeVue personalizados */
:deep(.p-card) {
  border: none;
  box-shadow: none;
}

:deep(.p-card .p-card-content) {
  padding: 0;
}

:deep(.p-card .p-card-header) {
  padding: 0;
  border: none;
}

:deep(.p-card .p-card-footer) {
  padding: 0;
  border: none;
}

:deep(.form-input) {
  width: 100%;
  padding: 1rem 1.25rem;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8fafc;
  color: #374151;
}

:deep(.form-input:focus) {
  outline: none;
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  transform: translateY(-2px);
}

:deep(.form-input::placeholder) {
  color: #9ca3af;
}

:deep(.password-input) {
  width: 100%;
}

:deep(.password-input .p-password-input) {
  width: 100%;
  padding: 1rem 1.25rem;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8fafc;
  color: #374151;
}

:deep(.password-input .p-password-input:focus) {
  outline: none;
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  transform: translateY(-2px);
}

:deep(.login-button) {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-weight: 700;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
  position: relative;
  overflow: hidden;
}

:deep(.login-button::before) {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

:deep(.login-button:hover::before) {
  left: 100%;
}

:deep(.login-button:hover) {
  transform: translateY(-3px);
  box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

:deep(.login-button:active) {
  transform: translateY(-1px);
}

:deep(.p-checkbox .p-checkbox-box) {
  border: 2px solid #d1d5db;
  border-radius: 6px;
  width: 18px;
  height: 18px;
  transition: all 0.3s ease;
}

:deep(.p-checkbox .p-checkbox-box.p-highlight) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
}

:deep(.p-checkbox .p-checkbox-box.p-highlight .p-checkbox-icon) {
  color: white;
}

/* Responsive */
@media (max-width: 480px) {
  .login-container {
    padding: 0.5rem;
  }

  .login-card {
    max-width: 100%;
  }

  .login-header {
    padding: 1.5rem 1rem 1rem;
  }

  .login-form {
    padding: 1.5rem;
  }

  .logo-circle {
    width: 70px;
    height: 70px;
  }

  .logo-circle i {
    font-size: 1.5rem;
  }

  .login-title {
    font-size: 1.75rem;
  }

  .form-options {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }
}
</style>
