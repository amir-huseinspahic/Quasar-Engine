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
    <div>
        <template v-for="link in props.links">
            <Link class="px-3 py-1 border rounded " :class="link.active ? 'font-bold' : ''" v-if="link.url" :href="link.url" v-html="link.label" />
            <span class="px-3 py-1 border rounded text-gray-400 select-none" v-else v-html="link.label"></span>
        </template>
    </div>
</template>

