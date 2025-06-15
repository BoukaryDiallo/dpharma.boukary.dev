<script setup lang="ts">
import { ArrowUpDown, Loader2, Minus, Pencil, Plus, Search, Trash2, ExternalLink } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { PharmaceuticalProduct } from '@/types/models';

// Définition des types
interface Client {
    id: number;
    nom: string;
}

interface User {
    id: number;
    name: string;
}

interface ProductWithPivot extends PharmaceuticalProduct {
    pivot: {
        quantity: number;
        unit_price: number;
        total: number;
    };
}

interface Sale {
    id: number;
    client_id: number;
    user_id: number;
    montant_total: number;
    date_vente: string;
    created_at: string;
    updated_at: string;
    client: Client;
    user: User;
}

interface SaleWithProducts extends Sale {
    products: ProductWithPivot[];
}

// Configuration des fil d'ariane
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tableau de bord',
        href: '/dashboard'
    },
    {
        title: 'Clients',
        href: '/clients'
    }
];

// Props
const props = defineProps<{
    sales: {
        data: SaleWithProducts[];
    };
    clients: {
        data: Client[];
    };
    products: {
        data: PharmaceuticalProduct[];
    };
}>();


// États réactifs
const showCreate = ref(false);
const showEdit = ref(false);
const showDelete = ref(false);
const showClientSalesModal = ref(false);
const selectedClientId = ref<number | null>(null);
const current = ref<Sale | null>(null);
const searchQuery = ref('');
const sortField = ref('date_vente');
const sortDirection = ref('desc');

// Formulaire de création
const createForm = useForm({
    client_id: '',
    products: [
        { product_id: '', quantity: 1 }
    ],
    montant_total: 0,
    date_vente: ''
});

// Formulaire d'édition
const editForm = useForm({
    client_id: '',
    products: [
        { product_id: '', quantity: 1 }
    ],
    montant_total: 0,
    date_vente: ''
});

// Ventes d'un client sélectionné
const clientSales = computed(() => {
    if (!selectedClientId.value) return [];
    return props.sales.data.filter(sale => sale.client_id === selectedClientId.value);
});

// Filtrage local des ventes
const localSales = computed(() => {
    let filtered = props.sales.data;
    // Recherche sur client, montant, date, user
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (s) =>
                s.client?.nom?.toLowerCase().includes(q) ||
                s.user?.name?.toLowerCase().includes(q) ||
                String(s.montant_total).includes(q) ||
                (s.date_vente && s.date_vente.toLowerCase().includes(q))
        );
    }
    // Tri local
    if (sortField.value) {
        filtered = [...filtered].sort((a, b) => {
            let aValue: any;
            let bValue: any;
            switch (sortField.value) {
                case 'client':
                    aValue = a.client?.nom || '';
                    bValue = b.client?.nom || '';
                    break;
                case 'user':
                    aValue = a.user?.name || '';
                    bValue = b.user?.name || '';
                    break;
                default:
                    aValue = a[sortField.value as keyof Sale] ?? '';
                    bValue = b[sortField.value as keyof Sale] ?? '';
            }
            if (typeof aValue === 'string' && typeof bValue === 'string') {
                aValue = aValue.toLowerCase();
                bValue = bValue.toLowerCase();
            }
            if (aValue < bValue) return (sortDirection.value as string) === 'asc' ? -1 : 1;
            if (aValue > bValue) return (sortDirection.value as string) === 'asc' ? 1 : -1;
            return 0;
        });
    }
    return filtered;
});

// Ouvrir le modal de création
const openCreate = (): void => {
    current.value = null;
    createForm.reset();
    createForm.products = [{ product_id: '', quantity: 1 }];
    showCreate.value = true;
};

// Ouvrir le modal d'édition
const openEdit = (sale: SaleWithProducts): void => {
    current.value = sale;
    editForm.client_id = String(sale.client_id);
    editForm.products = sale.products?.map((p) => ({
        product_id: String(p.id),
        quantity: p.pivot?.quantity || 1
    })) || [{ product_id: '', quantity: 1 }];
    editForm.montant_total = sale.montant_total;
    editForm.date_vente = sale.date_vente ? sale.date_vente.substring(0, 16) : '';
    showEdit.value = true;
};

// Ouvrir le modal de suppression
const openDelete = (sale: Sale): void => {
    current.value = sale;
    showDelete.value = true;
};

// Soumettre le formulaire de création
const createProductErrors = ref<string[]>([]);
const submitCreate = (): void => {
    createProductErrors.value = [];
    let hasError = false;
    createForm.products.forEach((item, idx) => {
        if (!item.product_id || !item.quantity || item.quantity < 1) {
            createProductErrors.value.push(`Ligne ${idx + 1} : produit ou quantité manquante/invalide.`);
            hasError = true;
        }
    });
    if (hasError) return;
    createForm.montant_total = calcTotal(createForm.products);
    createForm.post(route('sales.store'), {
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
        }
    });
};

