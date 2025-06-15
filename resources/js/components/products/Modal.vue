<template>
    <Teleport to="body">
        <transition name="fade">
            <div v-show="modelValue" class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50" @click="close"></div>

                <!-- Modal -->
                <transition name="scale">
                    <div v-show="modelValue"
                         class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-hidden"
                         @keydown.esc="close">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-3 border-b">
                            <h2 class="text-lg font-medium text-gray-800">
                                <slot name="title" />
                            </h2>
                            <button class="text-gray-400 hover:text-gray-600" @click="close">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-6">
                            <slot />
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </Teleport>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

// Ajout d'un watch pour le débogage
watch(() => props.modelValue, (newVal) => {
    console.log('Modal - modelValue changed:', newVal);
});

function close() {
    console.log('Fermeture du modal');
    emit('update:modelValue', false);
}

// Fermer avec la touche ESC
const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.modelValue) {
        close();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
});
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.scale-enter-active, .scale-leave-active {
    transition: transform 0.2s ease;
}

.scale-enter-from, .scale-leave-to {
    transform: scale(0.95);
}
</style>
