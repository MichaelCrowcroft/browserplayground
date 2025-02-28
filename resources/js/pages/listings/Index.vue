<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Listing, type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    listings?: Array<Listing>;
}>();
</script>

<template>
    <Head title="Game Directory" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <Heading title="Game Directory" description="⭐ Discover amazing indie games from our community! ⭐" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template v-if="listings.data.length > 0">
                    <Card v-for="listing in listings.data" :key="listing.id" class="overflow-hidden border-2 border-gray-900 shadow-md hover:shadow-xl transition-all duration-300 bg-gray-50 group">
                        <CardHeader class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
                            <CardTitle class="text-white font-mono text-xl truncate">{{ listing.name }}</CardTitle>
                        </CardHeader>
                        <img :src="listing.image" class="w-full h-48 object-cover" />

                        <CardContent class="p-6">
                            <p class="text-gray-700 line-clamp-3 mb-4 h-28">{{ listing.description }}</p>
                            <!-- <Badge class="bg-indigo-100 text-indigo-800 hover:bg-indigo-200 hover:cursor-default">Indie Game</Badge> */} -->
                        </CardContent>

                        <CardFooter class="bg-gray-100 p-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <a target="_blank" :href="'https://www.x.com/' + listing.builder">
                                    <span class="text-sm font-medium text-gray-700 hover:cursor underline">{{ listing.builder }}</span>
                                </a>
                            </div>

                            <a target="_blank" :href="listing.link">
                                <Button size="lg" class="uppercase">Play</Button>
                            </a>
                        </CardFooter>
                    </Card>
                </template>

                <div v-else class="col-span-full text-center p-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                    <div class="text-xl text-gray-500 mb-2 uppercase">No Games Found</div>
                    <p class="text-gray-500 mb-4">Be the first to add a game to our directory!</p>
                    <Link :href="route('listings.create')" prefetch>
                        <Button>
                            Add Your Game
                            <ArrowRight class="ml-1 size-4" />
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
