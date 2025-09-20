import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Props {
    stats: {
        total_users?: number;
        total_listings?: number;
        total_bookings?: number;
        my_listings?: number;
        my_bookings?: number;
        upcoming_bookings?: number;
    };
    [key: string]: unknown;
}

export default function Dashboard({ stats }: Props) {
    const { auth } = usePage<{ auth: { user: { id: number; name: string; role: string } } }>().props;
    const user = auth.user;

    const renderSuperAdminDashboard = () => (
        <>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">👥</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.total_users}</p>
                        </div>
                    </div>
                </div>

                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">🏠</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Listings</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.total_listings}</p>
                        </div>
                    </div>
                </div>

                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">📅</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bookings</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.total_bookings}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                    href={route('admin.show', 'users')}
                    className="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:bg-blue-100 transition-colors dark:bg-blue-900 dark:border-blue-800 dark:hover:bg-blue-800"
                >
                    <div className="text-2xl mb-2">👥</div>
                    <h3 className="text-lg font-semibold text-blue-900 dark:text-blue-100">Manage Users</h3>
                    <p className="text-blue-700 text-sm dark:text-blue-300">View and manage all users</p>
                </Link>

                <Link
                    href={route('admin.show', 'listings')}
                    className="bg-green-50 border border-green-200 rounded-lg p-6 hover:bg-green-100 transition-colors dark:bg-green-900 dark:border-green-800 dark:hover:bg-green-800"
                >
                    <div className="text-2xl mb-2">🏠</div>
                    <h3 className="text-lg font-semibold text-green-900 dark:text-green-100">Manage Listings</h3>
                    <p className="text-green-700 text-sm dark:text-green-300">View and manage all listings</p>
                </Link>

                <Link
                    href={route('admin.show', 'bookings')}
                    className="bg-purple-50 border border-purple-200 rounded-lg p-6 hover:bg-purple-100 transition-colors dark:bg-purple-900 dark:border-purple-800 dark:hover:bg-purple-800"
                >
                    <div className="text-2xl mb-2">📅</div>
                    <h3 className="text-lg font-semibold text-purple-900 dark:text-purple-100">Manage Bookings</h3>
                    <p className="text-purple-700 text-sm dark:text-purple-300">View and manage all bookings</p>
                </Link>

                <Link
                    href={route('listings.index')}
                    className="bg-orange-50 border border-orange-200 rounded-lg p-6 hover:bg-orange-100 transition-colors dark:bg-orange-900 dark:border-orange-800 dark:hover:bg-orange-800"
                >
                    <div className="text-2xl mb-2">🔍</div>
                    <h3 className="text-lg font-semibold text-orange-900 dark:text-orange-100">Browse Platform</h3>
                    <p className="text-orange-700 text-sm dark:text-orange-300">View the platform as users see it</p>
                </Link>
            </div>
        </>
    );

    const renderCreatorDashboard = () => (
        <>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">🏠</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">My Listings</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.my_listings}</p>
                        </div>
                    </div>
                </div>

                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">📅</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bookings</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.total_bookings}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    href={route('listings.create')}
                    className="bg-green-50 border border-green-200 rounded-lg p-6 hover:bg-green-100 transition-colors dark:bg-green-900 dark:border-green-800 dark:hover:bg-green-800"
                >
                    <div className="text-2xl mb-2">✨</div>
                    <h3 className="text-lg font-semibold text-green-900 dark:text-green-100">Create Listing</h3>
                    <p className="text-green-700 text-sm dark:text-green-300">Add a new property to rent</p>
                </Link>

                <Link
                    href={route('listings.index')}
                    className="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:bg-blue-100 transition-colors dark:bg-blue-900 dark:border-blue-800 dark:hover:bg-blue-800"
                >
                    <div className="text-2xl mb-2">🔍</div>
                    <h3 className="text-lg font-semibold text-blue-900 dark:text-blue-100">View My Listings</h3>
                    <p className="text-blue-700 text-sm dark:text-blue-300">Manage your properties</p>
                </Link>

                <Link
                    href={route('listings.index')}
                    className="bg-purple-50 border border-purple-200 rounded-lg p-6 hover:bg-purple-100 transition-colors dark:bg-purple-900 dark:border-purple-800 dark:hover:bg-purple-800"
                >
                    <div className="text-2xl mb-2">📊</div>
                    <h3 className="text-lg font-semibold text-purple-900 dark:text-purple-100">Analytics</h3>
                    <p className="text-purple-700 text-sm dark:text-purple-300">View booking statistics</p>
                </Link>
            </div>
        </>
    );

    const renderEndUserDashboard = () => (
        <>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">📅</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">My Bookings</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.my_bookings}</p>
                        </div>
                    </div>
                </div>

                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <div className="flex items-center">
                        <div className="text-3xl mr-4">⏰</div>
                        <div>
                            <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Upcoming Trips</p>
                            <p className="text-2xl font-semibold text-gray-900 dark:text-white">{stats.upcoming_bookings}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    href={route('listings.index')}
                    className="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:bg-blue-100 transition-colors dark:bg-blue-900 dark:border-blue-800 dark:hover:bg-blue-800"
                >
                    <div className="text-2xl mb-2">🔍</div>
                    <h3 className="text-lg font-semibold text-blue-900 dark:text-blue-100">Browse Listings</h3>
                    <p className="text-blue-700 text-sm dark:text-blue-300">Find your next adventure</p>
                </Link>

                <Link
                    href={route('bookings.index')}
                    className="bg-green-50 border border-green-200 rounded-lg p-6 hover:bg-green-100 transition-colors dark:bg-green-900 dark:border-green-800 dark:hover:bg-green-800"
                >
                    <div className="text-2xl mb-2">📅</div>
                    <h3 className="text-lg font-semibold text-green-900 dark:text-green-100">My Bookings</h3>
                    <p className="text-green-700 text-sm dark:text-green-300">View your trips</p>
                </Link>

                <Link
                    href={route('home')}
                    className="bg-purple-50 border border-purple-200 rounded-lg p-6 hover:bg-purple-100 transition-colors dark:bg-purple-900 dark:border-purple-800 dark:hover:bg-purple-800"
                >
                    <div className="text-2xl mb-2">🏠</div>
                    <h3 className="text-lg font-semibold text-purple-900 dark:text-purple-100">Explore</h3>
                    <p className="text-purple-700 text-sm dark:text-purple-300">Discover featured places</p>
                </Link>
            </div>
        </>
    );

    return (
        <AppShell>
            <Head title="Dashboard" />
            
            <div className="py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                            {user.role === 'SuperAdmin' && '⚡ Admin Dashboard'}
                            {user.role === 'Creator' && '💡 Creator Dashboard'}
                            {user.role === 'EndUser' && '🎯 My Dashboard'}
                        </h1>
                        <p className="mt-2 text-gray-600 dark:text-gray-400">
                            Welcome back, {user.name}! Here's what's happening.
                        </p>
                    </div>

                    {user.role === 'SuperAdmin' && renderSuperAdminDashboard()}
                    {user.role === 'Creator' && renderCreatorDashboard()}
                    {user.role === 'EndUser' && renderEndUserDashboard()}
                </div>
            </div>
        </AppShell>
    );
}