<template>
    <form @submit.prevent="submit" class="space-y-6">
        <!-- Première rangée : Nom et Forme -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Nom -->
            <div class="space-y-2">
                <Label for="name">Nom <span class="text-red-500">*</span></Label>
                <Input
                    id="name"
                    v-model="form.name"
                    :error="!!form.errors.name"
                    placeholder="Nom du produit"
                    required
                />
                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
            </div>

            <!-- Champ Forme -->
            <div class="space-y-2">
                <Label for="form">Forme <span class="text-red-500">*</span></Label>
                <Select v-model="form.form" :error="!!form.errors.form" required>
                    <SelectTrigger>
                        <SelectValue placeholder="Sélectionnez une forme" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="tablet">Comprimé</SelectItem>
                        <SelectItem value="capsule">Gélule</SelectItem>
                        <SelectItem value="syrup">Sirop</SelectItem>
                        <SelectItem value="injection">Injection</SelectItem>
                        <SelectItem value="cream">Crème</SelectItem>
                        <SelectItem value="drops">Gouttes</SelectItem>
                        <SelectItem value="inhaler">Inhalateur</SelectItem>
                        <SelectItem value="other">Autre</SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.form" class="text-sm text-red-500">{{ form.errors.form }}</p>
            </div>
        </div>

        <!-- Deuxième rangée : Dosage et Catégorie -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Dosage -->
            <div class="space-y-2">
                <Label for="dosage">Dosage <span class="text-red-500">*</span></Label>
                <Input
                    id="dosage"
                    v-model="form.dosage"
                    :error="!!form.errors.dosage"
                    placeholder="Ex: 500mg, 10ml"
                    required
                />
                <p v-if="form.errors.dosage" class="text-sm text-red-500">{{ form.errors.dosage }}</p>
            </div>

            <!-- Champ Catégorie -->
            <div class="space-y-2">
                <Label for="category_id">Catégorie</Label>
                <Select v-model="form.category_id" :error="!!form.errors.category_id">
                    <SelectTrigger>
                        <SelectValue placeholder="Sélectionnez une catégorie" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="null">
                            <span class="text-muted-foreground">Aucune catégorie</span>
                        </SelectItem>
                        <SelectItem v-for="category in props.categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.category_id" class="text-sm text-red-500">{{ form.errors.category_id }}</p>
            </div>
        </div>

        <!-- Troisième rangée : Prix, Quantité et N° de lot -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Champ Prix -->
            <div class="space-y-2">
                <Label for="price">Prix (FCFA) <span class="text-red-500">*</span></Label>
                <Input
                    type="number"
                    step="0.01"
                    min="0"
                    id="price"
                    v-model="form.price"
                    :error="!!form.errors.price"
                    placeholder="0.00"
                    required
                />
                <p v-if="form.errors.price" class="text-sm text-red-500">{{ form.errors.price }}</p>
            </div>

            <!-- Champ Quantité en stock -->
            <div class="space-y-2">
                <Label for="stock_quantity">Quantité en stock <span class="text-red-500">*</span></Label>
                <Input
                    type="number"
                    min="0"
                    id="stock_quantity"
                    v-model="form.stock_quantity"
                    :error="!!form.errors.stock_quantity"
                    required
                />
                <p v-if="form.errors.stock_quantity" class="text-sm text-red-500">{{ form.errors.stock_quantity }}</p>
            </div>

            <!-- Champ N° de lot -->
            <div class="space-y-2">
                <Label for="batch_number">N° de lot <span class="text-red-500">*</span></Label>
                <Input
                    id="batch_number"
                    v-model="form.batch_number"
                    :error="!!form.errors.batch_number"
                    required
                />
                <p v-if="form.errors.batch_number" class="text-sm text-red-500">{{ form.errors.batch_number }}</p>
            </div>
        </div>

        <!-- Quatrième rangée : Fabricant et Date d'expiration -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Fabricant -->
            <div class="space-y-2">
                <Label for="manufacturer">Fabricant <span class="text-red-500">*</span></Label>
                <Input
                    id="manufacturer"
                    v-model="form.manufacturer"
                    :error="!!form.errors.manufacturer"
                    required
                />
                <p v-if="form.errors.manufacturer" class="text-sm text-red-500">{{ form.errors.manufacturer }}</p>
            </div>

            <!-- Champ Date d'expiration -->
            <div class="space-y-2">
                <Label for="expiration_date">Date d'expiration <span class="text-red-500">*</span></Label>
                <Input
                    type="date"
                    id="expiration_date"
                    v-model="form.expiration_date"
                    :error="!!form.errors.expiration_date"
                    required
                    :min="new Date().toISOString().split('T')[0]"
                />
                <p v-if="form.errors.expiration_date" class="text-sm text-red-500">
                    {{ form.errors.expiration_date }}
                </p>
            </div>
        </div>

        <!-- Cinquième section : Cases à cocher -->
        <div class="space-y-4">
            <!-- Case à cocher Ordonnance requise -->
            <div class="flex items-center space-x-2">
                <Checkbox
                    id="requires_prescription"
                    v-model:checked="form.requires_prescription"
                    :error="!!form.errors.requires_prescription"
                />
                <Label for="requires_prescription" class="font-normal">Ordonnance requise</Label>
                <p v-if="form.errors.requires_prescription" class="text-sm text-red-500">
                    {{ form.errors.requires_prescription }}
                </p>
            </div>

            <!-- Case à cocher Actif -->
            <div class="flex items-center space-x-2">
                <Checkbox
                    id="is_active"
                    v-model:checked="form.is_active"
                    :error="!!form.errors.is_active"
                />
                <Label for="is_active" class="font-normal">Actif</Label>
                <p v-if="form.errors.is_active" class="text-sm text-red-500">{{ form.errors.is_active }}</p>
            </div>
        </div>

        <!-- Champ Description -->
        <div class="space-y-2">
            <Label for="description">Description</Label>
            <Textarea
                id="description"
                v-model="form.description"
                rows="3"
                :error="!!form.errors.description"
                placeholder="Description détaillée du produit..."
            />
            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-3 pt-6">
            <Button
                type="button"
                variant="outline"
                @click="$emit('cancel')"
                :disabled="form.processing"
            >
                Annuler
            </Button>
            <Button
                type="submit"
                :disabled="form.processing"
                :loading="form.processing"
            >
                {{ mode === 'create' ? 'Créer le produit' : 'Mettre à jour' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import type { Category, PharmaceuticalProduct } from '@/types/models';

// UI Components
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Props {
    modelValue?: Partial<PharmaceuticalProduct> | null;
    mode?: 'create' | 'edit';
    categories: Category[];
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: () => ({}),
    mode: 'create',
    categories: () => []
});

const formattedExpirationDate = computed<string>({
    get: () => {
        const iso = props.modelValue?.expiration_date;
        return iso ? iso.substring(0, 10) : '';
    },
    set: (value: string) => {
        if (value) {
            const date = new Date(value);
            form.expiration_date = date.toISOString();
        } else {
            form.expiration_date = '';
        }
    }
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: Partial<PharmaceuticalProduct>): void;
    (e: 'cancel'): void;
    (e: 'saved'): void;
}>();

// Inclure l'ID du modèle s'il existe (pour la mise à jour)
const form = useForm({
    ...(props.modelValue?.id && { id: props.modelValue.id }), // Inclure l'ID seulement en mode édition
    name: props.modelValue?.name || '',
    form: props.modelValue?.form || 'tablet',
    dosage: props.modelValue?.dosage || '',
    description: props.modelValue?.description || '',
    price: props.modelValue?.price || 0,
    stock_quantity: props.modelValue?.stock_quantity || 0,
    manufacturer: props.modelValue?.manufacturer || '',
    batch_number: props.modelValue?.batch_number || '',
    expiration_date: formattedExpirationDate.value || '',
    requires_prescription: props.modelValue?.requires_prescription || false,
    is_active: props.modelValue?.is_active ?? true,
    category_id: props.modelValue?.category_id || null
});

const submit = async () => {
    try {
        if (props.mode === 'create') {
            form.post(route('pharmaceutical-products.store'), {
                onSuccess: () => {
                    emit('saved');
                },
                onError: (errors) => {
                    console.error('Erreur lors de la création du produit:', errors);
                    toast.error('Veuillez vérifier les champs du formulaire');
                }
            });
        } else {
            form.put(route('pharmaceutical-products.update', props.modelValue?.id), {
                onSuccess: () => {
                    emit('saved');
                },
                onError: (errors) => {
                    console.error('Erreur lors de la mise à jour du produit:', errors);
                    toast.error('Veuillez vérifier les champs du formulaire');
                }
            });
        }
    } catch (error) {
        console.error('Erreur inattendue:', error);
        toast.error('Une erreur inattendue est survenue');
    }
};
</script>
