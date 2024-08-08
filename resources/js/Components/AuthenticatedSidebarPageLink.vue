<script setup>
    import { computed } from 'vue'
    import { Link } from '@inertiajs/vue3'

    const props = defineProps({
        href: {
            type: String,
            required: true
        },
        active: {
            type: Boolean
        }
    });

    const classes = computed(() =>
        props.active
            ? 'flex block bg-slate-700 hover:bg-slate-600 rounded transition-all ease-out duration-300'
            : 'flex block hover:bg-slate-600 rounded transition-all ease-out duration-300'
    );
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
