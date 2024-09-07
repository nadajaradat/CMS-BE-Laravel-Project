<?php

namespace App\Repositories;

use App\Contract\ReceptionistRepositoryInterface;
use App\Models\Receptionist\Receptionist;

/**
 * Class ReceptionistRepository
 * @property Receptionist $receptionist
 * @property UserRepository $userRepository
 */
class ReceptionistRepository implements ReceptionistRepositoryInterface
{
    protected $receptionist;
    protected $userRepository;

    public function __construct(Receptionist $receptionist, UserRepository $userRepository)
    {
        $this->receptionist = $receptionist;
        $this->userRepository = $userRepository;
    }

    public function getAllReceptionists($where = [], $start = 0, $length = 10, &$cntTotal)
    {
        $knownParams = ['length', 'start'];
        $where = array_filter(
            $where,
            function ($key) use ($knownParams) {
                return !in_array($key, $knownParams);
            },
            ARRAY_FILTER_USE_KEY
        );

        $query = $this->receptionist->with(["User"])
            ->where($where)
            ->whereHas('User', function ($query) {
                $query->where('is_active', 1);
            });
            
        $cntTotal = $query->count();
        $receptionists = $query->orderBy('id', 'desc');

        return $receptionists->skip($start)->take($length)->get();
    }

    public function getReceptionistById($receptionistId)
    {
        $receptionist = $this->receptionist->with("User")->find($receptionistId);
        return $receptionist;
    }

    public function createReceptionist(array $attributes)
    {
        $receptionist = $this->receptionist->create($attributes);
        return $this->getReceptionistById($receptionist->id);
    }

    public function updateReceptionist($receptionistId, array $attributes)
    {
        $receptionist = $this->receptionist->find($receptionistId);
        if ($receptionist) {
            $receptionist->update($attributes);
            return $this->getReceptionistById($receptionist->id);
        }
        return null;
    }
}
