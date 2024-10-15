<script setup> 
import Label from '@/components/ui/Label.vue'
import Input from '@/components/ui/Input.vue'
import Button from '@/components/ui/Button.vue'
import Alert from '@/components/ui/Alert.vue'
import { RouterLink, useRouter } from 'vue-router';
import fetchData from '@/lib/fetchApi'
</script>

<template>

  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
    <div
      class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
      <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
          Register
        </h1>
        <form class="space-y-4 md:space-y-6" @submit.prevent="handleSubmit">
          <Alert type="error" v-if="errors">{{ errors }}</Alert>  
          <div>
            <Label for="email">Your Name</Label>
            <Input type="text" name="name" id="name" placeholder="John Doe" required v-model="name" />
          </div>
          <div>
            <Label for="email">Your Email</Label>
            <Input type="email" name="email" id="email" placeholder="name@company.com" required v-model="email"/>
          </div>
          <div>
            <Label for="password">Password</Label>
            <Input type="password" name="password" id="password" placeholder="••••••••" required v-model="password"/>
          </div>
          <div>
            <Label for="password_confirmation">Confirm Password</Label>
            <Input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required v-model="password_confirmation"/>
          </div>
          <Button type="submit">Register</Button>

          <p class="text-sm font-light text-gray-500 dark:text-gray-400">
            Already registered? <RouterLink to="/login"
              class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login</RouterLink>
          </p>
        </form>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: 'Signup',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      errors: null,
      router: useRouter(),
    }
  },
  methods: {
    async handleSubmit(){ 
      const data = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      }


      fetchData('register', data, "POST")
      .then(json => {
        console.log(json)

        if(json.hasOwnProperty('errors')) {   
          this.errors = json.message; 
        } else { 
          this.router.push('/');
        } 
      })
     
    }
  }
}
</script>