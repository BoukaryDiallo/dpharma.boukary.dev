<script setup lang="ts">
import { cn } from '@/lib/utils';

export interface TableHeadProps {
  class?: string;
}

defineProps<TableHeadProps>();
</script>

<template>
  <th :class="cn('h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0', $attrs.class)">
    <slot />
  </th>
</template>
