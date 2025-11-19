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
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { MoreHorizontalIcon, Pencil, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';
// END IMPORT

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Riwayat Kujungan',
        href: '/dashboard/kunjungan',
    },
    {
        title: 'Pasien',
        href: '/dashboard/kunjungan',
    },
];

const search = ref('');
const { visits, patient } = defineProps(['visits', 'patient']);

const visitsDatas = ref(visits);

const goToPage = async (page: number) => {
    try {
        const res = await axios.get(
            `/dashboard/kunjungan/getAllDataVisit?page=${page}`,
        );
        visitsDatas.value = res.data.data;
    } catch (err) {
        console.error(err);
    }
};

const handleDelete = (page: number | undefined) => {
    router.delete(`/dashboard/kunjungan/${page}`, {
        onSuccess: () => {
            router.get(
                '/dashboard/kunjungan',
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
    <Head title="Dashboard | List Riwayat Kunjungan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex min-h-screen items-center justify-center overflow-x-auto rounded-xl p-4"
        >
            <Card class="container">
                <CardHeader class="flex items-center justify-between">
                    <div class="">
                        <CardHeader class="px-0 text-xl font-semibold"
                            >Riwayat kunjungan</CardHeader
                        >
                        <CardDescription class="pt-1"
                            >List kunjungan pasien
                            {{ patient.name }}</CardDescription
                        >
                    </div>
                    <Inpute
                        v-model="search"
                        type="text"
                        placeholder="Cari nama pasien..."
                        class="max-w-40"
                    />
                    <Link href="/dashboard/kunjungan/create">
                        <Button> Add Kunjungan </Button>
                    </Link>
                </CardHeader>
                <div class="">
                    <Table v-if="visitsDatas && visitsDatas.data.length > 0">
                        <TableCaption
                            >A list of your recent invoices.</TableCaption
                        >
                        <TableHeader>
                            <TableRow>
                                <TableHead class="max-w-10">No</TableHead>
                                <TableHead>Patient</TableHead>
                                <TableHead>Visit Date</TableHead>
                                <TableHead>Departement</TableHead>
                                <TableHead>Doctor</TableHead>
                                <TableHead>Complaint</TableHead>
                                <TableHead>Create</TableHead>
                                <TableHead>Update</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(visit, i) in visitsDatas.data"
                                :key="visit.id"
                            >
                                <TableCell class="max-w-10">{{
                                    i + 1
                                }}</TableCell>
                                <TableCell>{{ visit.patient?.name }}</TableCell>
                                <TableCell>{{
                                    formatDate(visit.visit_date)
                                }}</TableCell>
                                <TableCell>{{ visit.departement }}</TableCell>
                                <TableCell>{{ visit.doctor_name }}</TableCell>
                                <TableCell>{{ visit.complaint }}</TableCell>
                                <TableCell>{{
                                    formatDate(visit.created_at)
                                }}</TableCell>
                                <TableCell>{{
                                    formatDate(visit.updated_at)
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
                                                    :href="`/dashboard/kunjungan/${visit.id}/edit`"
                                                    class="flex items-center gap-2"
                                                >
                                                    <DropdownMenuItem>
                                                        <Pencil />
                                                        Edit
                                                    </DropdownMenuItem>
                                                </Link>
                                            </DropdownMenuGroup>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuItem
                                                    variant="destructive"
                                                    @click="
                                                        handleDelete(visit.id)
                                                    "
                                                    class="text-accent dark:text-white"
                                                >
                                                    <Trash2Icon class="mr-2" />
                                                    <span>Trash</span>
                                                </DropdownMenuItem>
                                            </DropdownMenuGroup>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                        <TableFooter class="ms-auto w-full bg-yellow-400">
                            <div
                                v-if="visitsDatas && visitsDatas.current_page"
                                class="ms-auto"
                            >
                                <Pagination
                                    :items-per-page="visitsDatas.per_page ?? 10"
                                    :total="visitsDatas.total ?? 0"
                                    :default-page="
                                        visitsDatas.current_page ?? 1
                                    "
                                    v-slot="{ page }"
                                >
                                    <PaginationContent v-slot="{ items }">
                                        <PaginationPrevious
                                            @click="
                                                goToPage(
                                                    visitsDatas.current_page -
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
                                                    visitsDatas.current_page +
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
