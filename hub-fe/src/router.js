// import vue from 'vue'
import { createMemoryHistory, createRouter } from 'vue-router' 
import TheWelcome from './components/TheWelcome.vue'
import LoginForm from './components/forms/LoginForm.vue'
import SignupForm from './components/forms/SignupForm.vue' 
import Files from './components/Files.vue'
import { isAuthenticated } from './lib/auth'

const router = new createRouter({
  history: createMemoryHistory(),
  routes: [
    { path: '/', component: TheWelcome, name: 'welcome' },
    { path: '/login', component: LoginForm, name: 'login' },
    { path: '/signup', component: SignupForm, name: 'signup' }, 
    { path: '/files', component: Files, name: 'files' }
  ]
});

router.beforeEach((to, from, next) => {
  if(to.name !== 'login' && to.name !== 'signup' && !isAuthenticated()) next({ name: 'login' }); 
  else next();
});

export default router;