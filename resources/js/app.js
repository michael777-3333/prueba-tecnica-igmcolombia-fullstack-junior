import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Quasar Framework
// import { Quasar } from 'quasar';
import 'quasar/dist/quasar.css';
import '@quasar/extras/material-icons/material-icons.css';
import '@quasar/extras/fontawesome-v6/fontawesome-v6.css';
import { Quasar,
  QIcon,
  QCard,
  QCardSection,
  QCardActions,
  QBtn,
  QTable,
  QTd,
  QTh,
  QTr,
  QAvatar,
  QBadge,
  QDialog,
  QInput,
  QSelect,
  QSlideTransition,
  QBtnGroup,
  QTooltip,
  QForm,
  QPagination
} from 'quasar'
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
      const app = createApp({ render: () => h(App, props) });

      // registrar Inertia plugin e PrimeVue (si los usas)
      app.use(plugin);
      // app.use(PrimeVue);
      // app.use(ToastService);

      // Registrar Quasar con los componentes/directivas que usas
      app.use(Quasar, {
          components: {
              QIcon,
              QCard,
              QCardSection,
              QCardActions,
              QBtn,
              QTable,
              QTd,
              QTh,
              QTr,
              QAvatar,
              QBadge,
              QDialog,
              QInput,
              QSelect,
              QSlideTransition,
              QBtnGroup,
              QTooltip,
              QForm,
              QPagination
          },
          plugins: {
            Notify: {}, 
            Dialog: {}, 
            Loading: {}
          },
          config: {
              brand: {
                  primary: '#1976d2',
                  secondary: '#26a69a',
                  accent: '#9c27b0',
                  dark: '#1d1d1d',
                  positive: '#21ba45',
                  negative: '#c10015',
                  info: '#31ccec',
                  warning: '#f2c037'
              }
          }
      });

      return app.mount(el);
  },
    progress: {
        color: '#4B5563',
    },
});
