<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\patientsModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class patientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Patient/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dashboard/Patient/Create/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        try {
            $validatedData = $request->validated();

            patientsModel::create($validatedData);

            return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode === 1062) {
                return redirect()->back()->withInput()->with('error', 'NIK sudah tedaftar!.');
            };

            Log::error("gagal membuat pasien: " . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = patientsModel::where('id', $id)->first();

        if (!$patient) {
            return redirect()->route('pasien.index')->with('error', 'Data pasien tidak ditemukan!.');
        }

        return Inertia::render('Dashboard/Patient/Edit/edit', [
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePatientRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();

            $patient = patientsModel::where('id', $id)->first();

            if (!$patient) {
                return redirect()->route('pasien.index')->with('error', 'Data pasien tidak ditemukan!');
            }

            $patient->update($validatedData);

            return redirect()->route('pasien.index')->with('success', 'Pasien berhasil diedit');
        } catch (QueryException $e) {
            Log::error("gagal edit pasien: " . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $patient = patientsModel::findOrFail($id);
            $patient->delete();

            return redirect()
                ->route('pasien.index')
                ->with('success', 'Pasien berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus');
        }
    }

    public function getAllDataPatients(Request $request)
    {
        $paginate = $request->query('per', 15);
        $search   = $request->query('patient', null);

        $data = $this->getAllPatientsPaginate($paginate, $search);

        if ($data->isEmpty()) {
            return response()->json([
                'status'  => 'empty',
                'message' => 'Tidak ada data pasien ditemukan',
                'data'    => $data
            ]);
        }

        return  response()->json([
            'status'  => 'success',
            'message' => "Berhasil mendapatkan {$data->count()} data pasien",
            'data'    => $data
        ]);
    }
}
