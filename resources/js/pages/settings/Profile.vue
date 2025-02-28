<script setup lang="ts">
import { TransitionRoot } from '@headlessui/vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const avatarInput = ref<HTMLInputElement | null>(null);
const avatarPreview = ref<string | null>(null);

const form = useForm({
    _method: 'PATCH',
    name: user.name,
    email: user.email,
    avatar: null as File | null,
});

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            avatarPreview.value = null;
            if (avatarInput.value) {
                avatarInput.value.value = '';
            }
        },
    });
};

const selectNewAvatar = () => {
    avatarInput.value?.click();
};

const updateAvatarPreview = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.avatar = target.files[0];

        const reader = new FileReader();
        reader.onload = (e) => {
            if (e.target) {
                avatarPreview.value = e.target.result as string;
            }
        };
        reader.readAsDataURL(form.avatar);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">

                        <Label>Profile Picture</Label>
                        {{ user.avatar }}
                        <div class="flex items-center gap-4">
                            <div class="relative h-20 w-20 overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-800">
                                <img
                                    v-if="avatarPreview"
                                    :src="avatarPreview"
                                    alt="Avatar Preview"
                                    class="h-full w-full object-cover"
                                />

                                <img
                                    v-else-if="user.avatar"
                                    :src="user.avatar"
                                    alt="Current Avatar"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center text-lg font-medium text-neutral-500">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <Button type="button" variant="outline" size="sm" @click="selectNewAvatar">
                                    Change Picture
                                </Button>
                                <input
                                    ref="avatarInput"
                                    type="file"
                                    class="hidden"
                                    @change="updateAvatarPreview"
                                    accept="image/*"
                                />
                                <p class="text-xs text-muted-foreground">
                                    JPG, PNG or GIF. 4MB max.
                                </p>
                            </div>
                        </div>

                        <InputError class="mt-2" :message="form.errors.avatar" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="hover:decoration-current! text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <TransitionRoot
                            :show="form.recentlySuccessful"
                            enter="transition ease-in-out"
                            enter-from="opacity-0"
                            leave="transition ease-in-out"
                            leave-to="opacity-0"
                        >
                            <p class="text-sm text-neutral-600">Saved.</p>
                        </TransitionRoot>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
