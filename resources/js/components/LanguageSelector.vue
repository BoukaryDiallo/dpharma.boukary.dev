<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';

const { t, locale } = useI18n();
const page = usePage();

const changeLanguage = (lang: string) => {
    locale.value = lang;
    // Update the language in the backend
    page.props.flash.success = t('dashboard.language.changed');
};
</script>

<template>
    <div class="flex items-center gap-2">
        <span>{{ t('dashboard.language.title') }}</span>
        <select
            v-model="locale"
            @change="changeLanguage($event.target.value)"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        >
            <option value="fr">{{ t('dashboard.language.fr') }}</option>
            <option value="en">{{ t('dashboard.language.en') }}</option>
        </select>
    </div>
</template>
