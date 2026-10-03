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
  <h1 class="text-3xl my-3 font-bold dark:text-white pb-4">Welcome to content hub</h1>

  <div class="text-left max-w-80 m-auto dark:text-white p-3 border border-gray-300 rounded-lg">
    <p>Allowed file types: pdf, txt, docx, ppt, xls</p>
    <p>Max size: 2Mb</p>

    <p v-if="!authStore.isLoggedIn" class="pt-3 dark:text-white"> 
      <RouterLink  to="/login" class="text-blue-500 hover:underline">Login</RouterLink> to start working with the content hub.
    </p>

    <p v-if="authStore.isLoggedIn" class="pt-3"> 
    <RouterLink to="/files" class="text-blue-500 hover:underline ">Go to files</RouterLink>
    </p>
  </div>
</template>
