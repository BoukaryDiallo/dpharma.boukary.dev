<template>
    <button
        @click="toggleTheme"
        class="relative inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition-colors duration-200 hover:bg-gray-50 hover:text-gray-900 sm:h-9 sm:w-9 lg:h-10 lg:w-10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        :title="appearance === 'dark' ? 'Passer au thème clair' : 'Passer au thème sombre'"
    >
        <Sun v-if="appearance === 'dark'" class="h-4 w-4 sm:h-5 sm:w-5" />
        <Moon v-else class="h-4 w-4 sm:h-5 sm:w-5" />
    </button>
</template>

<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();

const toggleTheme = () => {
    updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
};
</script>
