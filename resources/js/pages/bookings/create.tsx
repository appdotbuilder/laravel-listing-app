import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';

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
    listing: Listing;
    [key: string]: unknown;
}

interface BookingFormData {
    listing_id: number;
    start_date: string;
    end_date: string;
    [key: string]: string | number | boolean | null;
}

export default function CreateBooking({ listing }: Props) {
    const { data, setData, post, processing, errors } = useForm<BookingFormData>({
        listing_id: listing.id,
        start_date: '',
        end_date: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('bookings.store'));
    };

    const calculateTotal = () => {
        if (!data.start_date || !data.end_date) return 0;
        
        const start = new Date(data.start_date);
        const end = new Date(data.end_date);
        const days = Math.ceil((end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24)) + 1;
        
        return days > 0 ? days * listing.price_per_day : 0;
    };

    const totalAmount = calculateTotal();

    return (
        <AppShell>
            <Head title={`Book ${listing.title}`} />
            
            <div className="py-8">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* Listing Details */}
                        <div className="bg-white rounded-lg shadow-lg p-6 dark:bg-gray-800">
                            <h2 className="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                                Booking Details
                            </h2>
                            
                            <div className="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg mb-4 relative">
                                {listing.image_path ? (
                                    <img
                                        src={`/storage/${listing.image_path}`}
                                        alt={listing.title}
                                        className="w-full h-full object-cover rounded-lg"
                                    />
                                ) : (
                                    <div className="w-full h-full flex items-center justify-center text-white text-6xl rounded-lg">
                                        🏠
                                    </div>
                                )}
                            </div>

                            <h3 className="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
                                {listing.title}
                            </h3>
                            <p className="text-gray-600 mb-2 dark:text-gray-400">
                                📍 {listing.location}
                            </p>
                            <p className="text-gray-600 mb-4 dark:text-gray-400">
                                by {listing.creator.name}
                            </p>
                            <div className="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                ${listing.price_per_day} / day
                            </div>
                        </div>

                        {/* Booking Form */}
                        <div className="bg-white rounded-lg shadow-lg p-6 dark:bg-gray-800">
                            <h2 className="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                                📅 Select Your Dates
                            </h2>

                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div>
                                    <Label htmlFor="start_date">Check-in Date *</Label>
                                    <Input
                                        id="start_date"
                                        type="date"
                                        value={data.start_date}
                                        onChange={(e) => setData('start_date', e.target.value)}
                                        min={new Date().toISOString().split('T')[0]}
                                        className="mt-1"
                                        required
                                    />
                                    <InputError message={errors.start_date} className="mt-2" />
                                </div>

                                <div>
                                    <Label htmlFor="end_date">Check-out Date *</Label>
                                    <Input
                                        id="end_date"
                                        type="date"
                                        value={data.end_date}
                                        onChange={(e) => setData('end_date', e.target.value)}
                                        min={data.start_date || new Date().toISOString().split('T')[0]}
                                        className="mt-1"
                                        required
                                    />
                                    <InputError message={errors.end_date} className="mt-2" />
                                </div>

                                {totalAmount > 0 && (
                                    <div className="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                                        <div className="flex justify-between items-center">
                                            <span className="text-gray-600 dark:text-gray-300">Total Amount:</span>
                                            <span className="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                                ${totalAmount.toFixed(2)}
                                            </span>
                                        </div>
                                        <div className="text-sm text-gray-500 mt-1 dark:text-gray-400">
                                            {Math.ceil((new Date(data.end_date).getTime() - new Date(data.start_date).getTime()) / (1000 * 60 * 60 * 24)) + 1} days × ${listing.price_per_day}
                                        </div>
                                    </div>
                                )}

                                <div className="flex space-x-4">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => window.history.back()}
                                        className="flex-1"
                                    >
                                        Cancel
                                    </Button>
                                    <Button 
                                        type="submit" 
                                        disabled={processing || !data.start_date || !data.end_date}
                                        className="flex-1"
                                    >
                                        {processing ? 'Booking...' : '🎉 Book Now'}
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}