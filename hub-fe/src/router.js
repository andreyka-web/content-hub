// import vue from 'vue'
import { createMemoryHistory, createRouter } from 'vue-router' 
import TheWelcome from './components/TheWelcome.vue'
import LoginForm from './components/forms/LoginForm.vue'
import SignupForm from './components/forms/SignupForm.vue' 
import Files from './components/Files.vue'

export default new createRouter({
  history: createMemoryHistory(),
  routes: [
    { path: '/', component: TheWelcome },
    { path: '/login', component: LoginForm },
    { path: '/signup', component: SignupForm }, 
    { path: '/files', component: Files }
  ]
})