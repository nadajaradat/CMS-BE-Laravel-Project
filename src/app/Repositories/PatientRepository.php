<?php

namespace App\Repositories;

use App\Contract\PatientRepositoryInterface;
use App\Models\Patient\Patient;

/**
 * Class PatientRepository
 * @property Patient $patient
 */

class PatientRepository implements PatientRepositoryInterface
{
    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    public function getAllPatients($where = [], $start = 0, $length = 10, &$cntTotal)
    {
        $knownParams = ['length', 'start'];
        $where = array_filter(
            $where,
            function ($key) use ($knownParams) {
                return !in_array($key, $knownParams);
            },
            ARRAY_FILTER_USE_KEY
        );

        $query = $this->patient->where($where);
        $cntTotal = $query->count();
        $patients = $query->orderBy('id', 'desc');

        return $patients->skip($start)->take($length)->get();
    }

    public function getPatientById(Patient $patient)
    {
        return $patient;
    }


    public function createPatient(array $attributes)
    {
        $patient = $this->patient->create($attributes);
        return $this->getPatientById($patient);
    }

    public function updatePatient(Patient $patient, array $attributes)
    {
        $patient->update($attributes);
        return $patient;
    }
}
