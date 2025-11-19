<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'nik'  => [
                'required',
                'string',
                'max:16',
                Rule::unique('patients', 'nik')->ignore($this->route('id'))
            ],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:15', 'regex:/^0\d{9,13}$/'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama pasien wajib diisi!.',
            'nik.required' => 'NIK wajib diisi!.',
            'nik.size' => 'NIK harus terdiri dari 16!.',
            'nik.unique' => 'NIK sudah terdaftar!.',
            'birth_date.before' => 'Tanggal lahir tidak valid!.',
            'phone.regex' => 'Format nomor telepon tidak valid',
        ];
    }
}
