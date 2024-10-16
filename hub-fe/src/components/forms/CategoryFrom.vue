<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';

import { useRouter } from 'vue-router';

import fetchData from '@/lib/fetchApi' 

</script>

<template>
    <form class="space-y-4" @submit.prevent="saveCategory">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="folder">Folder Name</Label>
        <Input name="folder" id="folder" v-model="folder" />
        <Button>Create Folder</Button>
    </form>
</template>

<script>
export default {
    name: 'CategoryForm',
    props: {
        parentFolderId: null
    },
    data() {
        return {
            folder: null,
            message: null,
            router: useRouter(),
        }
    },
    methods: {
        async saveCategory() { 
            fetchData('category', { name: this.folder, parent_id: this.parentFolderId }, "POST")
            .then(json => { 
                if(json.hasOwnProperty('message')) {  
                    this.message = json.message
                }
                else {
                    this.folder = null;
                    this.message = null;
                    this.$emit('category-saved'); 
                }
            });
        }, 
    },   
}
</script>
