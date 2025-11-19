<script setup lang="ts">
import { BreadcrumbItem, PaginatedResponse, Patient } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import { Doctors } from '@/data/doctors';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import axios from 'axios';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

// Form
const form = useForm({
    patient_id: null,
    visit_date: '',
    departement: '',
    doctor_name: '',
    complaint: '',
});

// Search & Data
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

const submitForm = () => {
    form.post('/dashboard/kunjungan', {
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
        title: 'Kunjungan',
        href: '/dashboard/kunjungan',
    },
    {
        title: 'Edit',
        href: `/dashboard/kunjungan/create`,
    },
];
</script>

<template>
    <Head title="Dashboard | Edit Kunjungan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mt-40 min-h-screen space-y-4">
            <h1 class="text-center text-xl font-semibold">
                Edit Data Kunjungan
            </h1>
            <div class="flex justify-center">
                <div class="w-1/2 space-y-4">
                    <!-- Patient Select with Search -->
                    <InputGroup>
                        <select
                            v-model="form.patient_id"
                            class="w-full bg-transparent"
                        >
                            <option value="">Pilih Pasien</option>
                            <option value="" class="p-2">
                                <div class="p-2">
                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Cari nama pasien..."
                                        class="w-full bg-transparent focus:outline-none"
                                    />
                                </div>
                            </option>
                            <option
                                v-for="(patient, i) in patientsDatas?.data"
                                :key="i"
                                :value="patient?.id"
                            >
                                {{ patient?.name }} ({{ patient?.nik }})
                            </option>
                        </select>
                        <InputGroupAddon>Pasien</InputGroupAddon>
                    </InputGroup>

                    <!-- Visit Date -->
                    <InputGroup>
                        <InputGroupInput
                            v-model="form.visit_date"
                            type="date"
                        />
                        <InputGroupAddon>Tanggal Kunjungan</InputGroupAddon>
                    </InputGroup>

                    <!-- Departement -->
                    <InputGroup>
                        <select
                            v-model="form.departement"
                            class="w-full bg-transparent"
                        >
                            <option value="">Pilih Departemen</option>
                            <option value="Penyakit Dalam">
                                Penyakit Dalam
                            </option>
                            <option value="Kardiologi">Kardiologi</option>
                            <option value="Neurologi">Neurologi</option>
                            <option value="Orthopedi">Orthopedi</option>
                            <option value="Dermatologi">Dermatologi</option>
                            <option value="Pediatri">Pediatri</option>
                            <option value="Mata">Mata</option>
                            <option value="THT">THT</option>
                            <option value="Gigi">Gigi</option>
                            <option value="Bedah">Bedah</option>
                            <option value="Psikiatri">Psikiatri</option>
                            <option value="Umum">Umum</option>
                        </select>
                        <InputGroupAddon>Departemen</InputGroupAddon>
                    </InputGroup>

                    <!-- Doctor Name -->
                    <InputGroup>
                        <select
                            v-model="form.doctor_name"
                            class="w-full bg-transparent"
                        >
                            <option value="">Pilih Doktor</option>
                            <option
                                v-for="(doctor, i) in Doctors"
                                :key="i"
                                :value="doctor"
                            >
                                {{ doctor }}
                            </option>
                        </select>
                        <InputGroupAddon>Dokter</InputGroupAddon>
                    </InputGroup>

                    <!-- Complaint -->
                    <InputGroup>
                        <textarea
                            v-model="form.complaint"
                            placeholder="Keluhan pasien"
                            class="w-full bg-transparent focus:outline-none"
                            rows="4"
                        />
                        <InputGroupAddon>Keluhan</InputGroupAddon>
                    </InputGroup>

                    <Button
                        @click="submitForm"
                        class="w-full"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? 'Memproses...'
                                : 'Create Kunjungan'
                        }}
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
