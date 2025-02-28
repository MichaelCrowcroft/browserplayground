<script setup lang="ts">

interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="mt-6 mb-10 p-6 bg-gradient-to-r from-purple-600 to-indigo-800 rounded-lg border-4 border-black shadow-lg">
        <h2 class="text-3xl font-bold text-white text-center mb-2 font-mono uppercase">{{ title }}</h2>
        <p v-if="description" class="text-white text-center text-lg">
            {{ description }}
        </p>
    </div>
</template>
