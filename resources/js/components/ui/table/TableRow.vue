<script setup lang="ts">
import { cn } from '@/lib/utils';

export interface TableRowProps {
  class?: string;
}

defineProps<TableRowProps>();
</script>

<template>
  <tr :class="cn('border-b border-gray-200 transition-colors hover:bg-gray-50/50 data-[state=selected]:bg-gray-100', $attrs.class)">
    <slot />
  </tr>
</template>
