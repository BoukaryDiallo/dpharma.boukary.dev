<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

export interface SelectContentProps {
  class?: string;
  position?: 'item-aligned' | 'popper';
  sideOffset?: number;
  align?: 'center' | 'end' | 'start';
  side?: 'top' | 'right' | 'bottom' | 'left';
}

const props = withDefaults(defineProps<SelectContentProps>(), {
  class: '',
  position: 'item-aligned',
  sideOffset: 4,
  align: 'center',
  side: 'bottom',
});

const classes = computed(() =>
  cn(
    'relative z-50 min-w-[8rem] overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95',
    props.position === 'popper' && 'data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1',
    props.class
  )
);
</script>

<template>
  <div :class="classes">
    <slot />
  </div>
</template>
