<?php

namespace App\Http\Controllers;

use App\Models\patientsModel;
use App\Models\visitsModel;

abstract class Controller
{

    public function getAllPatientsPaginate(int $paginate = 15, ?string $where = null)
    {
        $query = patientsModel::query()->latest();

        if ($where) {
            $query->where('name', 'like', "%$where%");
        }

        return $query->paginate($paginate);
    }

    public function getAllVisitsPaginate(int $paginate = 15, ?string $where = null)
    {
        $query = visitsModel::query()->latest();

        if ($where) {
            $query->whereHas('patient', function ($q) use ($where) {
                $q->where('name', 'like', "%$where%");
            });
        }

        return $query->with('patient:id,name')->paginate($paginate);
    }
}
