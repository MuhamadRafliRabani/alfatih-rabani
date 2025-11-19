<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Card, CardHeader } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import { Input } from '@/components/ui/input';
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCaption from '@/components/ui/table/TableCaption.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/lib/format-date';
import { dashboard } from '@/routes';
import { PaginatedResponse, Patient, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { Eye, MoreHorizontalIcon, Pencil, Trash2Icon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
// END IMPORT

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Patient',
        href: '/dashboard/pasien',
    },
];

const search = ref('');
const patientsDatas = ref<PaginatedResponse<Patient> | null>(null);

const getDataPatient = async () => {
    try {
        const res = await axios.get('/dashboard/pasien/getAllDataPatients', {
            params: { per: 15, patient: search.value },
        });
        patientsDatas.value = res.data.data;
    } catch (err) {
        console.error(err);
    }
};

watch(
    search,
    () => {
        getDataPatient();
    },
    { immediate: true },
);

const goToPage = async (page: number) => {
    try {
        const res = await axios.get(
            `/dashboard/pasien/getAllDataPatients?page=${page}`,
        );
        patientsDatas.value = res.data.data;
    } catch (err) {
        console.error(err);
    }
};

const handleDelete = (page: number | undefined) => {
    router.delete(`/dashboard/pasien/${page}`, {
        onSuccess: () => {
            router.get(
                '/dashboard/pasien',
                {},
                {
                    preserveState: false,
                    preserveScroll: true,
                },
            );
        },
    });
};
</script>

<template>
    <Head title="Dashboard | List Pasien" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex min-h-screen items-center justify-center overflow-x-auto rounded-xl p-4"
        >
            <Card class="container">
                <CardHeader class="flex items-center justify-between">
                    <div class="">
                        <CardHeader class="px-0 text-xl font-semibold"
                            >Daftar Pasien</CardHeader
                        >
                        <CardDescription class="pt-1"
                            >List Pasien Yang Terdaftar Di
                            Alfatih</CardDescription
                        >
                    </div>
                    <Input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama pasien..."
                        class="max-w-40"
                    />
                    <Link href="/dashboard/pasien/create">
                        <Button> Add Patient </Button>
                    </Link>
                </CardHeader>
                <div class="">
                    <Table
                        v-if="patientsDatas && patientsDatas.data.length > 0"
                    >
                        <TableCaption
                            >A list of your recent invoices.</TableCaption
                        >
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[30px]">No</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Nik</TableHead>
                                <TableHead>Gander</TableHead>
                                <TableHead>Birth Date</TableHead>
                                <TableHead>Phone</TableHead>
                                <TableHead>Address</TableHead>
                                <TableHead>Cretime</TableHead>
                                <TableHead>Modtime</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(patient, i) in patientsDatas?.data"
                                :key="patient.id"
                            >
                                <TableCell class="font-medium">
                                    {{ i + 1 }}
                                </TableCell>
                                <TableCell class="font-medium">
                                    {{ patient.name }}
                                </TableCell>
                                <TableCell>{{ patient.nik }}</TableCell>
                                <TableCell>
                                    {{
                                        patient.gender === 'L'
                                            ? 'Laki - Laki'
                                            : 'Perempuan'
                                    }}
                                </TableCell>
                                <TableCell>
                                    {{ patient.birth_date }}
                                </TableCell>
                                <TableCell>{{ patient.phone }}</TableCell>
                                <TableCell>{{ patient.address }}</TableCell>
                                <TableCell>{{
                                    formatDate(patient.created_at)
                                }}</TableCell>
                                <TableCell>{{
                                    formatDate(patient.updated_at)
                                }}</TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button
                                                variant="outline"
                                                size="icon"
                                                aria-label="More Options"
                                            >
                                                <MoreHorizontalIcon />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent
                                            align="end"
                                            class="w-52"
                                        >
                                            <DropdownMenuGroup>
                                                <Link
                                                    :href="`/dashboard/pasien/${patient.id}/edit`"
                                                >
                                                    <DropdownMenuItem>
                                                        <Pencil
                                                            class="mr-2 text-accent dark:text-white"
                                                        />
                                                        Edit
                                                    </DropdownMenuItem>
                                                </Link>
                                            </DropdownMenuGroup>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <Link
                                                    :href="`/dashboard/kunjungan/show/${patient.id}`"
                                                    class="flex items-center gap-2"
                                                >
                                                    <DropdownMenuItem>
                                                        <Eye />
                                                        Detail
                                                    </DropdownMenuItem>
                                                </Link>
                                            </DropdownMenuGroup>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuItem
                                                    variant="destructive"
                                                    @click="
                                                        handleDelete(patient.id)
                                                    "
                                                    class="text-accent opacity-100 dark:text-white"
                                                >
                                                    <Trash2Icon
                                                        class="mr-2 text-accent dark:text-white"
                                                    />
                                                    <span
                                                        class="text-accent dark:text-white"
                                                        >Trash</span
                                                    >
                                                </DropdownMenuItem>
                                            </DropdownMenuGroup>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                        <TableFooter class="ms-auto w-full bg-yellow-400">
                            <div
                                v-if="
                                    patientsDatas && patientsDatas.current_page
                                "
                                class="ms-auto"
                            >
                                <Pagination
                                    :items-per-page="
                                        patientsDatas.per_page ?? 10
                                    "
                                    :total="patientsDatas.total ?? 0"
                                    :default-page="
                                        patientsDatas.current_page ?? 1
                                    "
                                    v-slot="{ page }"
                                >
                                    <PaginationContent v-slot="{ items }">
                                        <PaginationPrevious
                                            @click="
                                                goToPage(
                                                    patientsDatas.current_page -
                                                        1,
                                                )
                                            "
                                        />

                                        <template
                                            v-for="(item, i) in items"
                                            :key="i"
                                        >
                                            <PaginationItem
                                                v-if="item.type === 'page'"
                                                :value="item.value"
                                                :is-active="item.value === page"
                                                @click="goToPage(item.value)"
                                            >
                                                {{ item.value }}
                                            </PaginationItem>
                                        </template>

                                        <PaginationNext
                                            @click="
                                                goToPage(
                                                    patientsDatas.current_page +
                                                        1,
                                                )
                                            "
                                        />
                                    </PaginationContent>
                                </Pagination>
                            </div>

                            <div v-else>Loading...</div>
                        </TableFooter>
                    </Table>
                    <div v-else class="p-4 text-center text-gray-500">
                        Data pasien tidak ditemukan.
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
