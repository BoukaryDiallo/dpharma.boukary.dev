<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">
                    {{ title }}
                </p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mt-0.5 sm:mt-1 leading-tight">
                    {{ formattedValue }}
                </p>
                <div v-if="trend" class="flex items-center mt-1 sm:mt-2">
                    <TrendingUp
                        v-if="trend.isPositive"
                        class="h-3 w-3 sm:h-4 sm:w-4 text-green-500 mr-1 flex-shrink-0"
                    />
                    <TrendingDown
                        v-else
                        class="h-3 w-3 sm:h-4 sm:w-4 text-red-500 mr-1 flex-shrink-0"
                    />
                    <span
                        :class="[
              'text-xs sm:text-sm font-medium',
              trend.isPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
            ]"
                    >
            {{ trend.value }}%
          </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1 hidden sm:inline">
            vs mois dernier
          </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1 sm:hidden">
            vs M-1
          </span>
                </div>
            </div>
            <div class="flex-shrink-0 ml-2 sm:ml-3 lg:ml-4">
                <div :class="[
          'p-2 sm:p-2.5 lg:p-3 rounded-lg sm:rounded-xl transition-all duration-200 group-hover:scale-105',
          colorClasses[color]
        ]">
                    <component
                        :is="iconComponent"
                        class="h-4 w-4 sm:h-5 sm:w-5 lg:h-6 lg:w-6 text-white"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { DollarSign, Package, ShoppingBag, TrendingDown, TrendingUp, UserCheck, Users } from 'lucide-vue-next';

type ColorType = 'blue' | 'emerald' | 'orange' | 'green' | 'purple'
type IconType = 'Users' | 'UserCheck' | 'ShoppingBag' | 'DollarSign' | 'Package'

interface Props {
    title: string;
    value: string | number;
    icon: IconType;
    color: ColorType;
    trend?: {
        value: number
        isPositive: boolean
    };
}

const props = defineProps<Props>();

const colorClasses: Record<ColorType, string> = {
    blue: 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-blue-500/25',
    emerald: 'bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-emerald-500/25',
    orange: 'bg-gradient-to-br from-orange-500 to-orange-600 shadow-orange-500/25',
    green: 'bg-gradient-to-br from-green-500 to-green-600 shadow-green-500/25',
    purple: 'bg-gradient-to-br from-purple-500 to-purple-600 shadow-purple-500/25'
};

const iconComponents = {
    Users,
    UserCheck,
    ShoppingBag,
    DollarSign,
    Package
};

const iconComponent = computed(() => iconComponents[props.icon]);

// Formatage intelligent de la valeur selon la taille
const formattedValue = computed(() => {
    if (typeof props.value === 'string') {
        // Si c'est déjà une chaîne formatée (comme pour la devise), on la retourne
        return props.value;
    }

    const num = Number(props.value);

    // Pour les très grands nombres, on utilise une notation compacte sur mobile
    if (num >= 1000000) {
        return `${(num / 1000000).toFixed(1)}M`;
    } else if (num >= 1000) {
        return `${(num / 1000).toFixed(1)}k`;
    }

    return num.toString();
});
</script>
