<script setup>
 defineProps(['for']) 
</script>

<template>
  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-left" :for>
    <slot />
  </label>
</template>

