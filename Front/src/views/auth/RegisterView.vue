<template>
  <div class="register-container">
    <div class="register-background">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
      </div>
    </div>

    <Card class="register-card">
      <template #header>
        <div class="register-header">
          <div class="logo-container">
            <div class="logo-circle">
              <i class="pi pi-user-plus"></i>
            </div>
            <div class="logo-glow"></div>
          </div>
          <h1 class="register-title">Crear Cuenta</h1>
          <p class="register-subtitle">Únete a nuestra plataforma</p>
        </div>
      </template>

      <template #content>
        <form @submit.prevent="handleRegister" class="register-form">
          <div class="form-group">
            <label for="name" class="form-label">
              <i class="pi pi-user"></i>
              Nombre Completo
            </label>
            <InputText
              id="name"
              v-model="form.name"
              type="text"
              placeholder="Tu nombre completo"
              class="form-input"
              required
            />
          </div>

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
              autocomplete="email"
              required
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
              placeholder="Mínimo 8 caracteres"
              toggleMask
              :feedback="false"
              class="password-input"
              inputClass="form-input"
              required
            />
          </div>

          <div class="form-group">
            <label for="password_confirmation" class="form-label">
              <i class="pi pi-check-circle"></i>
              Confirmar Contraseña
            </label>
            <Password
              id="password_confirmation"
              v-model="form.password_confirmation"
              placeholder="Repite tu contraseña"
              toggleMask
              :feedback="false"
              class="password-input"
              inputClass="form-input"
              required
            />
          </div>

          <Button
            type="submit"
            :label="authStore.loading ? 'Creando cuenta...' : 'Crear Cuenta'"
            icon="pi pi-user-plus"
            :loading="authStore.loading"
            class="register-button"
          />

          <div v-if="authStore.error" class="error-container">
            <Message severity="error" :closable="true">
              {{ authStore.error }}
            </Message>
          </div>
        </form>
      </template>

      <template #footer>
        <div class="register-footer">
          ¿Ya tienes una cuenta?
          <router-link to="/login" class="login-link">
            Inicia sesión aquí
          </router-link>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

// PrimeVue Components
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Message from 'primevue/message'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const handleRegister = async () => {
  const result = await authStore.register(form)
  if (result.success) {
    router.push('/')
  }
}
</script>

<style scoped>
/* Container principal */
.register-container {
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
.register-background {
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
  width: 60px;
  height: 60px;
  top: 15%;
  left: 8%;
  animation-delay: 0s;
}

.shape-2 {
  width: 100px;
  height: 100px;
  top: 45%;
  right: 12%;
  animation-delay: 1.5s;
}

.shape-3 {
  width: 80px;
  height: 80px;
  bottom: 25%;
  left: 15%;
  animation-delay: 3s;
}

.shape-4 {
  width: 70px;
  height: 70px;
  top: 70%;
  right: 25%;
  animation-delay: 4.5s;
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
.register-card {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 450px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
  border-radius: 20px;
  overflow: hidden;
}

/* Header */
.register-header {
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
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
  transition: all 0.3s ease;
}

.logo-circle:hover {
  transform: scale(1.05);
  box-shadow: 0 15px 40px rgba(16, 185, 129, 0.4);
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
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

.register-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.register-subtitle {
  color: #64748b;
  font-size: 0.95rem;
  font-weight: 500;
}

/* Formulario */
.register-form {
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
  color: #10b981;
  font-size: 0.9rem;
}

.error-container {
  margin-top: 1rem;
}

/* Footer */
.register-footer {
  text-align: center;
  padding: 1.5rem 2rem;
  background: #f8fafc;
  color: #6b7280;
  font-size: 0.9rem;
  border-top: 1px solid #e2e8f0;
}

.login-link {
  color: #10b981;
  text-decoration: none;
  font-weight: 700;
  margin-left: 0.5rem;
  transition: all 0.3s ease;
}

.login-link:hover {
  color: #059669;
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
  border-color: #10b981;
  background: white;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
  border-color: #10b981;
  background: white;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
  transform: translateY(-2px);
}

:deep(.register-button) {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-weight: 700;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
  position: relative;
  overflow: hidden;
}

:deep(.register-button::before) {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

:deep(.register-button:hover::before) {
  left: 100%;
}

:deep(.register-button:hover) {
  transform: translateY(-3px);
  box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
}

:deep(.register-button:active) {
  transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 480px) {
  .register-container {
    padding: 0.5rem;
  }

  .register-card {
    max-width: 100%;
  }

  .register-header {
    padding: 1.5rem 1rem 1rem;
  }

  .register-form {
    padding: 1.5rem;
  }

  .logo-circle {
    width: 70px;
    height: 70px;
  }

  .logo-circle i {
    font-size: 1.5rem;
  }

  .register-title {
    font-size: 1.75rem;
  }
}
</style>
