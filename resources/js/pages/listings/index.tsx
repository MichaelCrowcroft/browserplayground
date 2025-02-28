import { type Listing, type BreadcrumbItem } from '@/types';
import { useInitials } from '@/hooks/use-initials';
import { Head, Link } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';
import Heading from '@/components/heading';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ArrowRight } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Listings',
        href: '/listings',
    },
];

export default function Index({ listings }) {
    const getInitials = useInitials();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Game Directory" />

        <div className="px-4 py-6 md:px-8">
            <Heading title="Game Directory" description="⭐ Discover amazing indie games from our community! ⭐" />

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {listings.data.length > 0 ? (listings.data.map((listing) => (
                <Card key={listing.id} className="overflow-hidden border-2 border-gray-900 shadow-md hover:shadow-xl transition-all duration-300 bg-gray-50 group">
                    <CardHeader className="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
                        <CardTitle className="text-white font-mono text-xl truncate">{listing.name}</CardTitle>
                    </CardHeader>

                    <CardContent className="p-6">
                        <p className="text-gray-700 line-clamp-3 mb-4 h-20">{listing.description}</p>
                        {/* <Badge className="bg-indigo-100 text-indigo-800 hover:bg-indigo-200 hover:cursor-default">Indie Game</Badge> */}
                    </CardContent>

                    <CardFooter className="bg-gray-100 p-4 border-t border-gray-200 flex items-center justify-between">
                        <div className="flex items-center space-x-3">
                            <a target="_blank" href={"https://www.x.com/" + listing.builder}>
                                <span className="text-sm font-medium text-gray-700 hover:cursor underline">{listing.builder}</span>
                            </a>
                        </div>

                        <a target="_blank" href={listing.link}>
                            <Button size="lg" className="uppercase">Play</Button>
                        </a>
                    </CardFooter>
                </Card>
                ))
            ) : (
                <div className="col-span-full text-center p-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                <div className="text-xl text-gray-500 mb-2 uppercase">No Games Found</div>
                <p className="text-gray-500 mb-4">Be the first to add a game to our directory!</p>
                <Link href={route('listings.create')} prefetch>
                    <Button>
                        Add Your Game
                        <ArrowRight className="ml-1 size-4" />
                    </Button>
                </Link>

                </div>
            )}
            </div>

            {/* Add New Game Button */}
            {listings && listings.length > 0 && (
            <div className="mt-10 text-center">
                <Button
                className="bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-900 shadow-md font-mono px-6 py-4 text-lg"
                onClick={() => window.location.href = '/listings/create'}
                >
                ADD NEW GAME
                <ArrowRight className="ml-2 h-5 w-5" />
                </Button>
            </div>
            )}
        </div>
        </AppLayout>
    );
}