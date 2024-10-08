<script setup>
import TheWelcome from './components/TheWelcome.vue'
import Navbar from './components/Navbar.vue';
import LoginForm from './components/LoginForm.vue';
import SignupForm from './components/SignupForm.vue';
import { RouterView } from 'vue-router';
import { useAuthStore } from './store/authStore'; 
import { onMounted } from 'vue';

const authStore = useAuthStore();

onMounted(() => {
  authStore.checkLoginStatus()
})
</script>

<template>
  <div class="bg-white dark:bg-gray-900">
    <div class="mx-auto max-w-screen-xl text-center">
      <Navbar /> 
      <main>
        <RouterView />
      </main>
    </div>
  </div>

</template>