// Soumettre le formulaire d'édition
const editProductErrors = ref<string[]>([]);
const submitEdit = (): void => {
    if (!current.value) return;
    editProductErrors.value = [];
    let hasError = false;

    console.log(editForm.products);

    editForm.products.forEach((item, idx) => {
        if (!item.product_id || !item.quantity || item.quantity < 1) {
            console.log(item);
            editProductErrors.value.push(`Ligne ${idx + 1} : produit ou quantité manquante/invalide.`);
            hasError = true;
        }
    });
    if (hasError) return;
    editForm.montant_total = calcTotal(editForm.products);
    editForm.put(route('sales.update', current.value.id), {
        onSuccess: () => {
            showEdit.value = false;
        }
    });
};

// Confirmer la suppression
const confirmDelete = (): void => {
    if (!current.value) return;
    router.delete(route('sales.destroy', current.value.id), {
        onSuccess: () => {
            showDelete.value = false;
        }
    });
};

// Fonction pour trier les résultats
const sort = (field: string): void => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
};

// Formater la date
const formatDate = (dateString: string): string => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Calcul du total de la vente
function calcTotal(products: { product_id: string | number, quantity: number }[]): number {
    return products.reduce((sum, p) => {
        const prod = props.products.data.find(pr => String(pr.id) === String(p.product_id));
        if (!prod) return sum;
        return sum + (prod.price * (p.quantity || 1));
    }, 0);
}

// Calcul du total d'une ligne
function lineTotal(product_id: string | number, quantity: number): number {
    const prod = props.products.data.find(pr => String(pr.id) === String(product_id));
    if (!prod) return 0;
    return prod.price * (quantity || 1);
}

// Ajout d'une ligne produit
function addProductLine(form: any) {
    form.products.push({ product_id: '', quantity: 1 });
}

// Suppression d'une ligne produit
function removeProductLine(form: any, idx: number) {
    if (form.products.length > 1) form.products.splice(idx, 1);
}

function openClientSalesModal(clientId: number) {
    selectedClientId.value = clientId;
    showClientSalesModal.value = true;
}

</script>

<template>
    <Head title="Gestion des ventes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6 flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <h1 class="text-2xl font-semibold">Gestion des ventes</h1>
                <Button @click="openCreate">
                    <Plus class="mr-2 h-4 w-4" />
                    Ajouter une vente
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1">
                            <Input v-model="searchQuery" placeholder="Rechercher une vente..." class="max-w-sm">
                                <template #prefix>
                                    <Search class="text-muted-foreground h-4 w-4" />
                                </template>
                            </Input>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="cursor-pointer" @click="sort('client.nom')">
                                        <div class="flex items-center">
                                            Client
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('montant_total')">
                                        <div class="flex items-center">
                                            Montant
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('date_vente')">
                                        <div class="flex items-center">
                                            Date
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('user.name')">
                                        <div class="flex items-center">
                                            Utilisateur
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="sale in localSales" :key="sale.id">
                                    <TableCell class="font-medium flex items-center justify-between gap-2">
    <span>{{ sale.client?.nom }}</span>
    <Button variant="ghost" size="icon"
            @click="openClientSalesModal(sale.client_id)">
        <ExternalLink class="h-4 w-4 text-muted-foreground hover:text-primary" />
    </Button>
