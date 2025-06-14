<script setup lang="ts">
import ProductForm from '@/components/products/ProductForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Category, PharmaceuticalProduct } from '@/types/models';
import { Head, router } from '@inertiajs/vue3';
import { computed, type Ref, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Eye, Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';

// Utilisation d'une chaîne de caractères simple pour le titre
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Produits pharmaceutiques',
        href: '/pharmaceutical-products',
    },
];

interface Props {
    products: PharmaceuticalProduct[];
    categories: Category[];
}

// Déclaration des props avec des valeurs par défaut
const props = withDefaults(defineProps<Props>(), {
    products: (): PharmaceuticalProduct[] => [],
    categories: (): Category[] => [],
});

const showCreate = ref(false);
const showEdit = ref(false);
const showDetails = ref(false);
const current: Ref<PharmaceuticalProduct | null> = ref(null);

const openCreate = (): void => {
    console.log('=== OPEN CREATE CALLED ===');
    current.value = null;
    console.log('Setting showCreate to true');
    showCreate.value = true;
    console.log('Current modal states:', {
        showCreate: showCreate.value,
        showEdit: showEdit.value,
        showDetails: showDetails.value,
    });
};

const openEdit = (product: PharmaceuticalProduct): void => {
    console.log('=== OPEN EDIT CALLED ===');
    current.value = product;
    console.log('Setting showEdit to true for product:', product.id);
    showEdit.value = true;
    console.log('Current modal states:', {
        showCreate: showCreate.value,
        showEdit: showEdit.value,
        showDetails: showDetails.value,
    });
};

const openDetails = (product: PharmaceuticalProduct): void => {
    console.log('=== OPEN DETAILS CALLED ===');
    current.value = product;
    console.log('Setting showDetails to true for product:', product.id);
    showDetails.value = true;
    console.log('Current modal states:', {
        showCreate: showCreate.value,
        showEdit: showEdit.value,
        showDetails: showDetails.value,
    });
};

// Gestion de la recherche
const searchQuery = ref('');
const localProducts = ref<PharmaceuticalProduct[]>([...props.products]);

// Mettre à jour les produits locaux lorsque les props changent
watch(
    () => props.products,
    (newProducts) => {
        localProducts.value = [...newProducts];
    },
    { immediate: true },
);

// Produits filtrés
const filteredProducts = computed<PharmaceuticalProduct[]>(() => {
    if (!searchQuery.value) return localProducts.value;

    const query = searchQuery.value.toLowerCase();
    return localProducts.value.filter((product) => {
        return (
            product.name.toLowerCase().includes(query) ||
            product.category?.name?.toLowerCase().includes(query) ||
            '' ||
            product.price.toString().includes(query) ||
            product.stock_quantity.toString().includes(query)
        );
    });
});

// Exposer les données au template
defineExpose({
    filteredProducts,
    searchQuery,
    localProducts,
});

// Les produits sont initialisés automatiquement via le watch sur props.products

// Gestion de la suppression
const confirmDelete = (product: PharmaceuticalProduct): void => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')) {
        router.delete(route('pharmaceutical-products.destroy', product.id), {
            onSuccess: () => {
                const index = localProducts.value.findIndex((p) => p.id === product.id);
                if (index !== -1) {
                    localProducts.value.splice(index, 1);
                }
            },
            onError: (error) => {
                console.error('Erreur lors de la suppression du produit:', error);
            },
        });
    }
};

const handleSearch = () => {
    // La recherche est maintenant gérée côté client via le computed filteredProducts
    // Cette fonction peut être supprimée ou conservée pour une recherche côté serveur si nécessaire
};

const handleFormSaved = (mode: 'create' | 'edit') => {
    if (mode === 'create') {
        showCreate.value = false;
    } else if (mode === 'edit' && current.value) {
        showEdit.value = false;
    }
    // Pas besoin de recharger la page, la liste est mise à jour en temps réel
};

// Exposer la fonction formatForm au template
// Fonction utilitaire pour formater le type de forme du produit
const formatForm = (form: string): string => {
    const forms: Record<string, string> = {
        tablet: 'Comprimé',
        capsule: 'Gélule',
        syrup: 'Sirop',
        injection: 'Injection',
        cream: 'Crème',
        drops: 'Gouttes',
        inhaler: 'Inhalateur',
        other: 'Autre',
    };

    return forms[form] || form;
};
</script>

