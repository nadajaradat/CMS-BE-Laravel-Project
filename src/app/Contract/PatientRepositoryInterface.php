<?php

namespace App\Contract;

use App\Models;
use App\Models\Patient;

interface PatientRepositoryInterface
{
    public function getAllPatients(array $where, int $start, int $length, int &$cntTotal);
    public function createPatient(array $data);
    public function getPatientById(Patient $patient);
    public function updatePatient(Patient $patient, array $data);
}
