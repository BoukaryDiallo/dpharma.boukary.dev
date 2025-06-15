<template>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 flex items-center">
            <div :class="`${color} text-white p-3 rounded-full`">
                <component :is="iconComponent" class="h-6 w-6" />
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">{{ title }}</h3>
                <div class="text-2xl font-semibold text-gray-900">{{ value }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, type Component } from 'vue';
import {
    DollarSignIcon,
    ShoppingBagIcon,
    UsersIcon,
    Circle
} from 'lucide-vue-next';

interface Props {
    title: string;
    value: number | string;
    icon: string;
    color?: string;
}

const props = withDefaults(defineProps<Props>(), {
    color: 'bg-green-500'
});

const iconComponent = computed<Component>(() => {
    switch (props.icon) {
        case 'currency-dollar': return DollarSignIcon;
        case 'shopping-bag': return ShoppingBagIcon;
        case 'users': return UsersIcon;
        case 'exclamation-circle': return Circle;
        default: return CurrencyDollarIcon;
    }
});
</script>
