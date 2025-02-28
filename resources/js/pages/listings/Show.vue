<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Listing, type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    listing: Listing;
}>();
</script>

<template>
    <Head title="Game Directory" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <Heading :title="listing.name" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card class="overflow-hidden border-2 border-gray-900 shadow-md hover:shadow-xl transition-all duration-300 bg-gray-50 group">
                    <CardHeader class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
                        <CardTitle class="text-white font-mono text-xl truncate">{{ listing.name }}</CardTitle>
                    </CardHeader>

                    <CardContent class="p-6">
                        <p class="text-gray-700 line-clamp-3 mb-4 h-20">{{ listing.description }}</p>
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
            </div>
        </div>
    </AppLayout>
</template>
