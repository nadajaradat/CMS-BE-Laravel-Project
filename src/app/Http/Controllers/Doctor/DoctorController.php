<?php

namespace App\Http\Controllers\Doctor;

use App\Actions\ApiActions;
use App\Constants\ResponseCode;
use App\Http\Controllers\CustomController;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor\Doctor;
use App\Repositories\DoctorRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DoctorController extends CustomController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DoctorRepository $doctorRepo)
    {
        try {
            $this->authorize('viewAny', Doctor::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }
        $cntTotal = 0;
        $length = ($request->has('length')) ? $request->input('length') : 10;
        $start  = ($request->has('start')) ? (($request->input('start') / $length) + 1) : 0;
        $where  = $request->all();

        $this->data["doctors"] = $doctorRepo->getAllDoctors($where, $start, $length, $cntTotal);

        if (count($this->data["doctors"]) == 0)
            return ApiActions::generateResponse(message_key: "No results data", code: ResponseCode::OK);

        return ApiActions::generateResponse(DoctorResource::make($this->data), total_records: $cntTotal);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request, DoctorRepository $doctorRepo, UserRepository $userRepo)
    {
        try {
            $this->authorize('create', Doctor::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $userData = $request->only(['name', 'user_name', 'contact_information', 'password']);
            $doctorData = $request->only(['department_id', 'description', 'income_percentage']);

            $user = $userRepo->createUser($userData);
            if (!$user) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while creating user", code: ResponseCode::INTERNAL_ERROR);
            }

            $roleAssigned = $userRepo->assignRole($user, 'doctor');
            if (!$roleAssigned) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while assigning role", code: ResponseCode::INTERNAL_ERROR);
            }

            $doctorData['user_id'] = $user->id;

            // Create the doctor
            $obj = $doctorRepo->createDoctor($doctorData);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while creating doctor", code: ResponseCode::INTERNAL_ERROR);
            }

            event(new Registered($user));
            $created = $user->isCreated();
            if (!$created) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while creating doctor", code: ResponseCode::INTERNAL_ERROR);
            }

            $this->data["doctor"] = $obj;
            DB::commit();
            return ApiActions::generateResponse(DoctorResource::make($this->data), message_key: "Added successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($doctorId, DoctorRepository $doctorRepo)
    {
        try {
            $doctor = Doctor::findOrFail($doctorId);
            $this->authorize('view', $doctor);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Doctor not found", code: ResponseCode::NOT_FOUND);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        try {
            $doctor = $doctorRepo->getDoctorById($doctor);

            if (!$doctor) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "Doctor not found", code: ResponseCode::NOT_FOUND);
            }

            DB::commit();
            $this->data['doctor'] = $doctor;
            return ApiActions::generateResponse(DoctorResource::make($this->data), message_key: "Doctor Retrieved Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateDoctorRequest $request, $doctorId, DoctorRepository $doctorRepo, UserRepository $userRepo)
    {
        try {
            $doctor = Doctor::findOrFail($doctorId);
            $this->authorize('update', $doctor);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Doctor not found", code: ResponseCode::NOT_FOUND);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $userData = $request->only(['name', 'user_name', 'contact_information', 'password']);

            if (!empty($userData)) {
                $user = $doctor->User;
                $userUpdated = $userRepo->updateUser($user, $userData);
                if (!$userUpdated) {
                    DB::rollBack();
                    return ApiActions::generateResponse(message_key: "An error occurred while updating user", code: ResponseCode::INTERNAL_ERROR);
                }
            }

            $doctorData = $request->only(['department_id', 'description', 'income_percentage']);

            if (!empty($doctorData)) {
                $updatedDoctor = $doctorRepo->updateDoctor($doctor, $doctorData);
                if (!$updatedDoctor) {
                    DB::rollBack();
                    return ApiActions::generateResponse(message_key: "An error occurred while updating doctor", code: ResponseCode::INTERNAL_ERROR);
                }
            }

            DB::commit();
            $this->data["doctor"] = $updatedDoctor;
            return ApiActions::generateResponse(DoctorResource::make($this->data), message_key: "Updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }
}
