<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 sm:gap-6 p-3 sm:p-4 lg:p-6">
            <!-- Header avec toggle de thème -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                        Tableau de bord
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1 hidden sm:block">
                        Vue d'ensemble de votre pharmacie
                    </p>
                </div>
            </div>

            <!-- Cartes de métriques ultra-responsives -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
                <MetricCard
                    title="Utilisateurs"
                    :value="metrics.users"
                    icon="Users"
                    color="blue"
                    :trend="{ value: 12, isPositive: true }"
                />
                <MetricCard
                    title="Clients"
                    :value="metrics.clients"
                    icon="UserCheck"
                    color="emerald"
                    :trend="{ value: 8, isPositive: true }"
                />
                <MetricCard
                    title="Ventes"
                    :value="metrics.sales"
                    icon="ShoppingBag"
                    color="orange"
                    :trend="{ value: 15, isPositive: true }"
                />
                <div class="col-span-2 sm:col-span-1">
                    <MetricCard
                        title="Chiffre d'affaires"
                        :value="formatCurrencyCompact(metrics.sales_total)"
                        icon="DollarSign"
                        color="green"
                        :trend="{ value: 23, isPositive: true }"
                    />
                </div>
                <div class="col-span-2 sm:col-span-3 lg:col-span-1">
                    <MetricCard
                        title="Produits"
                        :value="metrics.products"
                        icon="Package"
                        color="purple"
                        :trend="{ value: 5, isPositive: false }"
                    />
                </div>
            </div>

            <!-- Grille principale adaptative -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Evolution des ventes -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 shadow-sm">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-0">
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                                    Évolution des ventes
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                    7 derniers jours
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    class="px-2 sm:px-3 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full">
                                    7j
                                </button>
                            </div>
                        </div>
                        <div class="h-48 sm:h-64 lg:h-80">
                            <BarChart
                                :labels="salesByDay.map(m => m.day)"
                                :values="salesByDay.map(m => m.total)"
                                label="Chiffre d'affaires journalier"
                            />
                        </div>
                    </div>
                </div>

                <!-- Dernières ventes compactes -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                            Dernières ventes
                        </h3>
                        <Link
                            href="/sales"
                            class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium"
                        >
                            Voir tout
                        </Link>
                    </div>
                    <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                        <div
                            v-for="sale in recentSales.slice(0, 5)"
                            :key="sale.id"
                            class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                        >
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ sale.client?.nom || 'Client anonyme' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ formatDateCompact(sale.date_vente) }}
                                </p>
                            </div>
                            <div class="text-right ml-2">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ formatCurrencyCompact(sale.montant_total) }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 hidden sm:block">
                                    {{ sale.user?.name || '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs compacts pour mobile -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Top clients -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                            Top 5 clients
                        </h3>
                        <TrendingUp class="h-4 w-4 sm:h-5 sm:w-5 text-green-500" />
                    </div>
                    <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                        <div
                            v-for="(client, index) in topClients"
                            :key="client.id"
                            class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4"
                        >
                            <div class="flex-shrink-0">
                                <div
                                    class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs sm:text-sm font-bold">{{ index + 1 }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ client.nom }}
                                </p>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2 mt-1">
                                    <div
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 h-1.5 sm:h-2 rounded-full transition-all duration-300"
                                        :style="{ width: `${(client.total_achats / topClients[0].total_achats) * 100}%` }"
                                    ></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ formatCurrencyCompact(client.total_achats) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top produits -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                            Top 5 produits
                        </h3>
                        <Package class="h-4 w-4 sm:h-5 sm:w-5 text-orange-500" />
                    </div>
                    <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                        <div
                            v-for="(product, index) in topProducts"
                            :key="product.id"
                            class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4"
                        >
                            <div class="flex-shrink-0">
                                <div
                                    class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs sm:text-sm font-bold">{{ index + 1 }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ product.name }}
                                </p>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2 mt-1">
                                    <div
                                        class="bg-gradient-to-r from-orange-500 to-red-600 h-1.5 sm:h-2 rounded-full transition-all duration-300"
                                        :style="{ width: `${(product.total_vendus / topProducts[0].total_vendus) * 100}%` }"
                                    ></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ product.total_vendus }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 hidden sm:block">vendus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes compactes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                <AlertCard
                    type="warning"
                    title="Stock faible"
                    :message="`${stats_alerts.lowStockCount} produit${stats_alerts.lowStockCount > 1 ? 's' : ''} ont un stock inférieur ou égal à 10`"
                    action="Voir les produits"
                    @click="openModal('lowStock')"
                />
                <AlertCard
                    type="info"
                    title="Produits en expiration"
                    :message="`${stats_alerts.expiringSoonCount} produit${stats_alerts.expiringSoonCount > 1 ? 's' : ''} expirent dans moins de 30 jours`"
                    action="Voir les produits"
                    @click="openModal('expiring')"
                />

                <!-- Modal produits concernés -->
                <Dialog v-model="showModal">
                    <DialogContent class="max-w-lg w-full">
                        <DialogHeader>
                            <DialogTitle>
                                {{ modalType === 'lowStock' ? 'Produits à stock faible' : 'Produits en expiration prochaine'
                                }}
                            </DialogTitle>
                        </DialogHeader>
                        <div class="divide-y divide-muted-foreground/10">
                            <template v-if="modalType === 'lowStock'">
                                <div v-for="prod in lowStockProducts" :key="prod.id" class="flex justify-between py-2">
                                    <span>{{ prod.name }}</span>
                                    <span class="font-semibold text-warning">Stock : {{ prod.stock_quantity }}</span>
                                </div>
                                <div v-if="!lowStockProducts.length" class="text-center text-muted-foreground py-4">
                                    Aucun produit à stock faible.
                                </div>
                            </template>
                            <template v-else>
                                <div v-for="prod in expiringSoonProducts" :key="prod.id"
                                     class="flex justify-between py-2">
                                    <span>{{ prod.name }}</span>
                                    <span
                                        class="font-semibold text-info">Expire le {{ formatDateCompact(prod.expiration_date)
                                        }}</span>
                                </div>
                                <div v-if="!expiringSoonProducts.length" class="text-center text-muted-foreground py-4">
                                    Aucun produit en expiration prochaine.
                                </div>
                            </template>
                        </div>
                    </DialogContent>
                </Dialog>
                <div class="sm:col-span-2 lg:col-span-1">
                    <AlertCard
                        :type="stats_alerts.goalReached ? 'success' : 'info'"
                        :title="stats_alerts.goalReached ? 'Objectif atteint' : 'Objectif en cours'"
                        :message="stats_alerts.goalReached
                          ? `Objectif mensuel de ventes dépassé de ${stats_alerts.goalPercent - 100}%\n(${formatCurrencyCompact(stats_alerts.currentMonthSales)} / ${formatCurrencyCompact(stats_alerts.monthlyGoal)})`
                          : `Objectif mensuel atteint à ${stats_alerts.goalPercent}%\n(${formatCurrencyCompact(stats_alerts.currentMonthSales)} / ${formatCurrencyCompact(stats_alerts.monthlyGoal)})`"
                        action=""
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import MetricCard from '@/components/dashboard/MetricCard.vue';
import AlertCard from '@/components/dashboard/AlertCard.vue';
import BarChart from '@/components/BarChart.vue';
import { TrendingUp, Package } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

interface RecentSaleClient {
    nom: string;
}

interface RecentSaleUser {
    name: string;
}

interface RecentSale {
    id: number;
    montant_total: number;
    date_vente: string;
    client: RecentSaleClient | null;
    user: RecentSaleUser | null;
}

import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';

interface Props {
    metrics: {
        users: number
        clients: number
        sales: number
        sales_total: number
        products: number
    };
    recentSales: RecentSale[];
    topClients: Array<{
        id: number
        nom: string
        total_achats: number
    }>;
    topProducts: Array<{
        id: number
        name: string
        total_vendus: number
    }>;
    salesByDay: Array<{
        day: string
        total: number
    }>;
    stats_alerts: {
        lowStockCount: number;
        expiringSoonCount: number;
        goalReached: boolean;
        goalPercent: number;
        monthlyGoal: number;
        currentMonthSales: number;
    };
    lowStockProducts: Array<{
        id: number;
        name: string;
        stock_quantity: number;
    }>;
    expiringSoonProducts: Array<{
        id: number;
        name: string;
        expiration_date: string;
    }>;
}

const props = defineProps<Props>();

import { ref } from 'vue';

const showModal = ref(false);
const modalType = ref<'lowStock' | 'expiring'>('lowStock');

function openModal(type: 'lowStock' | 'expiring') {
    router.visit('/pharmaceutical-products');
    
    modalType.value = type;
    showModal.value = true;
}

const lowStockProducts = props.lowStockProducts;
const expiringSoonProducts = props.expiringSoonProducts;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tableau de bord',
        href: '/dashboard'
    }
];

// Formatage compact pour mobile
const formatCurrencyCompact = (amount: number): string => {
    if (amount >= 1000000) {
        return `${(amount / 1000000).toFixed(1)}M F CFA`;
    } else if (amount >= 1000) {
        return `${(amount / 1000).toFixed(0)}k F CFA`;
    }
    return amount.toLocaleString('fr-FR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }) + ' F CFA';
};

const formatDateCompact = (dateString: string): string => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - date.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) return 'Aujourd\'hui';
    if (diffDays === 2) return 'Hier';
    if (diffDays <= 7) return `Il y a ${diffDays}j`;

    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short'
    });
};
</script>
