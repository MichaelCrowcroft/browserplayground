<script setup lang="ts">
import { relativeDate } from '@/lib/utils.js';

defineProps<{
    comment: Comment,
}>();

defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="sm:flex mb-6">
        <!-- <div class="mb-4 flex-shrink-0">
            <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full" />
        </div> -->
        <div class="flex-1">
            <div class="mt-2max-w-none">{{ comment.body }}</div>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago</span>
            <!-- <div class="mt-2 flex justify-end space-x-3 empty:hidden">

                <form v-if="comment.can?.update" @submit.prevent="$emit('edit', comment.id)">
                    <button class="text-xs hover:underline">Edit</button>
                </form>

                <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">
                    <button class="text-red-700 text-xs hover:underline">Delete</button>
                </form>
            </div> -->
        </div>
    </div>
</template>