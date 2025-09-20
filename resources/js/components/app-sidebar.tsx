import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, CalendarDays, Home, LayoutGrid, Plus, Settings, Users } from 'lucide-react';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [
    {
        title: 'BookSpace',
        href: 'https://bookspace.com',
        icon: Home,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;
    const user = auth?.user;

    const getNavItems = (): NavItem[] => {
        const commonItems: NavItem[] = [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
            {
                title: 'Browse Listings',
                href: '/listings',
                icon: Home,
            },
        ];

        if (!user) return commonItems;

        if (user.role === 'SuperAdmin') {
            return [
                ...commonItems,
                {
                    title: 'Admin Users',
                    href: '/admin/users',
                    icon: Users,
                },
                {
                    title: 'Admin Listings',
                    href: '/admin/listings',
                    icon: Home,
                },
                {
                    title: 'Admin Bookings',
                    href: '/admin/bookings',
                    icon: CalendarDays,
                },
                {
                    title: 'Settings',
                    href: '/settings/profile',
                    icon: Settings,
                },
            ];
        }

        if (user.role === 'Creator') {
            return [
                ...commonItems,
                {
                    title: 'Create Listing',
                    href: '/listings/create',
                    icon: Plus,
                },
                {
                    title: 'My Listings',
                    href: '/listings',
                    icon: Home,
                },
                {
                    title: 'Settings',
                    href: '/settings/profile',
                    icon: Settings,
                },
            ];
        }

        if (user.role === 'EndUser') {
            return [
                ...commonItems,
                {
                    title: 'My Bookings',
                    href: '/bookings',
                    icon: CalendarDays,
                },
                {
                    title: 'Settings',
                    href: '/settings/profile',
                    icon: Settings,
                },
            ];
        }

        return commonItems;
    };

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={getNavItems()} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
