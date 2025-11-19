import { dashboard } from '@/routes';
import { BreadcrumbItem } from '@/types';

export const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Patient',
        href: '/dashboard/pasien',
    },
];
