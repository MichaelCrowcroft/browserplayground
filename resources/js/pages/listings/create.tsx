import { type BreadcrumbItem, type SharedData } from '@/types';
import { useInitials } from '@/hooks/use-initials';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import Heading from '@/components/heading';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Listings',
        href: '/listings',
    },
];

export default function Create() {
    const getInitials = useInitials();
    const { auth } = usePage<SharedData>().props;

    const { data, setData, post, errors, processing } = useForm({
        name: '',
        link: '',
        description: '',
        builder: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('listings.store'), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="New Game" />
            <div className="px-4 py-6 md:px-8">
                <Heading title="New Game" description="🔥 Add a new game to share it with the community! 🔥" />

                <div className="space-y-6 md:max-w-2xl mx-auto">
                    <Card className="border-2 border-gray-900 shadow-lg bg-gray-50 overflow-hidden">
                        <CardHeader className="bg-gradient-to-r from-indigo-500 to-purple-600 py-4">
                            <h3 className="text-xl font-mono font-bold text-white text-center uppercase">Game Details</h3>
                        </CardHeader>

                        <CardContent className="p-6 pt-8">
                            <form onSubmit={submit} className="space-y-8">
                                <div className="grid gap-3">
                                    <Label htmlFor="builder" className="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Builder<span className="text-sm lowercase ml-2">(Only X handles supported)</span>
                                    </Label>

                                    <Input
                                        id="builder"
                                        className="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium"
                                        value={data.builder}
                                        onChange={(e) => setData('builder', e.target.value)}
                                        required
                                        placeholder="@levelsio"
                                    />

                                    <InputError className="mt-2 font-mono" message={errors.builder} />
                                </div>

                                <div className="grid gap-3">
                                    <Label htmlFor="name" className="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Game Name
                                    </Label>

                                    <Input
                                        id="name"
                                        className="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        required
                                        autoComplete="name"
                                        placeholder="Pieter Flight Sim"
                                    />

                                    <InputError className="mt-2 font-mono" message={errors.name} />
                                </div>

                                <div className="grid gap-3">
                                    <Label htmlFor="link" className="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Game Link
                                    </Label>

                                    <Input
                                        id="link"
                                        type="url"
                                        className="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium"
                                        value={data.link}
                                        onChange={(e) => setData('link', e.target.value)}
                                        required
                                        placeholder="https://fly.pieter.com"
                                    />

                                    <InputError className="mt-2 font-mono" message={errors.link} />
                                </div>

                                <div className="grid gap-3">
                                    <Label htmlFor="description" className="text-lg font-mono text-gray-800 flex items-center uppercase">
                                        Description
                                    </Label>
                                    <Textarea
                                        id="description"
                                        className="mt-1 block w-full border-2 border-indigo-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white py-2 px-3 font-medium resize-none min-h-32"
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        required
                                        placeholder="The first MMO Flight Sim"
                                    />

                                    <InputError className="mt-2 font-mono" message={errors.description} />
                                </div>

                                <CardFooter className="px-0 pt-4 flex items-center justify-between">
                                    <Button size="lg" disabled={processing}>
                                        Save Game
                                    </Button>
                                </CardFooter>
                            </form>
                        </CardContent>
                    </Card>
                </div>

                <div className="text-center mt-8">
                    <Link href={route('listings.index')} prefetch
                        className="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-mono underline underline-offset-4"
                    >
                        ← BACK TO GAME DIRECTORY
                    </Link>
                </div>
            </div>

        </AppLayout>
    );
}
