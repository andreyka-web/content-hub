<script setup>
import Alert from '../ui/Alert.vue';
import Label from '../ui/Label.vue';
import Input from '../ui/Input.vue';
import Button from '../ui/Button.vue';

import fetchData from '@/lib/fetchApi'

</script>

<template>
    <form class="space-y-4" @submit.prevent="saveCategory">
        <Alert type="error" v-if="message">{{ message }}</Alert>
        <Label for="folder">Folder Name</Label>
        <Input name="folder" id="folder" v-model="activeFolder.name" />
        <Button>Rename Folder</Button>
    </form>
</template>

<script>
export default {
    name: 'CategoryEditForm',
    props: {
        activeFolder: null
    },
    data() {
        return {
            message: null,
        }
    },
    methods: {
        async saveCategory() {
            const data = {
                name: this.activeFolder.name,
                parent_id: this.activeFolder.parent_id
            };

            fetchData(`category/${this.activeFolder.id}`, data, "PUT")
                .then(json => {
                    this.message = json.hasOwnProperty('message') ? json.message : null;
                    if (this.message == null) {
                        this.$emit('category-saved');
                    }
                });
        },
    },
}
</script>
