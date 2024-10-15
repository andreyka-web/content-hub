<script setup>  
import { RouterLink} from 'vue-router'; 
import { useAuthStore } from '@/store/authStore';
</script>

<script>
export default {
  name: 'Navbar',
  data() {
    return {  
      authStore: useAuthStore()
    }
  }, 
}
</script>

<template>
  <h1 class="text-2xl font-bold dark:text-white pb-4">Welcome to content hub</h1>

  <div class="text-left max-w-72 m-auto">
    <p>Allowed file types: pdf, txt, docx, ppt, xls</p>
    <p>Max size: 2Mb</p>

    <p v-if="!authStore.isLoggedIn" class="pt-3"> 
      <RouterLink  to="/login" class="text-blue-500 hover:underline">Login</RouterLink> to start working with the content hub.
    </p>

    <p v-if="authStore.isLoggedIn" class="pt-3"> 
    <RouterLink to="/files" class="text-blue-500 hover:underline ">Go to files</RouterLink>
    </p>
  </div>
</template>
