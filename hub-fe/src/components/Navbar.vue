<template>
  <nav class="border-gray-600 border-b">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-4">
      <RouterLink :to="{ name: authStore.isLoggedIn ? 'files' : 'welcome' }" class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Content Hub</span>
      </RouterLink>
      <button data-collapse-toggle="navbar-default" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul
          class="flex flex-col items-center md:flex-row md:space-x-4 md:items-center md:justify-end space-y-4 md:space-y-0">
          <li v-if="authStore.isLoggedIn && authStore.username">
            <span class="dark:text-white "><span class="font-medium opacity-80">You are logged in as:</span> <b>{{ authStore.username }}</b></span>
          </li> 
          <li>
            <RouterLink v-if="!authStore.isLoggedIn" to="/login"
              class="p-2 text-blue-400 hover:text-blue-600">Login</RouterLink>
          </li>
          <li>
            <RouterLink v-if="!authStore.isLoggedIn" to="/signup"
              class="p-2 text-blue-400 hover:text-blue-600">Sign up</RouterLink>
          </li>
          <li v-if="authStore.isLoggedIn">
            <a href="javascript:void(0)" @click="handleLogout"
              class="p-2 text-blue-400 hover:text-blue-600">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>


<script setup> 
import { RouterLink, useRouter } from 'vue-router'; 
import { useAuthStore } from '@/store/authStore';

const router = useRouter()
const authStore = useAuthStore() 

const handleLogout = () => {
  authStore.logout() 
  router.replace('/')
}
</script>