<script setup>
defineProps(['type'])
</script>

<template>
  <div v-if="type == 'error'" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-300" role="alert">
     <slot />
  </div>
</template>