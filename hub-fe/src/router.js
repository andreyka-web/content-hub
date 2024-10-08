// import vue from 'vue'
import { createMemoryHistory, createRouter } from 'vue-router' 
import TheWelcome from './components/TheWelcome.vue'
import LoginForm from './components/LoginForm.vue'
import SignupForm from './components/SignupForm.vue' 
import Files from './components/Files.vue'

// vue.use(Router)

export default new createRouter({
  history: createMemoryHistory(),
  routes: [
    { path: '/', component: TheWelcome },
    { path: '/login', component: LoginForm },
    { path: '/signup', component: SignupForm }, 
    { path: '/files', component: Files }
  ]
})