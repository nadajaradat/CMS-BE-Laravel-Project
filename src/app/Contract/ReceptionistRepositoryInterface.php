<?php

namespace App\Contract;

use App\Models\Receptionist\Receptionist;

interface ReceptionistRepositoryInterface 
{
    public function getAllReceptionists($where = [], $start = 0, $length = 10, &$cntTotal);
    public function getReceptionistById(Receptionist $receptionist);
    public function createReceptionist(array $attributes);
    public function updateReceptionist(Receptionist $receptionist, array $attributes);
}
