<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';
import fetchData from '@/lib/fetchApi'

const { activeFile } = defineProps(['activeFile']);
const emits = defineEmits(['file-saved']); 

var file = null; 
var message = null;
var name = activeFile.filename.split('.').slice(0, -1).join('.');
const UpdateFile = (event) => { file = event.target.files[0] }

async function saveFile() {
    var data = new FormData()
    if (file) {
        const fileData = new File([file], file.name, { type: file.type });
        data.append('file', fileData)
    }
    data.append('category', activeFile.category_id)
    data.append('name', name)

    fetchData(`file/${activeFile.id}`, data, "PUT", true)
        .then(json => {
            message = json.hasOwnProperty('errors') ? json.message : null;
            if (message == null) {
                emits('file-saved');
            }
        });
};

</script>

<template>
    <form class="space-y-4" @submit.prevent="saveFile">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="filename">Rename File</Label>
        <Input name="filename" id="filename" type="text" v-model="name" />
        <Label for="file">Replace File <small class="font-thin ">(old file will be lost)</small></Label>
        <Input name="file" id="file" type="file" @change="UpdateFile" />
        <Button>Save File</Button>
    </form>
</template>