<script setup>
  defineProps({
    type: String, 
    name: String, 
    id: String, 
    placeholder: String, 
    required: Boolean = false,
    modelValue: String
  })
</script>

<template>
  <input 
    :type 
    :name  
    :id 
    :placeholder 
    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
    :required  
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>