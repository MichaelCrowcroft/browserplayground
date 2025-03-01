<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import Comment from '@/components/Comment.vue';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Listing, SharedData, User, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    listing: Listing;
}>();

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const commentForm = useForm({
    body: '',
});

const addComment = () => commentForm.post(route('listings.comments.store', { listing: props.listing.id }), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
});
</script>

<template>
    <Head title="Game Directory" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <Heading :title="listing.name" />

            <div class="block md:flex justify-between gap-6">
                <Card class="border-2 md:mb-0 mb-6 w-full md:w-2/5 h-fit border-gray-900 shadow-md hover:shadow-xl transition-all duration-300 bg-gray-50">
                    <CardHeader class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
                        <CardTitle class="text-white font-mono text-xl truncate">{{ listing.name }}</CardTitle>
                    </CardHeader>
                    <img :src="listing.image" class="w-full h-48 object-cover" />

                    <CardContent class="p-6">
                        <p class="text-gray-700 mb-4">{{ listing.description }}</p>
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

                <Card class="border-2 border-gray-900 shadow-lg bg-gray-50 flex-grow">
                    <CardHeader class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4">
                        <h3 class="text-xl font-mono font-bold text-white text-center uppercase">Comments</h3>
                    </CardHeader>

                    <CardContent class="p-6">
                        <template v-for="comment in listing.comments" :key="comment.id">
                            <Comment :comment />
                        </template>

                        <form @submit.prevent="addComment" class="space-y-2" v-if="user">
                            <div class="grid gap-3">
                                <Label for="body" class="text-lg font-mono text-gray-800 flex items-center uppercase">
                                    Comment
                                </Label>

                                <Textarea
                                    id="description"
                                    class="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium resize-none min-h-32"
                                    v-model="commentForm.body"
                                    required
                                    placeholder="What do you think?"
                                />

                                <InputError class="mt-2 font-mono" :message="commentForm.errors.body" />
                            </div>
                            <Button size="lg">
                                Add Comment
                            </Button>
                        </form>

                        <div v-else>
                            <Link class="hover:cursor-pointer underline" :href="route('login')">Login</Link> or <Link class="hover:cursor-pointer underline" :href="route('register')">register</Link> to leave a comment.
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
