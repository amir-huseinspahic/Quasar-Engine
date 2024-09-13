<script setup>
    import { ref } from 'vue';

    import DropdownLink from '@/Components/Base/DropdownLink.vue';
    import { ChevronDownIcon } from '@heroicons/vue/24/solid/index.js'

    const isDropdownExpanded = ref(false);
    function toggleDropdown() {
        isDropdownExpanded.value = !isDropdownExpanded.value;
    }
</script>

<template>
    <div class="relative inline-block text-left">
        <div>
            <button type="button" class="bg-gray-50 inline-flex w-full justify-center gap-x-1.5 rounded-md px-3 py-2 text-sm font-semibold text-gray-700 items-center hover:bg-gray-50" id="menu-button" aria-expanded="true" aria-haspopup="true" @click="toggleDropdown">
                <img class="w-8 h-auto" :src="'/media/users/avatars/' + $page.props.auth.user.id + '.png'" alt="">
                {{ $page.props.auth.user.name }}
                <ChevronDownIcon class="-mr-1 ml-1 size-5 text-gray-400 transform transition-all ease-out lg:mr-1 lg:ml-2" :class="isDropdownExpanded ? 'rotate-180' : 'rotate-0'" />
            </button>
        </div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
        <div class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" v-show="isDropdownExpanded">
            <div class="py-1" role="none">
                <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                <DropdownLink :href="route('profile.show')">{{ $t('Profile') }}</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">{{ $t('Log out') }}</DropdownLink>
            </div>
        </div>
        </Transition>
    </div>
</template>
