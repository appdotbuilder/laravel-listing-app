import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Listing {
    id: number;
    title: string;
    description: string;
    price_per_day: number;
    location: string;
    image_path?: string;
    creator: {
        name: string;
    };
}

interface Props {
    listings: {
        data: Listing[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    [key: string]: unknown;
}

export default function ListingsIndex({ listings }: Props) {
    const { auth } = usePage<{ auth: { user: { role: string } | null } }>().props;

    return (
        <AppShell>
            <Head title="Browse Listings" />
            
            <div className="py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="md:flex md:items-center md:justify-between mb-8">
                        <div className="flex-1 min-w-0">
                            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                                🏠 Browse Listings
                            </h1>
                            <p className="mt-2 text-gray-600 dark:text-gray-400">
                                Discover amazing places to book for your next adventure
                            </p>
                        </div>
                        {auth?.user?.role === 'Creator' && (
                            <div className="mt-4 md:mt-0">
                                <Link
                                    href={route('listings.create')}
                                    className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
                                >
                                    ✨ Create Listing
                                </Link>
                            </div>
                        )}
                    </div>

                    {listings.data.length === 0 ? (
                        <div className="text-center py-12">
                            <div className="text-6xl mb-4">🏠</div>
                            <h3 className="text-lg font-medium text-gray-900 mb-2 dark:text-white">
                                No listings found
                            </h3>
                            <p className="text-gray-600 dark:text-gray-400">
                                Check back later for new listings, or create your own!
                            </p>
                        </div>
                    ) : (
                        <>
                            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                {listings.data.map((listing) => (
                                    <div key={listing.id} className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow dark:bg-gray-800">
                                        <div className="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 relative">
                                            {listing.image_path ? (
                                                <img
                                                    src={`/storage/${listing.image_path}`}
                                                    alt={listing.title}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center text-white text-6xl">
                                                    🏠
                                                </div>
                                            )}
                                        </div>
                                        <div className="p-6">
                                            <h3 className="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
                                                {listing.title}
                                            </h3>
                                            <p className="text-gray-600 text-sm mb-2 dark:text-gray-400">
                                                📍 {listing.location}
                                            </p>
                                            <p className="text-gray-600 text-sm mb-4 dark:text-gray-400">
                                                by {listing.creator.name}
                                            </p>
                                            <div className="flex items-center justify-between">
                                                <span className="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                                    ${listing.price_per_day}/day
                                                </span>
                                                <Link
                                                    href={route('listings.show', listing.id)}
                                                    className="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-600 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300"
                                                >
                                                    View
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Pagination */}
                            {listings.links.length > 3 && (
                                <div className="mt-8 flex justify-center">
                                    <nav className="flex space-x-2">
                                        {listings.links.map((link, index) => (
                                            <span key={index}>
                                                {link.url ? (
                                                    <Link
                                                        href={link.url}
                                                        className={`px-3 py-2 rounded-md text-sm font-medium ${
                                                            link.active
                                                                ? 'bg-indigo-600 text-white'
                                                                : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700'
                                                        }`}
                                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                                    />
                                                ) : (
                                                    <span
                                                        className="px-3 py-2 rounded-md text-sm font-medium text-gray-400"
                                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                                    />
                                                )}
                                            </span>
                                        ))}
                                    </nav>
                                </div>
                            )}
                        </>
                    )}
                </div>
            </div>
        </AppShell>
    );
}