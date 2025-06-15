<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { ArrowUpDown, Loader2, Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';
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

// Définition des types
interface Client {
    id: number;
    nom: string;
    adresse: string | null;
    numero: string;
    created_at: string;
    updated_at: string;
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
    clients: {
        data: Client[];
    };
}>();

// États réactifs
const showCreate = ref(false);
const showEdit = ref(false);
const showDelete = ref(false);
const current = ref<Client | null>(null);
const searchQuery = ref('');
// Pagination locale supprimée, affichage de tous les clients
const sortField = ref('nom');
const sortDirection = ref('asc');

// Formulaire de création
const createForm = useForm({
    nom: '',
    adresse: '',
    numero: ''
});

// Formulaire d'édition
const editForm = useForm({
    nom: '',
    adresse: '',
    numero: ''
});

// Filtrage local des pharmaciens
const localClients = computed(() => {
    let filtered = props.clients.data;
    // Recherche sur nom, adresse ou numero
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (c) =>
                c.nom.toLowerCase().includes(q) ||
                (c.adresse && c.adresse.toLowerCase().includes(q)) ||
                c.numero.toLowerCase().includes(q)
        );
    }
    // Tri local
    if (sortField.value) {
        filtered = [...filtered].sort((a, b) => {
            let aValue = a[sortField.value as keyof Client] ?? '';
            let bValue = b[sortField.value as keyof Client] ?? '';
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
    showCreate.value = true;
};

// Ouvrir le modal d'édition
const openEdit = (client: Client): void => {
    current.value = client;
    editForm.nom = client.nom;
    editForm.adresse = client.adresse || '';
    editForm.numero = client.numero;
    showEdit.value = true;
};

// Ouvrir le modal de suppression
const openDelete = (client: Client): void => {
    current.value = client;
    showDelete.value = true;
};

// Soumettre le formulaire de création
const submitCreate = (): void => {
    createForm.post(route('clients.store'), {
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
            // toast({
            //     title: 'Succès',
            //     description: 'Le pharmacien a été créé avec succès.'
            // });
        }
    });
};

// Soumettre le formulaire d'édition
const submitEdit = (): void => {
    if (!current.value) return;

    editForm.put(route('clients.update', current.value.id), {
        onSuccess: () => {
            showEdit.value = false;
        }
    });
};

// Confirmer la suppression
const confirmDelete = (): void => {
    if (!current.value) return;

    router.delete(route('clients.destroy', current.value.id), {
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
    return new Date(dateString).toLocaleString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Gestion des clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6 flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <h1 class="text-2xl font-semibold">Gestion des clients</h1>
                <Button @click="openCreate">
                    <Plus class="mr-2 h-4 w-4" />
                    Ajouter un client
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1">
                            <Input v-model="searchQuery" placeholder="Rechercher un client..." class="max-w-sm">
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
                                    <TableHead class="cursor-pointer" @click="sort('nom')">
                                        <div class="flex items-center">
                                            Nom
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('adresse')">
                                        <div class="flex items-center">
                                            Adresse
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('numero')">
                                        <div class="flex items-center">
                                            Numéro
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('created_at')">
                                        <div class="flex items-center">
                                            Date de création
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="client in localClients" :key="client.id">
                                    <TableCell class="font-medium">{{ client.nom }}</TableCell>
                                    <TableCell>{{ client.adresse }}</TableCell>
                                    <TableCell>{{ client.numero }}</TableCell>
                                    <TableCell>{{ formatDate(client.created_at) }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end space-x-2">
                                            <Button variant="ghost" size="sm" @click="openEdit(client)">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                            <Button variant="ghost" size="sm" @click="openDelete(client)">
                                                <Trash2 class="text-destructive h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="clients.data.length === 0">
                                    <TableCell colspan="5" class="text-muted-foreground py-8 text-center"> Aucun client
                                        trouvé
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Modal de création -->
        <Dialog :open="showCreate" @update:open="(val) => (showCreate = val)">
            <DialogContent class="sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle>Nouveau client</DialogTitle>
                    <DialogDescription> Remplissez les informations pour ajouter un nouveau client</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitCreate">
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <label for="nom" class="text-sm font-medium"> Nom </label>
                            <Input
                                id="nom"
                                v-model="createForm.nom"
                                placeholder="Nom du client"
                                :class="{ 'border-destructive': createForm.errors.nom }"
                            />
                            <p v-if="createForm.errors.nom" class="text-destructive text-sm">
                                {{ createForm.errors.nom }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="adresse" class="text-sm font-medium"> Adresse </label>
                            <Input
                                id="adresse"
                                v-model="createForm.adresse"
                                placeholder="Adresse du client"
                                :class="{ 'border-destructive': createForm.errors.adresse }"
                            />
                            <p v-if="createForm.errors.adresse" class="text-destructive text-sm">
                                {{ createForm.errors.adresse }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="numero" class="text-sm font-medium"> Numéro </label>
                            <Input
                                id="numero"
                                v-model="createForm.numero"
                                placeholder="Numéro du client"
                                :class="{ 'border-destructive': createForm.errors.numero }"
                            />
                            <p v-if="createForm.errors.numero" class="text-destructive text-sm">
                                {{ createForm.errors.numero }}
                            </p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showCreate = false"> Annuler</Button>
                        <Button type="submit" :disabled="createForm.processing">
                            <span v-if="createForm.processing" class="mr-2">
                                <Loader2 class="h-4 w-4 animate-spin" />
                            </span>
                            Créer
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Modal d'édition -->
        <Dialog :open="showEdit" @update:open="(val) => (showEdit = val)">
            <DialogContent class="sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle>Modifier le client</DialogTitle>
                    <DialogDescription> Modifiez les informations du client</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitEdit">
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <label for="edit-nom" class="text-sm font-medium"> Nom </label>
                            <Input
                                id="edit-nom"
                                v-model="editForm.nom"
                                placeholder="Nom du client"
                                :class="{ 'border-destructive': editForm.errors.nom }"
                            />
                            <p v-if="editForm.errors.nom" class="text-destructive text-sm">
                                {{ editForm.errors.nom }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-adresse" class="text-sm font-medium"> Adresse </label>
                            <Input
                                id="edit-adresse"
                                v-model="editForm.adresse"
                                placeholder="Adresse du client"
                                :class="{ 'border-destructive': editForm.errors.adresse }"
                            />
                            <p v-if="editForm.errors.adresse" class="text-destructive text-sm">
                                {{ editForm.errors.adresse }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-numero" class="text-sm font-medium"> Numéro </label>
                            <Input
                                id="edit-numero"
                                v-model="editForm.numero"
                                placeholder="Numéro du client"
                                :class="{ 'border-destructive': editForm.errors.numero }"
                            />
                            <p v-if="editForm.errors.numero" class="text-destructive text-sm">
                                {{ editForm.errors.numero }}
                            </p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showEdit = false"> Annuler</Button>
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
                    <DialogTitle>Confirmer la suppression</DialogTitle>
                    <DialogDescription>
                        Êtes-vous sûr de vouloir supprimer le client
                        <span class="font-semibold">{{ current?.nom }}</span> ? Cette action est irréversible.
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
