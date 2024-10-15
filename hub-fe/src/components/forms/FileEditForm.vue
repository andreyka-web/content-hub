<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue'; 

import fetchData from '@/lib/fetchApi'
</script>

<template>
    <form class="space-y-4" @submit.prevent="saveFile">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="filename">Rename File</Label>
        <Input name="filename" id="filename" type="text" v-model="name"/>
        <Label for="file">Change File</Label>
        <Input name="file" id="file" type="file" @change="UpdateFile" />
        <Button>Save File</Button>
    </form>
</template>

<script>
export default {
    name: 'FileEditForm',
    props: {
        activeFile: null
    },
    data() {
        return {
            file: null,
            category: this.activeFile.category_id,
            name: this.activeFile.filename,
            message: null, 
            UpdateFile: (event) => { this.file = event.target.files[0]}
        }
    },
    methods: {
        async saveFile() {
            var data = new FormData()
            if( this.file ) {
                const fileData = new File([this.file], this.file.name, { type: this.file.type });
                data.append('file', fileData) 
            }
            data.append('category', this.category) 
            data.append('name', this.name)  

            fetchData(`file/${this.activeFile.id}`, data, "PUT", true)
                .then(json =>  {
                    this.message = json.hasOwnProperty('errors') ? json.message : null; 
                    if(this.message == null){
                        this.$emit('file-saved');
                    }
                });
        },
    },
}
</script>
