<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\patientsModel;
use App\Models\visitsModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Visit/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dashboard/Visit/Create/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        try {
            $validatedData = $request->validated();

            visitsModel::create($validatedData);

            return redirect()->route('kunjungan.index')->with('success', 'kunjungan berhasil ditambahkan');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode === 1062) {
                return redirect()->back()->withInput()->with('error', 'NIK sudah tedaftar!.');
            };

            Log::error("gagal membuat kunjungan: " . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = patientsModel::query()->find($id);

        if (!$patient) {
            return redirect()->route('kunjungan.index')->with('error', 'data kunjungan tidak ditemukan');
        }

        $visits = visitsModel::query()
            ->latest()
            ->where('patient_id', $patient->id)
            ->with('patient:id,name')
            ->paginate(15);


        return Inertia::render('Dashboard/Visit/Show/show', [
            'visits' => $visits,
            'patient' => $patient
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $visit = visitsModel::where('id', $id)->with('patient:id,name')->first();

        if (!$visit) {
            return redirect()->route('kunjungan.index')->with('error', 'Data kunjungan pasien tidak ditemukan!.');
        }

        return Inertia::render('Dashboard/Visit/Edit/edit', [
            'visit' => $visit,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePatientRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();

            $patient = visitsModel::where('id', $id)->first();

            if (!$patient) {
                return redirect()->route('kunjungan.index')->with('error', 'Data kunjungan tidak ditemukan!');
            }

            $patient->update($validatedData);

            return redirect()->route('kunjungan.index')->with('success', 'kunjungan berhasil diedit');
        } catch (QueryException $e) {
            Log::error("gagal edit kunjungan: " . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $patient = visitsModel::findOrFail($id);
            $patient->delete();

            return redirect()
                ->route('kunjungan.index')
                ->with('success', 'kunjungan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus');
        }
    }

    public function getAllDataVisit(Request $request)
    {
        $paginate = $request->query('per', 15);
        $search   = $request->query('patient', null);

        $data = $this->getAllVisitsPaginate($paginate, $search);

        if ($data->isEmpty()) {
            return response()->json([
                'status'  => 'empty',
                'message' => 'Tidak ada data kunjungan pasien ditemukan',
                'data'    => $data
            ]);
        }

        return  response()->json([
            'status'  => 'success',
            'message' => "Berhasil mendapatkan {$data->count()} data kunjungan pasien",
            'data'    => $data
        ]);
    }
}
