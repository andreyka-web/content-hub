<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';

import { useRouter } from 'vue-router';

import { getHeaders } from '@/lib/fetchApi'

</script>

<template>
    <form class="space-y-4" @submit.prevent="saveFile">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="file">Select File</Label>
        <Input name="file" id="file" type="file" @change="UpdateFile" />
        <Button>Upload</Button>
    </form>
</template>

<script>
export default {
    name: 'FileUploadForm',
    props: {
        parentFolderId: null
    },
    data() {
        return {
            file: null,
            message: null,
            router: useRouter(),
            UpdateFile: (event) => { this.file = event.target.files[0]}
        }
    },
    methods: {
        async saveFile() { 
            const fileData = new File([this.file], this.file.name, { type: this.file.type });

            var data = new FormData()
            data.append('file', fileData)
            if(this.parentFolderId) {
                data.append('category', this.parentFolderId)
            }

            const headers = getHeaders()  

            fetch('http://localhost:8080/api/file', {
                headers: getHeaders(),
                body: data,
                method: "POST",
                // mode: 'no-cors',
            })
                .then(res => res.json())
                .then(json => { 
                    if (json.hasOwnProperty('errors')) {
                        this.message = json.message
                    }
                    else {
                        this.folder = null;
                        this.message = null;
                        this.$emit('file-saved');
                    }
                })
                .catch(e => console.log);

        },
    },
}
</script>
