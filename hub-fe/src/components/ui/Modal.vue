<script setup> 
import Button from "./Button.vue";
import closeIcon from "../icons/closeIcon.vue";
 
var props = defineProps({
  formId: String,
  isOpen: Boolean
}) 

const emit = defineEmits(['modal-close'])
 
const titles = Object.freeze({
  'file': "Upload File",
  'folder': "Create Folder",
  'folderEdit': "Rename Folder",
  'editFile': "Update File",
  'deleteFolder': "Delete Folder",
  'deleteFile': "Delete File"
}); 
 
</script>

<template>
  <div  
    v-if="isOpen"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-30">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ titles[formId] }}</h3>
          <Button type="button" class="bg-gray hover:bg-gray-700 px-2 py-2" @click.stop="emit('modal-close')">
            <closeIcon />
          </Button>
        </div>
        <div class="p-4 md:p-5 space-y-4">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>