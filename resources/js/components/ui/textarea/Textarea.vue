<script setup lang="ts">
import { computed } from 'vue';

export interface TextareaProps {
    modelValue?: string;
    placeholder?: string;
    disabled?: boolean;
    error?: boolean;
    class?: string;
    rows?: number | string;
}

const props = withDefaults(defineProps<TextareaProps>(), {
    modelValue: '',
    placeholder: '',
    disabled: false,
    error: false,
    class: '',
    rows: 3
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
});
</script>

<template>
  <textarea
      v-model="value"
      :class="[
      'flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
      props.error ? 'border-destructive focus-visible:ring-destructive' : 'border-input',
      props.class
    ]"
      :placeholder="placeholder"
      :disabled="disabled"
      :rows="rows"
      v-bind="$attrs"
  />
</template>
