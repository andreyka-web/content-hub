<script setup>
import Label from '@/components/ui/Label.vue'
import Input from '@/components/ui/Input.vue'
import Button from '@/components/ui/Button.vue'
import Alert from '@/components/ui/Alert.vue'
import { RouterLink, useRouter } from 'vue-router';
import { useAuthStore } from '@/store/authStore';

import fetchData from '@/lib/fetchApi'

</script>

<template>

  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
    <div
      class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
      <div class="p-6 space-y-4 md:space-y-6 sm:p-8"> 
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
          Log in to your account
        </h1>
        <form class="space-y-4 md:space-y-6"  @submit.prevent="handleSubmit">
          <Alert type="error" v-if="errors">{{ errors }}</Alert>  
          <div>
            <Label for="email">Your Email</Label>
            <Input type="email" name="email" id="email" placeholder="name@company.com" required v-model="email" autocomplete="email"/>
          </div>
          <div>
            <Label for="password">Password</Label>
            <Input type="password" name="password" id="password" placeholder="••••••••" required v-model="password" autocomplete="current-password"/>
          </div>
          <Button type="submit">Login</Button>

          <p class="text-sm font-light text-gray-500 dark:text-gray-400">
            Don’t have an account yet? <RouterLink to="/signup"
              class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</RouterLink>
          </p>
        </form>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: 'LoginForm', 
  data() {
    return {
      email: '',
      password: '',
      errors: null,
      router: useRouter(),
      authStore: useAuthStore(), 
    }
  },
  methods: {
    async handleSubmit(){ 
      const data = { 
        email: this.email,
        password: this.password,
      }
      
      fetchData('login', data, "POST").then(json => { 
        if(json.hasOwnProperty('message')) {  
          this.errors = json.message;
        } else {
          this.authStore.login(json.token, json.user.name)  
          this.router.push('/');
        } 
      })
      
    }
  }
}
</script>