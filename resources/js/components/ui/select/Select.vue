<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

export interface SelectProps {
  modelValue?: string | number | boolean | null;
  disabled?: boolean;
  error?: boolean;
  class?: string;
}

const props = withDefaults(defineProps<SelectProps>(), {
  modelValue: undefined,
  disabled: false,
  error: false,
  class: '',
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | boolean | null): void;
}>();

const model = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
});

const classes = computed(() =>
  cn(
    'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
    props.error ? 'border-destructive' : '',
    props.class
  )
);
</script>

<template>
  <select
    v-model="model"
    :class="classes"
    :disabled="disabled"
    v-bind="$attrs"
  >
    <slot />
  </select>
</template>
