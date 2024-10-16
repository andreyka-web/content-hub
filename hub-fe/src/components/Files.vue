<script setup>
import fetchData, { fetchFile } from '@/lib/fetchApi'
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue' 

import { ref } from 'vue';
import CategoryFrom from '@/components/forms/CategoryFrom.vue';
import FileUploadForm from './forms/FileUploadForm.vue';
import FileIcon from './icons/fileIcon.vue';
import FolderIcon from './icons/folderIcon.vue';
import FileEditForm from './forms/FileEditForm.vue';
import CategoryEditFrom from './forms/CategoryEditFrom.vue';
</script>

<template>
  <h3 class="text-white py-3 text-2xl">My Files</h3>

  <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
      
      <div class="flex flex-row justify-end whitespace-nowrap space-x-2">
        <Button type="button" @click="openModal('file')">Upload File</Button>
        <Button type="button" @click="openModal('folder')">New Folder</button>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-4 py-3">
              <p>/{{ currentTree.join('/') }}</p>
            </th>
            <th scope="col" class="px-4 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody>

          <tr class="border-b dark:border-gray-700 hover:bg-gray-100 cursor-pointer" v-if="parentFolder.length > 0" @click="levelUp">
            <th scope="row"
              class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              colspan="2">
              <span class="text-xl  ">..</span>
            </th>
          </tr>

          <tr class="border-b dark:border-gray-700 hover:bg-gray-100" v-for="folder in categories">
            <th scope="row" class="w-full px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white cursor-pointer "
              @click="setCurrentPath(folder.id)">
              <FolderIcon class="float-left pe-2  "/>
              <span class="p-2">{{ folder.name }}/</span>
            </th>
            <td class="px-2 py-2 flex items-end justify-end space-x-2">
              <Button type="button" @click="openFolderEditForm(folder)">Edit</Button>
              <Button type="button" @click="deleteItem('category/'+folder.id)">Delete</Button>
            </td>
          </tr>

          <tr class="border-b dark:border-gray-700 hover:bg-gray-100" v-for="file in files">
            <th scope="row" class="w-full px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              <FileIcon class="float-left pe-2 "/>
              <a class="p-2 text-blue-700" @click="fileShow(file.id)">{{ file.filename }}</a>
            </th>
            <td class="px-2 py-2 flex items-end justify-end space-x-2">
              <Button type="button" @click="openFileEditForm(file)">Edit</Button>
              <Button type="button" @click="deleteItem(`file/${file.id}`)">Delete</Button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal :isOpen="isModalOpen" @modal-close="closeModal" v-bind:formId="activeForm">
      <CategoryFrom v-if="activeForm == 'folder'" @category-saved="itemCreated" :parentFolderId />
      <CategoryEditFrom v-if="activeForm == 'folderEdit'" @category-saved="itemCreated" :activeFolder />
      <FileUploadForm v-if="activeForm == 'file'" @file-saved="itemCreated" :parentFolderId />
      <FileEditForm v-if="activeForm == 'editFile'" @file-saved="itemCreated" :activeFile />
    </Modal>
  </div>

</template>


<script>
export default {
  name: 'Files',
  data() {
    return {
      categories: null,
      files: null,
      currentTree: [],
      parentFolder: [],
      parentFolderId: null,
      isModalOpen: ref(false),
      activeForm: null, 
      activeFile: null,
      activeFolder: null,
      closeModal: () => {
        this.isModalOpen = false;
      }
    }
  },
  methods: {
    // add selected folder id and name to the current tree array
    setCurrentPath(id) {
      const currentCategory = this.categories.find((c) => c.id == id)
      this.currentTree.push(currentCategory.name)
      this.parentFolder.push(currentCategory.id)
      this.readFolder(id);
    },
    // move up in folder tree
    levelUp() {
      this.currentTree.pop()
      this.parentFolder.pop()
      let id = this.parentFolder.slice(-1)[0]
      this.readFolder(id);
    },
    // get current folder content
    readFolder(id = null) {
      this.categories = null;
      this.files = null;

      const url = id == null ? 'read' : `read/${id}`;

      fetchData(url, {}, "GET").then(json => {
        if (json.hasOwnProperty('errors')) {
          this.errors = json.message;
        } else {
          this.categories = Object.values(json.folders)
          this.files = Object.values(json.files)
        }
      })
    },
    itemCreated() {
      this.closeModal();
      this.readFolder(this.parentFolderId); 
    },
    openModal(form) {
      console.log(form)
      this.activeForm = form;
      this.isModalOpen = true;
      this.parentFolderId =  this.parentFolder.slice(-1)[0]
    }, 
    deleteItem(path) { 
      fetchData(path, {}, "DELETE").then(json => { 
        this.readFolder(this.parentFolderId)
      });
    },
    openFileEditForm(file) { 
      this.activeFile = file; 
      this.openModal('editFile');
    },
    openFolderEditForm(folder) {
      this.activeFolder = folder; 
      this.openModal('folderEdit');
    },
    async fileShow(id) {
      const filePreviewWindow = window.open('about:blank');
      try {
        const url = await fetchFile(`file/${id}`);
        filePreviewWindow.location.href = url;
        filePreviewWindow.focus();
      } catch(e) {
        filePreviewWindow.close();
        console.log(e)
      }
    }
  },
  created() { 
    this.readFolder();
  }

}
</script>