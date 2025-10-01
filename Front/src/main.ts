import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'

import App from './App.vue'
import router from './router'

// Importar tema de PrimeVue 4
import Aura from '@primevue/themes/aura'
import DialogService from 'primevue/dialogservice'
import DynamicDialog from 'primevue/dynamicdialog'

// Importar PrimeIcons
import 'primeicons/primeicons.css'

// Importar componentes PrimeVue
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Card from 'primevue/card'
import Message from 'primevue/message'
import Checkbox from 'primevue/checkbox'
import Avatar from 'primevue/avatar'
import Tag from 'primevue/tag'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(DialogService)

// Registrar componentes PrimeVue globalmente
app.component('DynamicDialog', DynamicDialog)
app.component('DataTable', DataTable)
app.component('Column', Column)
app.component('Button', Button)
app.component('InputText', InputText)
app.component('Password', Password)
app.component('Card', Card)
app.component('Message', Message)
app.component('Checkbox', Checkbox)
app.component('Avatar', Avatar)
app.component('Tag', Tag)
app.component('Dropdown', Dropdown)
app.component('Textarea', Textarea)
app.use(PrimeVue, {
  theme: {
    preset: Aura,
    options: {
      prefix: 'p',
      darkModeSelector: '.dark-mode',
      cssLayer: {
        name: 'primevue',
        order: 'tailwind-base, primevue, tailwind-utilities',
      },
    },
  },
})

app.mount('#app')
