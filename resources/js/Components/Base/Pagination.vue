<script setup>
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        links: {
            type: Array,
            required: true
        }
    });

</script>

<template>
    <div class="flex flex-wrap">
        <template v-for="link in props.links">
            <Link class="bg-white border border-gray-100 text-gray-500 px-3 py-1 hover:bg-gray-300 transition-colors ease-out" :class="link.active ? 'font-bold !bg-indigo-500 text-white' : ''" v-if="link.url" :href="link.url" v-html="link.label" />
            <span class="bg-white px-3 py-1 text-gray-300 select-none" v-else v-html="link.label"></span>
        </template>
    </div>
</template>