<template>
    <Head title="Produits pharmaceutiques" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Produits pharmaceutiques</h1>
                <Button @click.stop="openCreate">
                    <Plus class="mr-2 h-4 w-4" />
                    Ajouter un produit
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Liste des produits</CardTitle>
                        <div class="w-64">
                            <div class="relative">
                                <Search class="text-muted-foreground absolute top-2.5 left-2.5 h-4 w-4" />
                                <Input
                                    type="search"
                                    placeholder="Rechercher un produit..."
                                    v-model="searchQuery"
                                    @input="handleSearch"
                                    class="pl-8"
                                />
                            </div>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nom</TableHead>
                                <TableHead>Catégorie</TableHead>
                                <TableHead>Prix</TableHead>
                                <TableHead>Stock</TableHead>
                                <TableHead class="w-[100px]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="product in filteredProducts" :key="product.id">
                                <TableCell class="font-medium">{{ product.name }}</TableCell>
                                <TableCell>
                                    <Badge variant="outline">{{ product.category?.name || 'Aucune' }}</Badge>
                                </TableCell>
                                <TableCell>{{ Number(product.price || 0).toFixed(2) }} FCFA</TableCell>
                                <TableCell>
                                    <Badge :variant="product.stock_quantity > 0 ? 'default' : 'destructive'">
                                        {{ product.stock_quantity }} unité(s)
                                    </Badge>
                                </TableCell>
                                <TableCell class="space-x-2">
                                    <Button variant="ghost" size="icon" @click.stop="openDetails(product)">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" @click.stop="openEdit(product)">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" @click="confirmDelete(product)" class="text-red-600 hover:bg-red-50">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>

            <!-- Create Dialog -->
            <Dialog :open="showCreate" @update:open="(val) => (showCreate = val)">
                <DialogContent class="sm:max-w-[600px]">
                    <DialogHeader>
                        <DialogTitle>Ajouter un produit</DialogTitle>
                        <DialogDescription> Remplissez le formulaire pour ajouter un nouveau produit pharmaceutique </DialogDescription>
                    </DialogHeader>
                    <div class="py-4">
                        <ProductForm :categories="categories" @cancel="showCreate = false" @saved="handleFormSaved('create')" />
                    </div>
                </DialogContent>
            </Dialog>

            <!-- Edit Dialog -->
            <Dialog :open="showEdit" @update:open="(val) => (showEdit = val)">
                <DialogContent class="sm:max-w-[600px]">
                    <DialogHeader>
                        <DialogTitle>Modifier le produit</DialogTitle>
                        <DialogDescription>Modifiez les informations du produit</DialogDescription>
                    </DialogHeader>
                    <div class="py-4">
                        <ProductForm 
                            v-if="current"
                            :model-value="current"
                            :categories="categories"
                            mode="edit"
                            @cancel="showEdit = false"
                            @saved="handleFormSaved('edit')"
                        />
                    </div>
                </DialogContent>
            </Dialog>

            <!-- Details Dialog -->
            <Dialog :open="showDetails" @update:open="(val) => (showDetails = val)">
                <DialogContent class="sm:max-w-[600px]">
                    <DialogHeader>
                        <DialogTitle>Détails du produit</DialogTitle>
                    </DialogHeader>
                    <div v-if="current" class="space-y-4 py-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="text-muted-foreground">Nom</Label>
                                <p class="font-medium">{{ current.name || '-' }}</p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Catégorie</Label>
                                <p class="font-medium">
                                    <Badge variant="outline">{{ current.category?.name || 'Aucune' }}</Badge>
                                </p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Prix</Label>
                                <p class="font-medium">{{ Number(current.price || 0).toFixed(2) }} FCFA</p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Stock</Label>
                                <p class="font-medium">
                                    <Badge :variant="current.stock_quantity > 0 ? 'default' : 'destructive'">
                                        {{ current.stock_quantity }} unité(s)
                                    </Badge>
                                </p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Date d'expiration</Label>
                                <p class="font-medium">
                                    {{ current.expiration_date ? new Date(current.expiration_date).toLocaleDateString('fr-FR') : '-' }}
                                </p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Forme</Label>
                                <p class="font-medium">{{ formatForm(current.form) || '-' }}</p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Dosage</Label>
                                <p class="font-medium">{{ current.dosage || '-' }}</p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">N° de lot</Label>
                                <p class="font-medium">{{ current.batch_number || '-' }}</p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Fabricant</Label>
                                <p class="font-medium">{{ current.manufacturer || '-' }}</p>
                            </div>
                            <div>
                                <Label class="text-muted-foreground">Ordonnance requise</Label>
                                <p class="font-medium">
                                    <Badge :variant="current.requires_prescription ? 'destructive' : 'outline'">
                                        {{ current.requires_prescription ? 'Oui' : 'Non' }}
                                    </Badge>
                                </p>
                            </div>
                        </div>
                        <div v-if="current.description" class="border-t pt-4">
                            <Label class="text-muted-foreground">Description</Label>
                            <p class="text-muted-foreground mt-1 text-sm whitespace-pre-line">{{ current.description }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button @click="showDetails = false">Fermer</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
