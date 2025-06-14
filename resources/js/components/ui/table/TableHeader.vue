<script setup lang="ts">
import { cn } from '@/lib/utils';

export interface TableHeaderProps {
  class?: string;
}

defineProps<TableHeaderProps>();
</script>

<template>
  <thead :class="cn('border-b border-gray-200', $attrs.class)">
    <tr>
      <slot />
    </tr>
  </thead>
</template>
