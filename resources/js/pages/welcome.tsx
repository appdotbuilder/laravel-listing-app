import React from 'react';
import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

interface Props {
    listings?: Array<{
        id: number;
        title: string;
        price_per_day: number;
        location: string;
        image_path?: string;
        creator: {
            name: string;
        };
    }>;
    [key: string]: unknown;
}

export default function Welcome({ listings = [] }: Props) {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="BookSpace - Find & Book Amazing Places">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
                <header className="bg-white/80 backdrop-blur-md shadow-sm dark:bg-gray-900/80">
                    <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <span className="text-2xl">🏠</span>
                            <span className="text-xl font-bold text-gray-900 dark:text-white">BookSpace</span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium dark:text-gray-300 dark:hover:text-white"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors"
                                    >
                                        Get Started
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    {/* Hero Section */}
                    <div className="text-center mb-16">
                        <h1 className="text-5xl font-bold text-gray-900 mb-6 dark:text-white">
                            🌟 Find & Book Amazing Places
                        </h1>
                        <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto dark:text-gray-300">
                            Discover unique spaces, book instantly, and create unforgettable experiences. 
                            Whether you're planning a getaway or listing your space, we've got you covered! ✨
                        </p>
                        
                        {!auth.user && (
                            <div className="flex justify-center gap-4 mb-12">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg"
                                >
                                    🚀 Start Booking
                                </Link>
                                <Link
                                    href={route('listings.index')}
                                    className="inline-flex items-center px-8 py-3 border border-indigo-600 text-lg font-medium rounded-lg text-indigo-600 bg-white hover:bg-indigo-50 transition-colors shadow-lg dark:bg-gray-800 dark:text-indigo-400 dark:border-indigo-400 dark:hover:bg-gray-700"
                                >
                                    🔍 Browse Listings
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Features Grid */}
                    <div className="grid md:grid-cols-3 gap-8 mb-16">
                        <div className="bg-white rounded-xl shadow-lg p-8 dark:bg-gray-800">
                            <div className="text-4xl mb-4">🎯</div>
                            <h3 className="text-xl font-semibold mb-3 text-gray-900 dark:text-white">For Travelers</h3>
                            <ul className="text-gray-600 space-y-2 dark:text-gray-300">
                                <li>• Browse amazing listings</li>
                                <li>• Easy calendar booking</li>
                                <li>• Secure payments (Stripe & Razorpay)</li>
                                <li>• Instant confirmation</li>
                            </ul>
                        </div>
                        
                        <div className="bg-white rounded-xl shadow-lg p-8 dark:bg-gray-800">
                            <div className="text-4xl mb-4">💡</div>
                            <h3 className="text-xl font-semibold mb-3 text-gray-900 dark:text-white">For Creators</h3>
                            <ul className="text-gray-600 space-y-2 dark:text-gray-300">
                                <li>• List your space easily</li>
                                <li>• Upload beautiful photos</li>
                                <li>• Set your own pricing</li>
                                <li>• Manage bookings</li>
                            </ul>
                        </div>
                        
                        <div className="bg-white rounded-xl shadow-lg p-8 dark:bg-gray-800">
                            <div className="text-4xl mb-4">⚡</div>
                            <h3 className="text-xl font-semibold mb-3 text-gray-900 dark:text-white">Platform Features</h3>
                            <ul className="text-gray-600 space-y-2 dark:text-gray-300">
                                <li>• Real-time availability</li>
                                <li>• Admin dashboard</li>
                                <li>• User management</li>
                                <li>• Payment tracking</li>
                            </ul>
                        </div>
                    </div>

                    {/* Featured Listings */}
                    {listings.length > 0 && (
                        <div className="mb-16">
                            <div className="text-center mb-8">
                                <h2 className="text-3xl font-bold text-gray-900 mb-4 dark:text-white">
                                    ✨ Featured Listings
                                </h2>
                                <p className="text-gray-600 dark:text-gray-300">
                                    Discover some of our most popular spaces
                                </p>
                            </div>
                            
                            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                {listings.map((listing) => (
                                    <div key={listing.id} className="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow dark:bg-gray-800">
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
                                                    className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200 transition-colors dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800"
                                                >
                                                    View Details
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                            
                            <div className="text-center">
                                <Link
                                    href={route('listings.index')}
                                    className="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors"
                                >
                                    🔍 View All Listings
                                </Link>
                            </div>
                        </div>
                    )}

                    {/* Call to Action */}
                    <div className="bg-indigo-600 rounded-2xl p-12 text-center text-white">
                        <h2 className="text-3xl font-bold mb-4">Ready to Get Started? 🎉</h2>
                        <p className="text-xl mb-8 opacity-90">
                            Join thousands of users who trust BookSpace for their booking needs
                        </p>
                        {!auth.user && (
                            <Link
                                href={route('register')}
                                className="inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-lg text-indigo-600 bg-white hover:bg-gray-50 transition-colors shadow-lg"
                            >
                                🚀 Create Your Account
                            </Link>
                        )}
                    </div>
                </main>

                <footer className="bg-white border-t dark:bg-gray-900 dark:border-gray-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-600 dark:text-gray-400">
                        <p>Built with ❤️ using Laravel & React</p>
                    </div>
                </footer>
            </div>
        </>
    );
}