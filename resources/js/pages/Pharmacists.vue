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
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

// Définition des types
interface Pharmacist {
    id: number;
    name: string;
    email: string;
    role: string;
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
        title: 'Pharmaciens',
        href: '/pharmacists'
    }
];

// Props
const props = defineProps<{
    pharmacists: {
        data: Pharmacist[];
    }
}>();

// États réactifs
const showCreate = ref(false);
const showEdit = ref(false);
const showDelete = ref(false);
const current = ref<Pharmacist | null>(null);
const searchQuery = ref('');
const selectedRole = ref('');
const perPage = ref(10);
const sortField = ref('name');
const sortDirection = ref('asc');

// Formulaire de création
const createForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'pharmacist'
});

// Formulaire d'édition
const editForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'pharmacist'
});

// Filtrage local des pharmaciens
const localPharmacists = computed(() => {
    let filtered = props.pharmacists.data;
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        filtered = filtered.filter(p =>
            p.name.toLowerCase().includes(q) ||
            p.email.toLowerCase().includes(q)
        );
    }
    if (selectedRole.value) {
        filtered = filtered.filter(p => p.role === selectedRole.value);
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
const openEdit = (pharmacist: Pharmacist): void => {
    current.value = pharmacist;
    editForm.name = pharmacist.name;
    editForm.email = pharmacist.email;
    editForm.role = pharmacist.role;
    editForm.password = '';
    editForm.password_confirmation = '';
    showEdit.value = true;
};

// Ouvrir le modal de suppression
const openDelete = (pharmacist: Pharmacist): void => {
    current.value = pharmacist;
    showDelete.value = true;
};

// Soumettre le formulaire de création
const submitCreate = (): void => {
    createForm.post(route('pharmacists.store'), {
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

    editForm.put(route('pharmacists.update', current.value.id), {
        onSuccess: () => {
            showEdit.value = false;
            // toast({
            //     title: 'Succès',
            //     description: 'Le pharmacien a été mis à jour avec succès.'
            // });
        }
    });
};

// Confirmer la suppression
const confirmDelete = (): void => {
    if (!current.value) return;

    router.delete(route('pharmacists.destroy', current.value.id), {
        onSuccess: () => {
            showDelete.value = false;
            // toast({
            //     title: 'Succès',
            //     description: 'Le pharmacien a été supprimé avec succès.'
            // });
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
    updateUrl();
};

// Mettre à jour l'URL avec les filtres actuels
const updateUrl = (): void => {
    router.get(route('pharmacists.index'), {
        search: searchQuery.value,
        role: selectedRole.value,
        perPage: perPage.value,
        sortField: sortField.value,
        sortDirection: sortDirection.value
    }, {
        preserveState: true,
        replace: true
    });
};

// Watchers pour la pagination locale
// (aucune requête backend n'est envoyée pour la recherche/filtrage)

// Rôles disponibles
const roles = [
    { value: 'pharmacist', label: 'Pharmacien' },
    { value: 'manager', label: 'Gestionnaire' },
    { value: 'admin', label: 'Administrateur' }
];

// Formater la date
const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Gestion des pharmaciens" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6 flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <h1 class="text-2xl font-semibold">Gestion des pharmaciens</h1>
                <Button @click="openCreate">
                    <Plus class="mr-2 h-4 w-4" />
                    Ajouter un pharmacien
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1">
                            <Input
                                v-model="searchQuery"
                                placeholder="Rechercher un pharmacien..."
                                class="max-w-sm"
                            >
                                <template #prefix>
                                    <Search class="h-4 w-4 text-muted-foreground" />
                                </template>
                            </Input>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Select v-model="selectedRole">
                                <SelectTrigger class="w-[180px]">
                                    <SelectValue placeholder="Tous les rôles" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="">Tous les rôles</SelectItem>
                                    <SelectItem v-for="role in roles" :key="role.value" :value="role.value">
                                        {{ role.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <Select v-model="perPage">
                                <SelectTrigger class="w-[100px]">
                                    <SelectValue :placeholder="`${perPage} / page`" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem :value="10">10 / page</SelectItem>
                                    <SelectItem :value="25">25 / page</SelectItem>
                                    <SelectItem :value="50">50 / page</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="cursor-pointer" @click="sort('name')">
                                        <div class="flex items-center">
                                            Nom
                                            <ArrowUpDown class="ml-2 h-4 w-4" />
                                        </div>
                                    </TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead class="cursor-pointer" @click="sort('role')">
                                        <div class="flex items-center">
                                            Rôle
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
                                <TableRow v-for="pharmacist in pharmacists.data" :key="pharmacist.id">
                                    <TableCell class="font-medium">{{ pharmacist.name }}</TableCell>
                                    <TableCell>{{ pharmacist.email }}</TableCell>
                                    <TableCell>
                                        <Badge variant="outline">
                                            {{ roles.find(r => r.value === pharmacist.role)?.label || pharmacist.role }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>{{ formatDate(pharmacist.created_at) }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end space-x-2">
                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                @click="openEdit(pharmacist)"
                                                v-if="true"
                                            >
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                @click="openDelete(pharmacist)"
                                                v-if="true"
                                            >
                                                <Trash2 class="h-4 w-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="pharmacists.data.length === 0">
                                    <TableCell colspan="5" class="text-center py-8 text-muted-foreground">
                                        Aucun pharmacien trouvé
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
                    <DialogTitle>Nouveau pharmacien</DialogTitle>
                    <DialogDescription>
                        Remplissez les informations pour ajouter un nouveau pharmacien
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitCreate">
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <label for="name" class="text-sm font-medium">Nom complet</label>
                            <Input
                                id="name"
                                v-model="createForm.name"
                                placeholder="Nom complet"
                                :class="{ 'border-destructive': createForm.errors.name }"
                            />
                            <p v-if="createForm.errors.name" class="text-sm text-destructive">
                                {{ createForm.errors.name }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="email" class="text-sm font-medium">Email</label>
                            <Input
                                id="email"
                                v-model="createForm.email"
                                type="email"
                                placeholder="Email"
                                :class="{ 'border-destructive': createForm.errors.email }"
                            />
                            <p v-if="createForm.errors.email" class="text-sm text-destructive">
                                {{ createForm.errors.email }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="password" class="text-sm font-medium">Mot de passe</label>
                            <Input
                                id="password"
                                v-model="createForm.password"
                                type="password"
                                placeholder="Mot de passe"
                                :class="{ 'border-destructive': createForm.errors.password }"
                            />
                            <p v-if="createForm.errors.password" class="text-sm text-destructive">
                                {{ createForm.errors.password }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="password_confirmation" class="text-sm font-medium">
                                Confirmer le mot de passe
                            </label>
                            <Input
                                id="password_confirmation"
                                v-model="createForm.password_confirmation"
                                type="password"
                                placeholder="Confirmer le mot de passe"
                            />
                        </div>
                        <div class="grid gap-2">
                            <label for="role" class="text-sm font-medium">Rôle</label>
                            <Select v-model="createForm.role">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un rôle" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="role in roles"
                                        :key="role.value"
                                        :value="role.value"
                                    >
                                        {{ role.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showCreate = false">
                            Annuler
                        </Button>
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
                    <DialogTitle>Modifier le pharmacien</DialogTitle>
                    <DialogDescription>
                        Modifiez les informations du pharmacien
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitEdit">
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <label for="edit-name" class="text-sm font-medium">Nom complet</label>
                            <Input
                                id="edit-name"
                                v-model="editForm.name"
                                placeholder="Nom complet"
                                :class="{ 'border-destructive': editForm.errors.name }"
                            />
                            <p v-if="editForm.errors.name" class="text-sm text-destructive">
                                {{ editForm.errors.name }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-email" class="text-sm font-medium">Email</label>
                            <Input
                                id="edit-email"
                                v-model="editForm.email"
                                type="email"
                                placeholder="Email"
                                :class="{ 'border-destructive': editForm.errors.email }"
                            />
                            <p v-if="editForm.errors.email" class="text-sm text-destructive">
                                {{ editForm.errors.email }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-password" class="text-sm font-medium">
                                Nouveau mot de passe (laisser vide pour ne pas changer)
                            </label>
                            <Input
                                id="edit-password"
                                v-model="editForm.password"
                                type="password"
                                placeholder="Nouveau mot de passe"
                                :class="{ 'border-destructive': editForm.errors.password }"
                            />
                            <p v-if="editForm.errors.password" class="text-sm text-destructive">
                                {{ editForm.errors.password }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-password_confirmation" class="text-sm font-medium">
                                Confirmer le nouveau mot de passe
                            </label>
                            <Input
                                id="edit-password_confirmation"
                                v-model="editForm.password_confirmation"
                                type="password"
                                placeholder="Confirmer le nouveau mot de passe"
                            />
                        </div>
                        <div class="grid gap-2">
                            <label for="edit-role" class="text-sm font-medium">Rôle</label>
                            <Select v-model="editForm.role">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un rôle" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="role in roles"
                                        :key="role.value"
                                        :value="role.value"
                                    >
                                        {{ role.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showEdit = false">
                            Annuler
                        </Button>
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
                        Êtes-vous sûr de vouloir supprimer le pharmacien
                        <span class="font-semibold">{{ current?.name }}</span> ?
                        Cette action est irréversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showDelete = false"
                    >
                        Annuler
                    </Button>
                    <Button
                        type="button"
                        variant="destructive"
                        @click="confirmDelete"

                    >
                        Supprimer
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
