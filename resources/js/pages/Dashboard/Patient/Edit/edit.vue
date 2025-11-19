<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { BreadcrumbItem, Patient } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
// END IMPORT

const { patient } = defineProps(['patient']);

const form = useForm<Patient>({
    name: patient.name,
    nik: patient.nik,
    gender: patient.gender,
    birth_date: patient.birth_date,
    phone: patient.phone,
    address: patient.address,
});

const submitForm = () => {
    form.put(`/dashboard/pasien/${patient.id}`, {
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
        onError: (errors) => {
            Object.values(errors).forEach((err: any) => toast.error(err));
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Pasien',
        href: '/dashboard/pasien',
    },
    {
        title: 'Edit',
        href: '/dashboard/pasien/edit',
    },
];
</script>

<template>
    <Head title="Dashboard | Edit Pasien" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mt-40 min-h-screen space-y-4">
            <h1 class="text-center text-xl font-semibold">Edit Pasien</h1>
            <div class="flex justify-center">
                <div class="w-1/2 space-y-4">
                    <InputGroup>
                        <InputGroupInput
                            v-model="form.name"
                            placeholder="Nama lengkap"
                        />
                        <InputGroupAddon>Nama</InputGroupAddon>
                    </InputGroup>

                    <InputGroup>
                        <InputGroupInput
                            v-model="form.nik"
                            placeholder="NIK"
                            maxlength="16"
                        />
                        <InputGroupAddon>NIK</InputGroupAddon>
                    </InputGroup>

                    <InputGroup>
                        <select
                            v-model="form.gender"
                            class="w-full bg-transparent"
                        >
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <InputGroupAddon>Gender</InputGroupAddon>
                    </InputGroup>

                    <InputGroup>
                        <InputGroupInput
                            v-model="form.birth_date"
                            type="date"
                        />
                        <InputGroupAddon>Birth</InputGroupAddon>
                    </InputGroup>

                    <InputGroup>
                        <InputGroupInput
                            v-model="form.phone"
                            placeholder="08xxxxxxxxxx"
                            @input="
                                (e: { target: { value: string } }) =>
                                    (e.target.value = e.target.value.replace(
                                        /[^0-9+]/g,
                                        '',
                                    ))
                            "
                        />
                        <InputGroupAddon>Phone</InputGroupAddon>
                    </InputGroup>

                    <InputGroup>
                        <InputGroupInput
                            v-model="form.address"
                            placeholder="Alamat lengkap"
                        />
                        <InputGroupAddon>Alamat</InputGroupAddon>
                    </InputGroup>

                    <Button @click="submitForm" class="w-full"> Submit </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
