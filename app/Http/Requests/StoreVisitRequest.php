<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'visit_date' => ['required', 'date', 'before_or_equal:today'],
            'departement' => ['required', 'string', 'max:50', 'in:Penyakit Dalam,Kardiologi,Neurologi,Orthopedi,Dermatologi,Pediatri,Mata,THT,Gigi,Bedah,Psikiatri,Umum'],
            'doctor_name' => ['required', 'string', 'max:100'],
            'complaint' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'ID pasien wajib diisi!.',
            'patient_id.integer' => 'ID pasien harus berupa angka!.',
            'patient_id.exists' => 'Pasien tidak ditemukan!.',

            'visit_date.required' => 'Tanggal kunjungan wajib diisi!.',
            'visit_date.date' => 'Format tanggal kunjungan tidak valid!.',
            'visit_date.before_or_equal' => 'Tanggal kunjungan tidak boleh melebihi hari ini!.',

            'departement.required' => 'Departemen wajib dipilih!.',
            'departement.max' => 'Nama departemen maksimal 50 karakter!.',
            'departement.in' => 'Departemen yang dipilih tidak valid!.',

            'doctor_name.required' => 'Nama dokter wajib diisi!.',
            'doctor_name.max' => 'Nama dokter maksimal 100 karakter!.',

            'complaint.required' => 'Keluhan wajib diisi!.',
            'complaint.max' => 'Keluhan maksimal 1000 karakter!.',
        ];
    }
}
