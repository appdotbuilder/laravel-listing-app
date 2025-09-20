import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/input-error';

interface ListingFormData {
    title: string;
    description: string;
    price_per_day: string;
    location: string;
    image: File | null;
    is_available: boolean;
    [key: string]: string | number | boolean | File | null;
}

export default function CreateListing() {
    const { data, setData, post, processing, errors } = useForm<ListingFormData>({
        title: '',
        description: '',
        price_per_day: '',
        location: '',
        image: null,
        is_available: true,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('listings.store'));
    };

    return (
        <AppShell>
            <Head title="Create New Listing" />
            
            <div className="py-8">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                            ✨ Create New Listing
                        </h1>
                        <p className="mt-2 text-gray-600 dark:text-gray-400">
                            List your space and start earning money
                        </p>
                    </div>

                    <div className="bg-white shadow rounded-lg dark:bg-gray-800">
                        <form onSubmit={handleSubmit} className="p-6 space-y-6">
                            <div>
                                <Label htmlFor="title">Listing Title *</Label>
                                <Input
                                    id="title"
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    placeholder="Amazing downtown apartment"
                                    className="mt-1"
                                />
                                <InputError message={errors.title} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="description">Description *</Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Describe your space, amenities, and what makes it special..."
                                    rows={4}
                                    className="mt-1"
                                />
                                <InputError message={errors.description} className="mt-2" />
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="price_per_day">Price per Day ($) *</Label>
                                    <Input
                                        id="price_per_day"
                                        type="number"
                                        step="0.01"
                                        min="1"
                                        value={data.price_per_day}
                                        onChange={(e) => setData('price_per_day', e.target.value)}
                                        placeholder="150.00"
                                        className="mt-1"
                                    />
                                    <InputError message={errors.price_per_day} className="mt-2" />
                                </div>

                                <div>
                                    <Label htmlFor="location">Location *</Label>
                                    <Input
                                        id="location"
                                        type="text"
                                        value={data.location}
                                        onChange={(e) => setData('location', e.target.value)}
                                        placeholder="San Francisco, CA"
                                        className="mt-1"
                                    />
                                    <InputError message={errors.location} className="mt-2" />
                                </div>
                            </div>

                            <div>
                                <Label htmlFor="image">Property Image</Label>
                                <Input
                                    id="image"
                                    type="file"
                                    accept="image/*"
                                    onChange={(e) => setData('image', e.target.files?.[0] || null)}
                                    className="mt-1"
                                />
                                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Upload a beautiful photo of your space (max 2MB)
                                </p>
                                <InputError message={errors.image} className="mt-2" />
                            </div>

                            <div className="flex items-center">
                                <input
                                    id="is_available"
                                    type="checkbox"
                                    checked={data.is_available}
                                    onChange={(e) => setData('is_available', e.target.checked)}
                                    className="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                />
                                <Label htmlFor="is_available" className="ml-2">
                                    Available for booking
                                </Label>
                            </div>

                            <div className="flex justify-end space-x-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => window.history.back()}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Creating...' : 'Create Listing'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}