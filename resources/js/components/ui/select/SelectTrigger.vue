<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

export interface SelectTriggerProps {
  class?: string;
  disabled?: boolean;
}

const props = withDefaults(defineProps<SelectTriggerProps>(), {
  class: '',
  disabled: false,
});

const classes = computed(() =>
  cn(
    'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
    props.class
  )
);
</script>

<template>
  <div :class="classes" :disabled="disabled">
    <slot />
  </div>
</template>
