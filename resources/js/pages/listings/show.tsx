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
        id: number;
        name: string;
    };
}

interface Props {
    listing: Listing;
    [key: string]: unknown;
}

export default function ListingShow({ listing }: Props) {
    const { auth } = usePage<{ auth: { user: { id: number; role: string } | null } }>().props;
    const canEdit = auth?.user?.id === listing.creator.id || auth?.user?.role === 'SuperAdmin';
    const canBook = auth?.user?.role === 'EndUser';

    return (
        <AppShell>
            <Head title={listing.title} />
            
            <div className="py-8">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="mb-6">
                        <Link
                            href={route('listings.index')}
                            className="text-indigo-600 hover:text-indigo-700 text-sm font-medium dark:text-indigo-400"
                        >
                            ← Back to listings
                        </Link>
                    </div>

                    <div className="bg-white rounded-lg shadow-lg overflow-hidden dark:bg-gray-800">
                        <div className="h-64 md:h-96 bg-gradient-to-br from-indigo-400 to-purple-500 relative">
                            {listing.image_path ? (
                                <img
                                    src={`/storage/${listing.image_path}`}
                                    alt={listing.title}
                                    className="w-full h-full object-cover"
                                />
                            ) : (
                                <div className="w-full h-full flex items-center justify-center text-white text-8xl">
                                    🏠
                                </div>
                            )}
                        </div>

                        <div className="p-8">
                            <div className="flex justify-between items-start mb-6">
                                <div>
                                    <h1 className="text-3xl font-bold text-gray-900 mb-2 dark:text-white">
                                        {listing.title}
                                    </h1>
                                    <p className="text-gray-600 mb-2 dark:text-gray-400">
                                        📍 {listing.location}
                                    </p>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        Created by {listing.creator.name}
                                    </p>
                                </div>
                                <div className="text-right">
                                    <div className="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                        ${listing.price_per_day}
                                    </div>
                                    <div className="text-gray-600 dark:text-gray-400">per day</div>
                                </div>
                            </div>

                            <div className="mb-8">
                                <h2 className="text-xl font-semibold mb-3 text-gray-900 dark:text-white">
                                    Description
                                </h2>
                                <p className="text-gray-700 leading-relaxed dark:text-gray-300">
                                    {listing.description}
                                </p>
                            </div>

                            <div className="flex flex-col sm:flex-row gap-4">
                                {canBook && (
                                    <Link
                                        href={route('bookings.create', { listing_id: listing.id })}
                                        className="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-indigo-700 transition-colors"
                                    >
                                        📅 Book This Place
                                    </Link>
                                )}
                                
                                {canEdit && (
                                    <>
                                        <Link
                                            href={route('listings.edit', listing.id)}
                                            className="px-6 py-3 border border-gray-300 rounded-lg text-center font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            ✏️ Edit
                                        </Link>
                                        <Link
                                            href={route('listings.destroy', listing.id)}
                                            method="delete"
                                            as="button"
                                            className="px-6 py-3 border border-red-300 rounded-lg text-center font-medium text-red-700 hover:bg-red-50 transition-colors dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900"
                                        >
                                            🗑️ Delete
                                        </Link>
                                    </>
                                )}

                                {!auth?.user && (
                                    <div className="flex-1 bg-gray-100 text-gray-600 px-6 py-3 rounded-lg text-center font-medium dark:bg-gray-700 dark:text-gray-400">
                                        <Link href={route('login')} className="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                            Log in to book
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}