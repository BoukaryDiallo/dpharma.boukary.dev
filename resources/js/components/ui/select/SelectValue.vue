<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

export interface SelectValueProps {
  placeholder?: string;
  class?: string;
}

const props = withDefaults(defineProps<SelectValueProps>(), {
  placeholder: 'Select an option',
  class: '',
});

const classes = computed(() =>
  cn('text-left', props.class)
);
</script>

<template>
  <span :class="classes">
    <slot>{{ placeholder }}</slot>
  </span>
</template>
