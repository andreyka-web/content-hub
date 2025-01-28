<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';
import { useRouter } from 'vue-router';
import fetchData from '@/lib/fetchApi'

const { parentFolderId } = defineProps(['parentFolderId']);
const emits = defineEmits(['category-saved']);

var folder = null;
var message = null;
const router = useRouter();

const saveCategory = async () => {
    console.log(folder, parentFolderId);
    fetchData('category', { name: folder, parent_id: parentFolderId }, "POST")
        .then(json => {
            if (json.hasOwnProperty('message')) {
                message = json.message
            }
            else {
                folder = null;
                message = null;
                emits('category-saved');
            }
        });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="saveCategory">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="folder">Folder Name</Label>
        <Input name="folder" id="folder" v-model="folder" />
        <Button>Create Folder</Button>
    </form>
</template> 
