import { ref, reactive } from 'vue'

export interface AlertOptions {
  title?: string
  message: string
  duration?: number
  closable?: boolean
}

export interface Alert {
  id: string
  type: 'success' | 'error' | 'warning' | 'info'
  title?: string
  message: string
  duration?: number
  closable?: boolean
  timestamp: number
}

// Estado global de alertas
const alerts = ref<Alert[]>([])
let alertIdCounter = 0

export const useAlert = () => {
  const addAlert = (type: Alert['type'], options: AlertOptions) => {
    const id = `alert-${++alertIdCounter}`
    const alert: Alert = {
      id,
      type,
      title: options.title,
      message: options.message,
      duration: options.duration || (type === 'error' ? 0 : 5000), // Los errores no se auto-ocultan
      closable: options.closable !== false,
      timestamp: Date.now()
    }

    alerts.value.push(alert)

    // Auto-remover después de la duración especificada
    if (alert.duration && alert.duration > 0) {
      setTimeout(() => {
        removeAlert(id)
      }, alert.duration)
    }

    return id
  }

  const removeAlert = (id: string) => {
    const index = alerts.value.findIndex(alert => alert.id === id)
    if (index > -1) {
      alerts.value.splice(index, 1)
    }
  }

  const clearAllAlerts = () => {
    alerts.value = []
  }

  // Métodos de conveniencia
  const success = (message: string, options?: Omit<AlertOptions, 'message'>) => {
    return addAlert('success', { message, ...options })
  }

  const error = (message: string, options?: Omit<AlertOptions, 'message'>) => {
    return addAlert('error', { message, ...options })
  }

  const warning = (message: string, options?: Omit<AlertOptions, 'message'>) => {
    return addAlert('warning', { message, ...options })
  }

  const info = (message: string, options?: Omit<AlertOptions, 'message'>) => {
    return addAlert('info', { message, ...options })
  }

  return {
    alerts: alerts.value,
    addAlert,
    removeAlert,
    clearAllAlerts,
    success,
    error,
    warning,
    info
  }
}

// Función para usar alertas desde cualquier lugar sin composable
export const showAlert = {
  success: (message: string, options?: Omit<AlertOptions, 'message'>) => {
    const { success } = useAlert()
    return success(message, options)
  },
  error: (message: string, options?: Omit<AlertOptions, 'message'>) => {
    const { error } = useAlert()
    return error(message, options)
  },
  warning: (message: string, options?: Omit<AlertOptions, 'message'>) => {
    const { warning } = useAlert()
    return warning(message, options)
  },
  info: (message: string, options?: Omit<AlertOptions, 'message'>) => {
    const { info } = useAlert()
    return info(message, options)
  }
}
