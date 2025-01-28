<script setup>
import Navbar from './components/Navbar.vue'; 
import Footer from './components/Footer.vue';
import { RouterView } from 'vue-router';
import { useAuthStore } from './store/authStore'; 
import { onMounted } from 'vue';

const authStore = useAuthStore();

onMounted(() => {
  authStore.checkLoginStatus()
})
</script>

<template>
  <div class="bg-white dark:bg-gray-700 min-h-screen">
    <div class="mx-auto max-w-screen-lg text-center">
      <Navbar />
      <main>
        <RouterView />
      </main>
      <Footer />
    </div>
  </div>
</template>

