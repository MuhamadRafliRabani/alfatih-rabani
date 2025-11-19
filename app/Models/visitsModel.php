<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class visitsModel extends Model
{
    use HasFactory;
    protected $table = 'visits';
    public $guarded = [];

    public function patient()
    {
        return $this->belongsTo(patientsModel::class, 'patient_id');
    }
}