</TableCell>
                                    <TableCell>{{ sale.montant_total }}</TableCell>
                                    <TableCell>{{ formatDate(sale.date_vente) }}</TableCell>
                                    <TableCell>{{ sale.user?.name }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end space-x-2">
                                            <Button variant="ghost" size="sm" @click="openEdit(sale)">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                            <Button variant="ghost" size="sm" @click="openDelete(sale)">
                                                <Trash2 class="text-destructive h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="sales.data.length === 0">
                                    <TableCell colspan="5" class="text-muted-foreground py-8 text-center"> Aucune vente
                                        trouvée
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>

                <!-- Modal ventes client -->
                <Dialog :open="showClientSalesModal" @update:open="(val) => (showClientSalesModal = val)">
                    <DialogContent class="sm:max-w-[700px]">
                        <DialogHeader>
                            <DialogTitle>Ventes du client</DialogTitle>
                            <DialogDescription>
                                Liste des ventes pour le client sélectionné
                            </DialogDescription>
                        </DialogHeader>
                        <div v-if="clientSales.length">
                            <table class="w-full text-sm border rounded">
                                <thead>
                                <tr class="bg-muted">
                                    <th class="p-2">Date</th>
                                    <th class="p-2">Montant</th>
                                    <th class="p-2">Produits</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="sale in clientSales" :key="sale.id">
                                    <td class="p-2">{{ formatDate(sale.date_vente) }}</td>
                                    <td class="p-2">
                                        {{ sale.montant_total.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) }} F
                                        CFA
                                    </td>
                                    <td class="p-2">
                                        <ul>
                                            <li v-for="prod in sale.products" :key="prod.id">
                                                {{ prod.name }} ({{ prod.pivot.quantity }})
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-muted-foreground">Aucune vente pour ce client.</div>
                    </DialogContent>
                </Dialog>
            </Card>
        </div>

        <!-- Modal création -->
        <Dialog :open="showCreate" @update:open="(val) => (showCreate = val)">
            <DialogContent class="sm:max-w-[700px]">
                <DialogHeader>
                    <DialogTitle>Nouvelle vente</DialogTitle>
                    <DialogDescription>Remplissez les informations pour ajouter une nouvelle vente</DialogDescription>
                </DialogHeader>
                <div class="max-h-[70vh] overflow-y-auto">
                    <form @submit.prevent="submitCreate">
                        <div v-if="createProductErrors.length"
                             class="mb-2 bg-destructive/10 text-destructive p-2 rounded text-sm">
                            <div v-for="err in createProductErrors" :key="err">{{ err }}</div>
                        </div>
                        <div class="grid gap-4 py-4">
                            <div class="grid gap-2">
                                <label for="client_id" class="text-sm font-medium">Client</label>
                                <select id="client_id" v-model="createForm.client_id"
                                        class="input w-full border rounded p-2"
                                        :class="{ 'border-destructive': createForm.errors.client_id }" required>
                                    <option value="" disabled>Sélectionner un client</option>
                                    <option v-for="client in props.clients.data" :key="client.id" :value="client.id">
                                        {{ client.nom }}
                                    </option>
                                </select>
                                <p v-if="createForm.errors.client_id" class="text-destructive text-sm">
                                    {{ createForm.errors.client_id }}</p>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium">Produits de la vente</label>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm border rounded">
                                        <thead>
                                        <tr class="bg-muted">
                                            <th class="p-2">Produit</th>
                                            <th class="p-2">Prix unitaire</th>
                                            <th class="p-2">Quantité</th>
                                            <th class="p-2">Total</th>
                                            <th class="p-2"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, idx) in createForm.products" :key="idx">
                                            <td class="p-2">
                                                <select v-model="item.product_id"
                                                        class="input w-full border rounded p-2"
                                                        :class="{ 'border-destructive': createForm.errors[`products.${idx}.product_id`] }"
                                                        required>
                                                    <option value="" disabled>Sélectionner</option>
                                                    <option v-for="product in props.products.data" :key="product.id"
                                                            :value="product.id">{{ product.name }}
                                                    </option>
                                                </select>
                                                <p v-if="createForm.errors[`products.${idx}.product_id` ]"
                                                   class="text-destructive text-xs">
                                                    {{ createForm.errors[`products.${idx}.product_id`] }}</p>
                                            </td>
                                            <td class="p-2 text-right">
                                                    <span v-if="item.product_id">
                                                       {{ props.products.data.find(p => String(p.id) === String(item.product_id))?.price?.toLocaleString('fr-FR', { minimumFractionDigits: 2 })
                                                        }} F CFA
                                                    </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <input type="number" min="1" v-model.number="item.quantity"
                                                       class="input w-20 border rounded p-1 text-center"
                                                       :class="{ 'border-destructive': createForm.errors[`products.${idx}.quantity`] }"
                                                       required />
                                                <p v-if="createForm.errors[`products.${idx}.quantity` ]"
                                                   class="text-destructive text-xs">
                                                    {{ createForm.errors[`products.${idx}.quantity`] }}</p>
                                            </td>
                                            <td class="p-2 text-right">
                                                {{ lineTotal(item.product_id, item.quantity).toLocaleString('fr-FR', { minimumFractionDigits: 2 })
                                                }} F CFA
                                            </td>
                                            <td class="p-2">
                                                <Button type="button" variant="ghost" size="icon"
                                                        @click="removeProductLine(createForm, idx)"
                                                        :disabled="createForm.products.length === 1">
                                                    <Minus class="h-4 w-4" />
                                                </Button>
                                                <Button type="button" variant="ghost" size="icon"
                                                        @click="addProductLine(createForm)">
                                                    <Plus class="h-4 w-4" />
                                                </Button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="grid gap-2">
                                <label for="montant_total" class="text-sm font-medium">Montant total</label>
                                <Input id="montant_total"
                                       :model-value="calcTotal(createForm.products).toLocaleString('fr-FR', {minimumFractionDigits:2}) + ' F CFA'"
                                       readonly />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button type="button" variant="outline" @click="showCreate = false">Annuler</Button>
                            <Button type="submit" :disabled="createForm.processing">
                                <span v-if="createForm.processing" class="mr-2">
                                    <Loader2 class="h-4 w-4 animate-spin" />
                                </span>
                                Enregistrer
                            </Button>
                        </DialogFooter>
                    </form>
                </div>
            </DialogContent>
        </Dialog>

        <!-- Modal édition -->
        <Dialog :open="showEdit" @update:open="(val) => (showEdit = val)">
            <DialogContent class="sm:max-w-[700px]">
                <DialogHeader>
                    <DialogTitle>Modifier la vente</DialogTitle>
                    <DialogDescription>Modifiez les informations de la vente</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitEdit">
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <label for="edit-client_id" class="text-sm font-medium">Client</label>
                            <select id="edit-client_id" v-model="editForm.client_id"
                                    class="input w-full border rounded p-2" required>
                                <option value="" disabled>Sélectionner un client</option>
                                <option v-for="client in props.clients.data" :key="client.id" :value="client.id">
                                    {{ client.nom }}
                                </option>
                            </select>
                            <p v-if="editForm.errors.client_id" class="text-destructive text-sm">
                                {{ editForm.errors.client_id }}</p>
                        </div>
                        <div class="grid gap-2">
                            <label class="text-sm font-medium">Produits de la vente</label>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm border rounded">
                                    <thead>
                                    <tr class="bg-muted">
                                        <th class="p-2">Produit</th>
                                        <th class="p-2">Prix unitaire</th>
                                        <th class="p-2">Quantité</th>
                                        <th class="p-2">Total</th>
                                        <th class="p-2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, idx) in editForm.products" :key="idx">
                                        <td class="p-2">
                                            <select v-model="item.product_id" class="input w-full border rounded p-2"
                                                    required>
                                                <option value="" disabled>Sélectionner</option>
                                                <option v-for="product in props.products.data" :key="product.id"
                                                        :value="product.id">{{ product.name }}
                                                </option>
                                            </select>
                                            <p v-if="editForm.errors[`products.${idx}.product_id` ]"
                                               class="text-destructive text-xs">
                                                {{ editForm.errors[`products.${idx}.product_id`] }}</p>
                                        </td>
                                        <td class="p-2 text-right">
                                                    <span v-if="item.product_id">
                                                        {{ props.products.data.find(p => String(p.id) === String(item.product_id))?.price?.toLocaleString('fr-FR', { minimumFractionDigits: 2 })
                                                        }} F CFA
                                                    </span>
                                        </td>
                                        <td class="p-2 text-center">
                                            <input type="number" min="1" v-model.number="item.quantity"
                                                   class="input w-20 border rounded p-1 text-center" required />
                                            <p v-if="editForm.errors[`products.${idx}.quantite` ]"
                                               class="text-destructive text-xs">
                                                {{ editForm.errors[`products.${idx}.quantite`] }}</p>
                                        </td>
                                        <td class="p-2 text-right">
                                            {{ lineTotal(item.product_id, item.quantity).toLocaleString('fr-FR', { minimumFractionDigits: 2 })
                                            }} F CFA
                                        </td>
                                        <td class="p-2">
                                            <Button type="button" variant="ghost" size="icon"
                                                    @click="removeProductLine(editForm, idx)"
                                                    :disabled="editForm.products.length === 1">
                                                <Minus class="h-4 w-4" />
                                            </Button>
                                            <Button type="button" variant="ghost" size="icon"
                                                    @click="addProductLine(editForm)">
                                                <Plus class="h-4 w-4" />
                                            </Button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-montant_total" class="text-sm font-medium">Montant total</label>
                            <Input id="edit-montant_total"
                                   :model-value="calcTotal(editForm.products).toLocaleString('fr-FR', {minimumFractionDigits:2}) + ' F CFA'"
                                   readonly />
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-date_vente" class="text-sm font-medium">Date de vente</label>
                            <Input id="edit-date_vente" v-model="editForm.date_vente" type="datetime-local"
                                   :class="{ 'border-destructive': editForm.errors.date_vente }" />
                            <p v-if="editForm.errors.date_vente" class="text-destructive text-sm">
                                {{ editForm.errors.date_vente }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showEdit = false">Annuler</Button>
                        <Button type="submit" :disabled="editForm.processing">
                                <span v-if="editForm.processing" class="mr-2">
                                    <Loader2 class="h-4 w-4 animate-spin" />
                                </span>
                            Enregistrer
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Modal de suppression -->
        <Dialog :open="showDelete" @update:open="(val) => (showDelete = val)">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogDescription>
                        Êtes-vous sûr de vouloir supprimer la vente
                        <span class="font-semibold">{{ current?.client?.nom }}</span> ? Cette action est irréversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="showDelete = false"> Annuler</Button>
                    <Button type="button" variant="destructive" @click="confirmDelete"> Supprimer</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
