<script setup>
import Button from '../ui/Button.vue';

const emits = defineEmits(['delete-confirmed', 'modal-close']);
const props = defineProps({name: String}); 

</script>

<template>
    <div>
        <h3>Delete {{ props.name }}</h3>
        <p>Are you sure?</p>
        <p>This operation is irreversible!</p>
    </div>
    <div class="flex justify-end space-x-2" >
        <Button type="button" @click.stop="emits('delete-confirmed')">Yes</Button>
        <Button type="button" @click.stop="emits('modal-close')">Cancel</Button>
    </div>
</template>

