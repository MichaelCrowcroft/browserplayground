import { Listing, type BreadcrumbItem, type SharedData } from '@/types';
import { Head } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';
import Heading from '@/components/heading';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Listings',
        href: '/listings',
    },
];

export default function Show({ listing }: { listing: Listing }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create listing" />

            <div className="px-4 py-6 space-y-6 md:max-w-2xl">
                <Heading title="Create a Listing" description="Add a link to your game and get discovered!" />
                <p>{listing.name}</p>

            </div>
        </AppLayout>
    )
};