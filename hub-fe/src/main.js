import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import { createPinia  } from 'pinia' 

const pinia = createPinia()

createApp(App)
    .use(router)
    .use(pinia)
    .provide('getHeaders', () => {
        const token = localStorage.getItem('authToken');
        return {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' // Add other headers as needed
        };
    })
    .mount('#app')
