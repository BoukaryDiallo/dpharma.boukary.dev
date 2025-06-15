<template>
    <div :class="[
    'rounded-lg sm:rounded-xl border p-3 sm:p-4 shadow-sm',
    typeClasses[type].background,
    typeClasses[type].border
  ]">
        <div class="flex items-start space-x-2 sm:space-x-3">
            <div :class="[
        'flex-shrink-0 p-1.5 sm:p-2 rounded-lg',
        typeClasses[type].iconBackground
      ]">
                <component :is="iconComponent" :class="[
          'h-4 w-4 sm:h-5 sm:w-5',
          typeClasses[type].iconColor
        ]" />
            </div>
            <div class="flex-1 min-w-0">
                <h4 :class="[
          'text-sm font-semibold',
          typeClasses[type].titleColor
        ]">
                    {{ title }}
                </h4>
                <p :class="[
          'text-xs sm:text-sm mt-1',
          typeClasses[type].messageColor
        ]">
                    {{ message }}
                </p>
                <button
                    v-if="action"
                    :class="[
            'text-xs sm:text-sm font-medium mt-2 hover:underline',
            typeClasses[type].actionColor
          ]"
                    @click="handleClick"
                >
                    {{ action }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { AlertTriangle, CheckCircle, Info, XCircle } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

type AlertType = 'warning' | 'info' | 'success' | 'error'

interface Props {
    type: AlertType;
    title: string;
    message: string;
    action?: string;
}

const props = defineProps<Props>();

const handleClick = () => {
    router.visit('pharmaceutical-products');
    // $emit('click')
};
const typeClasses = {
    warning: {
        background: 'bg-yellow-50 dark:bg-yellow-900/20',
        border: 'border-yellow-200 dark:border-yellow-800',
        iconBackground: 'bg-yellow-100 dark:bg-yellow-900/40',
        iconColor: 'text-yellow-600 dark:text-yellow-400',
        titleColor: 'text-yellow-800 dark:text-yellow-200',
        messageColor: 'text-yellow-700 dark:text-yellow-300',
        actionColor: 'text-yellow-600 dark:text-yellow-400'
    },
    info: {
        background: 'bg-blue-50 dark:bg-blue-900/20',
        border: 'border-blue-200 dark:border-blue-800',
        iconBackground: 'bg-blue-100 dark:bg-blue-900/40',
        iconColor: 'text-blue-600 dark:text-blue-400',
        titleColor: 'text-blue-800 dark:text-blue-200',
        messageColor: 'text-blue-700 dark:text-blue-300',
        actionColor: 'text-blue-600 dark:text-blue-400'
    },
    success: {
        background: 'bg-green-50 dark:bg-green-900/20',
        border: 'border-green-200 dark:border-green-800',
        iconBackground: 'bg-green-100 dark:bg-green-900/40',
        iconColor: 'text-green-600 dark:text-green-400',
        titleColor: 'text-green-800 dark:text-green-200',
        messageColor: 'text-green-700 dark:text-green-300',
        actionColor: 'text-green-600 dark:text-green-400'
    },
    error: {
        background: 'bg-red-50 dark:bg-red-900/20',
        border: 'border-red-200 dark:border-red-800',
        iconBackground: 'bg-red-100 dark:bg-red-900/40',
        iconColor: 'text-red-600 dark:text-red-400',
        titleColor: 'text-red-800 dark:text-red-200',
        messageColor: 'text-red-700 dark:text-red-300',
        actionColor: 'text-red-600 dark:text-red-400'
    }
};

const iconComponents = {
    warning: AlertTriangle,
    info: Info,
    success: CheckCircle,
    error: XCircle
};

const iconComponent = computed(() => iconComponents[props.type]);
</script>
