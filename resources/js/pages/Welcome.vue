<template>
    <Head title="Dpharma - Gestion de Pharmacie">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Accéder au Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Connexion
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Inscription
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
                >
                    <div class="mb-6">
                        <div class="mb-4 flex flex-col items-center">
                            <AppLogoIcon class="h-16 w-16 text-green-600 mb-2" />
                            <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Dpharma</h1>
                        </div>
                        <p class="text-lg font-medium text-[#706f6c] dark:text-[#A1A09A]">
                            Système de Gestion de Pharmacie
                        </p>
                    </div>

                    <div class="mb-6">
                        <h2 class="mb-3 font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">À propos du projet</h2>
                        <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
                            Dpharma est une application web moderne de gestion de pharmacie développée dans le cadre
                            d'un projet scolaire.
                            Elle permet aux pharmaciens de gérer efficacement leur inventaire, leurs ventes et leurs
                            clients.
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="mb-3 font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Fonctionnalités
                            principales</h3>
                        <ul class="mb-4 flex flex-col space-y-2">
                            <li class="relative flex items-start gap-3 py-1">
                                <span
                                    class="mt-1.5 h-1.5 w-1.5 rounded-full bg-[#f53003] dark:bg-[#FF4433] flex-shrink-0" />
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">
                                    <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">Gestion des produits :</strong>
                                    Inventaire complet avec suivi des stocks et alertes de rupture
                                </span>
                            </li>
                            <li class="relative flex items-start gap-3 py-1">
                                <span
                                    class="mt-1.5 h-1.5 w-1.5 rounded-full bg-[#f53003] dark:bg-[#FF4433] flex-shrink-0" />
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">
                                    <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">Gestion des ventes :</strong>
                                    Système de caisse avec historique et rapports détaillés
                                </span>
                            </li>
                            <li class="relative flex items-start gap-3 py-1">
                                <span
                                    class="mt-1.5 h-1.5 w-1.5 rounded-full bg-[#f53003] dark:bg-[#FF4433] flex-shrink-0" />
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">
                                    <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">Gestion des clients :</strong>
                                    Base de données clients avec historique d'achats
                                </span>
                            </li>
                            <li class="relative flex items-start gap-3 py-1">
                                <span
                                    class="mt-1.5 h-1.5 w-1.5 rounded-full bg-[#f53003] dark:bg-[#FF4433] flex-shrink-0" />
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">
                                    <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">Dashboard analytique :</strong>
                                    Statistiques en temps réel et graphiques de performance
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h3 class="mb-3 font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Technologies utilisées</h3>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="rounded-md bg-[#f8f9fa] dark:bg-[#2a2a2a] p-3">
                                <div class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Backend</div>
                                <div class="mt-1 text-[#706f6c] dark:text-[#A1A09A]">Laravel 12</div>
                                <div class="text-[#706f6c] dark:text-[#A1A09A]">PHP 8.3</div>
                                <div class="text-[#706f6c] dark:text-[#A1A09A]">SQLite</div>
                            </div>
                            <div class="rounded-md bg-[#f8f9fa] dark:bg-[#2a2a2a] p-3">
                                <div class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Frontend</div>
                                <div class="mt-1 text-[#706f6c] dark:text-[#A1A09A]">Vue.js 3</div>
                                <div class="text-[#706f6c] dark:text-[#A1A09A]">Reka UI</div>
                                <div class="text-[#706f6c] dark:text-[#A1A09A]">TypeScript</div>
                            </div>
                            <div class="rounded-md bg-[#f8f9fa] dark:bg-[#2a2a2a] p-3">
                                <div class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Styling</div>
                                <div class="text-[#706f6c] dark:text-[#A1A09A]">Tailwind CSS</div>
                            </div>
                            <div class="rounded-md bg-[#f8f9fa] dark:bg-[#2a2a2a] p-3">
                                <div class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Graphiques</div>
                                <div class="text-[#706f6c] dark:text-[#A1A09A]">Chart.js</div>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            Projet encadré par le Dr Koala - Enseignant référent
                        </div>
                        <div class="mt-4">
                            <a
                                href="https://github.com/BoubakarPI/dpharma.boukary.dev"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-sm text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC]"
                            >
                                <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                </svg>
                                Voir sur GitHub
                            </a>
                        </div>
                    </div>

                    <div class="flex gap-3 text-sm leading-normal">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('register')"
                            class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                        >
                            Commencer l'exploration
                        </Link>
                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                        >
                            Accéder au Dashboard
                        </Link>
                    </div>

                    <div class="mt-6 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">Projet académique</strong> -
                            Développé pour démontrer les compétences en développement web full-stack avec des
                            technologies modernes.
                        </p>
                    </div>
                </div>

            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>
