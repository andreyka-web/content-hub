<script setup>
import fetchData from '@/lib/fetchApi'
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue'
import Input from './ui/Input.vue';
import Label from './ui/Label.vue'
</script>

<template>
  <h3 class="text-white py-3 text-2xl">My Files</h3>

  <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
      <div class="w-full md:w-1/2 text-left dark:text-white">
        <p>/{{ currentTree.join('/') }}</p>
      </div>
      <div
        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
        <button type="button"
          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
          upload file
        </button>

        <button type="button" @click="openCategoryForm()"
          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
          new folder
        </button>

      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-4 py-3">Path</th>
            <th scope="col" class="px-4 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody>

          <tr class="border-b dark:border-gray-700" v-if="parentFolder.length > 0" @click="levelUp">
            <th scope="row"
              class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white cursor-pointer hover:opacity-50"
              colspan="2">
              <span>..</span>
            </th>
          </tr>

          <tr class="border-b dark:border-gray-700" v-for="folder in categories">
            <th scope="row"
              class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white cursor-pointer hover:opacity-50"
              @click="setCurrentPath(folder.id)">
              <span class="p-2">{{ folder.name }}/</span>
            </th>
            <td class="px-4 py-3 flex items-end justify-end">
              <Button type="button">Edit</Button>
              <Button type="button">Delete</Button>
            </td>
          </tr>

          <tr class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              <span class="p-2">Filename</span>
            </th>
            <td class="px-4 py-3 flex items-center justify-end">
              <button id="apple-imac-20-dropdown-button" data-dropdown-toggle="apple-imac-20-dropdown"
                class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                type="button">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
              </button>
              <div id="apple-imac-20-dropdown"
                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                  aria-labelledby="apple-imac-20-dropdown-button">
                  <li>
                    <a href="#"
                      class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</a>
                  </li>
                  <li>
                    <a href="#"
                      class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                  </li>
                </ul>
                <div class="py-1">
                  <a href="#"
                    class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                </div>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <Modal isOpen="{{ newCategoryForm }}" title="Create Folder">
      <form class="space-y-4">
        <Label for="folder">Folder Name</Label>
        <Input name="folder" id="folder"/>
        <Button>save</Button>
      </form>
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
      newCategoryForm: false
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
    //
    openCategoryForm(){
      this.newCategoryForm = true;
    },
    createFolder() {
      fetchData('category', { name: "New", parent_id: this.parentFolder.slice(-1)[0] }, "POST").then(json => {

      });
    }
  },
  created() {
    this.readFolder();
  }
}
</script>