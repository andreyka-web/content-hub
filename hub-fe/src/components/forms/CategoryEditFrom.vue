<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';

import fetchData from '@/lib/fetchApi'

const { activeFolder } = defineProps(['activeFolder']);
const emits = defineEmits(['category-saved']);

var message = null;

async function saveCategory() {
    const data = {
        name: activeFolder.name,
        parent_id: activeFolder.parent_id
    };

    fetchData(`category/${activeFolder.id}`, data, "PUT")
        .then(json => { 
            message = json.hasOwnProperty('message') ? json.message : null;
            if (message == null) {
                emits('category-saved');
            }
        });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="saveCategory">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="folder">Folder Name</Label>
        <Input name="folder" id="folder" v-model="activeFolder.name" />
        <Button>Rename Folder</Button>
    </form>
</template>
