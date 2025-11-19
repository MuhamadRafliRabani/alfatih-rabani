import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Patient {
    id?: number | undefined;
    name: string | undefined;
    nik: string | undefined;
    gender: 'L' | 'P';
    birth_date: string | undefined;
    phone: string | undefined;
    address: string | undefined;
    created_at?: string | undefined;
    updated_at?: string | undefined;
}

interface Visit {
    id: number | undefined;
    patient_id: number | undefined;
    visit_date: string | undefined;
    departement: string | undefined;
    doctor_name: string | undefined;
    complaint: string | undefined;
    created_at: string | undefined;
    updated_at: string | undefined;
    patient?: Patient;
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from?: number;
    to?: number;
}

export interface PatientForm extends Patient {
    errors?: Record<string, string>;
}

export type BreadcrumbItemType = BreadcrumbItem;
