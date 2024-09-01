<script setup>
    const props = defineProps({
        options: {
            type: [Object, Array],
            required: true
        },
        locale: {
            type: String,
            default: 'en'
        }
    });

    const model = defineModel({
        type: String,
        required: true
    });
</script>

<template>
    <select v-model="model"
            class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
            ref="select">
        <template v-for="option in props.options">
            <option v-if="locale === 'bs'" :value="option.value">{{ option.textBS }}</option>
            <option v-else :value="option.value">{{ option.text }}</option>
        </template>
    </select>
</template>
