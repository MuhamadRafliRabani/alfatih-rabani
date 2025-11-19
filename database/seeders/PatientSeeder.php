<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            DB::table('patients')->insert([
                'name' => 'Patient ' . $i,
                'nik' => '900000000' . $i,
                'gender' => $i % 2 === 0 ? 'P' : 'L',
                'birth_date' => now()->subYears(rand(20, 40))->subDays(rand(1, 365)),
                'phone' => '081234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'address' => 'Alamat Pasien ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
