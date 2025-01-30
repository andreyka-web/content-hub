<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';
import { api_url } from '@/lib/fetchApi'
import { ref, inject } from 'vue';

const { activeFile } = defineProps(['activeFile']);
const emits = defineEmits(['file-saved']);
 
var message = ref(null);
var name = ref(activeFile.filename);
var category = ref(activeFile.category_id);

const getHeaders = inject('getHeaders');

async function saveFile() {
    const formData = new FormData() 
    formData.append('name', name.value)
    formData.append('category', category.name) 

    const jsonBody = JSON.stringify({
                'name': name.value,
                'category': category.value
            });

    try {
        const headers = getHeaders();
        headers['Content-Type'] = 'application/json';
        headers['Content-Length'] = jsonBody.length.toString();
        const response = await fetch(`${api_url}file/${activeFile.id}`, {
            method: 'PUT',
            body: jsonBody,
            headers: headers
        });
        if(!response.ok) {
            const json = await response.json();
            message.value = json.message;
        }
        else {
            emits('file-saved');
            message.value = null; 
            name.value = null;
            category.value = null;
        }
    } catch(error) {
        console.error(error);
        message.value = error.message;
    }
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="saveFile">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="filename">Rename File</Label>
        <Input name="filename" id="filename" type="text" v-model="name" />
        <Button>Save File</Button>
    </form>
</template>