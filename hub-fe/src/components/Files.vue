<script setup>
import fetchData, { fetchFile } from '@/lib/fetchApi'
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue'

import { onMounted, ref } from 'vue';
import CategoryFrom from '@/components/forms/CategoryFrom.vue';
import FileUploadForm from './forms/FileUploadForm.vue';
import FileIcon from './icons/fileIcon.vue';
import FolderIcon from './icons/folderIcon.vue';
import FileEditForm from './forms/FileEditForm.vue';
import CategoryEditFrom from './forms/CategoryEditFrom.vue';
import DeleteConfirmation from './forms/DeleteConfirmation.vue';

var categories = ref([]);
var files = ref([]);
var currentTree = ref([]);
var parentFolder = ref([]);
var parentFolderId = ref(null);
var isModalOpen = ref(false);
var activeForm = ref('');
var activeFile = ref('');
var activeFolder = ref('');
var deletePath = ref('');

// get current folder content
function readFolder(id = null) {
  categories.value = [];
  files.value = [];
  const url = id == null ? 'read' : `read/${id}`;

  fetchData(url, {}, "GET").then(json => {
    if (json.hasOwnProperty('errors')) {
      errors = json.message;
      console.error(json.message);
    } else {
      categories.value = Object.values(json.folders)
      files.value = Object.values(json.files)
    }
  })

  parentFolderId.value = parentFolder.value.slice(-1)[0];
}

const currentPath = () => '<span class="opacity-75 pe-1">/</span>' + currentTree.value.join('<span class="opacity-75 px-1">/</span>');

// add selected folder id and name to the current tree array
const setCurrentPath = (id) => {
  const currentCategory = categories.value.find((c) => c.id == id)
  currentTree.value.push(currentCategory.name)
  parentFolder.value.push(currentCategory.id)
  readFolder(id); 
}

// move up in folder tree
const levelUp = () => {
  currentTree.value.pop()
  parentFolder.value.pop()
  let id = parentFolder.value.slice(-1)[0]
  readFolder(id);
}

function openModal(form) {
  activeForm.value = form;
  isModalOpen.value = true; 
}

const closeModal = () => isModalOpen.value = false;
const getActiveForm = () => activeForm.value;

var itemCreated = () => {
  closeModal();
  readFolder(parentFolderId.value);
}

const openFileEditForm = (file) => {
  activeFile.value = file;
  openModal('editFile');
}

function openFolderEditForm(folder) {
  activeFolder.value = folder;
  openModal('folderEdit');
}

function openDeleteItem(item) {
  let deleteForm = item.hasOwnProperty('filename') ? 'deleteFile' : 'deleteFolder';
  deletePath.value = item.hasOwnProperty('filename') ? `file/${item.id}` : `category/${item.id}`;
  openModal(deleteForm); 
}

const deleteItem = () => {
  fetchData(deletePath.value, {}, "DELETE").then(readFolder(parentFolderId.value));
  closeModal();
  readFolder(parentFolderId.value);
}

async function fileShow(id) {
  const filePreviewWindow = window.open('about:blank');
  try {
    const url = await fetchFile(`file/${id}`);
    filePreviewWindow.location.href = url;
    filePreviewWindow.focus();
  } catch (e) {
    filePreviewWindow.close();
    console.log(e)
  }
}

onMounted(() => { 
  readFolder();
});
</script>

<template>
  <h3 class="text-white py-3 text-2xl">My Files</h3>
  <div class="flex flex-row justify-end whitespace-nowrap space-x-2 mb-4">
    <Button type="button" @click="openModal('file')">Upload File</Button>
    <Button type="button" @click="openModal('folder')">New Folder</Button>
  </div>
  <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-ellipsis border-b dark:border-gray-700 text-gray-700 dark:text-gray-200">
          <tr>
            <th scope="col" class="px-4 py-3 font-thin">
              <p v-html="currentPath()"></p>
            </th>
            <th scope="col" class="px-4 py-3"><span class="sr-only">Actions</span></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b dark:border-gray-700 hover:bg-gray-500 cursor-pointer" v-if="parentFolder.length > 0"
            @click="levelUp">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white" colspan="2">
              <span class="text-xl">..</span>
            </th>
          </tr>
          <!-- Folders -->
          <tr class="border-b dark:border-gray-700 hover:bg-gray-600" v-for="folder in categories">
            <th scope="row"
              class="w-full px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white cursor-pointer "
              @click="setCurrentPath(folder.id)">
              <FolderIcon class="float-left pe-2" />
              <span class="p-2">{{ folder.name }}/</span>
            </th>
            <td class="px-2 py-2 flex items-end justify-end space-x-2">
              <Button type="button" @click="openFolderEditForm(folder)">Edit</Button>
              <Button type="button" @click="openDeleteItem(folder)">Delete</Button> 
            </td>
          </tr>
          <!-- Files -->
          <tr class="border-b dark:border-gray-700 hover:bg-gray-600" v-for="file in files">
            <th scope="row" class="w-full px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              <FileIcon class="float-left pe-2 " />
              <a class="p-2 text-blue-700 dark:text-blue-200" @click="fileShow(file.id)">{{ file.filename }}</a>
            </th>
            <td class="px-2 py-2 flex items-end justify-end space-x-2"> 
              <Button type="button" @click="openFileEditForm(file)">Edit</Button>
              <Button type="button" @click="openDeleteItem(file)">Delete</Button> 
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal :isOpen="isModalOpen" @modal-close="closeModal" v-bind:formId="getActiveForm()">
      <CategoryFrom v-if="getActiveForm() == 'folder'" @category-saved="itemCreated" :parentFolderId />
      <CategoryEditFrom v-if="getActiveForm() == 'folderEdit'" @category-saved="itemCreated" :activeFolder />
      <FileUploadForm v-if="getActiveForm() == 'file'" @file-saved="itemCreated" :parentFolderId />
      <FileEditForm v-if="getActiveForm() == 'editFile'" @file-saved="itemCreated" :activeFile />
      <DeleteConfirmation v-if="getActiveForm() == 'deleteFolder'" @delete-confirmed="deleteItem" @modal-close="closeModal"/>
    </Modal>
  </div>
</template>