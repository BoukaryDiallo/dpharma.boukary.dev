<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

export interface SelectItemProps {
  value: string | number | boolean | null;
  disabled?: boolean;
  class?: string;
}

const props = withDefaults(defineProps<SelectItemProps>(), {
  disabled: false,
  class: '',
});

const classes = computed(() =>
  cn(
    'relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
    props.class
  )
);
</script>

<template>
  <option :value="value" :disabled="disabled" :class="classes">
    <slot />
  </option>
</template>
