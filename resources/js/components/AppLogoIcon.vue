<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" :class="className" v-bind="$attrs">
  <!-- Croix pharmacie stylisée -->
        <rect x="16" y="4" width="8" height="32" rx="3" fill="currentColor" />
        <rect x="4" y="16" width="32" height="8" rx="3" fill="currentColor" />
        <!-- Feuille stylisée au centre -->
        <path d="M20 15c2 2 4 5 0 10-4-5-2-8 0-10z" fill="currentColor" fill-opacity="0.7" />
    </svg>
</template>
