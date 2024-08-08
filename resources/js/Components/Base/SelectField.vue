<script setup>
    const props = defineProps({
        selectOptions: {
            required: true
        }
    });

    console.log(props.selectOptions);
</script>

<template>
    <select class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out">
        <option class="p-2"
                v-for="option in props.selectOptions" :value="option.value">
            {{ option.value }}
        </option>
    </select>
</template>
