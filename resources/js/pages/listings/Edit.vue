<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Listing, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    listing: Listing;
}>();

const imageInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(null);

const form = useForm({
    _method: 'PATCH',
    name: props.listing.name,
    link: props.listing.link,
    description: props.listing.description,
    builder: props.listing.builder,
    image: null as File | null,
});

const submit = () => {
    form.post(route('listings.update', {
        listing: props.listing.id,
    }), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            imagePreview.value = null;
            if (imageInput.value) {
                imageInput.value.value = '';
            }
        },
    });
};

const selectNewImage = () => {
    imageInput.value?.click();
};

const updateImagePreview = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.image = target.files[0];

        const reader = new FileReader();
        reader.onload = (e) => {
            if (e.target) {
                imagePreview.value = e.target.result as string;
            }
        };
        reader.readAsDataURL(form.image);
    }
};
</script>

<template>
    <Head title="New Game" />

    <AppLayout :breadcrumbs="breadcrumbs">
            <div class="px-4 py-6 md:px-8">
                <Heading title="New Game" description="🔥 Add a new game to share it with the community! 🔥" />

                <div class="space-y-6 md:max-w-2xl mx-auto">
                    <Card class="border-2 border-gray-900 shadow-lg bg-gray-50 overflow-hidden">
                        <CardHeader class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4">
                            <h3 class="text-xl font-mono font-bold text-white text-center uppercase">Game Details</h3>
                        </CardHeader>

                        <CardContent class="p-6 pt-8">
                            <form @submit.prevent="submit" class="space-y-8">
                                <div class="grid gap-2">
                                    <Label for="Image" class="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Image
                                    </Label>
                                    <div class="w-full">
                                        <div class="relative overflow-hidden bg-neutral-100 dark:bg-neutral-800">
                                            <img
                                                v-if="imagePreview"
                                                :src="imagePreview"
                                                alt="Image Preview"
                                                class="h-full w-full object-cover"
                                            />

                                            <img
                                                v-else-if="listing.image"
                                                :src="listing.image"
                                                alt="Current image"
                                                class="h-full w-full object-cover"
                                            />
                                            <div v-else class="flex h-full py-8 w-full items-center justify-center text-lg font-medium text-neutral-500">
                                                <p>No Image</p>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <Button type="button" variant="outline" size="sm" @click="selectNewImage">
                                                Change Image
                                            </Button>
                                            <input
                                                ref="imageInput"
                                                type="file"
                                                class="hidden"
                                                @change="updateImagePreview"
                                                accept="image/*"
                                            />
                                            <p class="text-xs text-muted-foreground mt-2">
                                                JPG, PNG or GIF. 4MB max.
                                            </p>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.image" />
                                </div>

                                <div class="grid gap-3">
                                    <Label for="builder" class="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Builder<span class="text-sm lowercase ml-2">(Only X handles supported)</span>
                                    </Label>

                                    <Input
                                        id="builder"
                                        class="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium"
                                        v-model="form.builder"
                                        required
                                        placeholder="@levelsio"
                                    />

                                    <InputError class="mt-2 font-mono" :message="form.errors.builder" />
                                </div>

                                <div class="grid gap-3">
                                    <Label for="name" class="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Game Name
                                    </Label>

                                    <Input
                                        id="name"
                                        class="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium"
                                        v-model="form.name"
                                        required
                                        placeholder="Pieter Flight Sim"
                                    />

                                    <InputError class="mt-2 font-mono" :message="form.errors.name" />
                                </div>

                                <div class="grid gap-3">
                                    <Label for="link" class="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Game Link
                                    </Label>

                                    <Input
                                        id="link"
                                        type="url"
                                        class="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium"
                                        v-model="form.link"
                                        required
                                        placeholder="https://fly.pieter.com"
                                    />

                                    <InputError class="mt-2 font-mono" :message="form.errors.link" />
                                </div>

                                <div class="grid gap-3">
                                    <Label for="description" class="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Description
                                    </Label>
                                    <Textarea
                                        id="description"
                                        class="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium resize-none min-h-32"
                                        v-model="form.description"
                                        required
                                        placeholder="The first MMO Flight Sim"
                                    />

                                    <InputError class="mt-2 font-mono" :message="form.errors.description" />
                                </div>

                                <CardFooter class="px-0 pt-4 flex items-center justify-between">
                                    <Button size="lg">
                                        Save Game
                                    </Button>
                                </CardFooter>
                            </form>
                        </CardContent>
                    </Card>
                </div>

                <div class="text-center mt-8">
                    <Link :href="route('listings.index')" prefetch
                        class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-mono underline underline-offset-4"
                    >
                        ← BACK TO GAME DIRECTORY
                    </Link>
                </div>
            </div>
    </AppLayout>
</template>
