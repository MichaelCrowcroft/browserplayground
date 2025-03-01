import type { LucideIcon } from 'lucide-vue-next';
import type { PageProps } from '@inertiajs/core';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
}

export interface Listing {
    id: number;
    name: string;
    builder: string;
    link: string;
    image: string;
    comment?: Array<Comment>;
    user?: User;
    description: string;
    routes: { show: string; };
    [key: string]: unknown;
}

export interface Comment {
    id: number;
    body: string;
    created_at: string;
    updated_at: string;
    user?: User;
    listing?: Listing;
    [key: string]: unknown;
}