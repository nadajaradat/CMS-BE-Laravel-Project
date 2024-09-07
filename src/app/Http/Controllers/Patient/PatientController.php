<?php

namespace App\Http\Controllers\Patient;

use App\Actions\ApiActions;
use App\Constants\ResponseCode;
use App\Http\Controllers\CustomController;
use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\UpdatePatientProfileRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient\Patient;
use App\Repositories\PatientRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PatientController extends CustomController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PatientRepository $patientRepo)
    {
        try {
            $this->authorize('viewAny', Patient::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }
        $cntTotal = 0;
        $length = ($request->has('length')) ? $request->input('length') : 10;
        $start  = ($request->has('start')) ? (($request->input('start') / $length) + 1) : 0;
        $where  = $request->all();

        $this->data["patients"] = $patientRepo->getAllPatients($where, $start, $length, $cntTotal);

        if (count($this->data["patients"]) == 0)
            return ApiActions::generateResponse(message_key: "No results data", code: ResponseCode::OK);

        return ApiActions::generateResponse(PatientResource::make($this->data), total_records: $cntTotal);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request, PatientRepository $patientRepo)
    {
        try {
            $this->authorize('create', Patient::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $obj    = $patientRepo->createPatient($data);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }
            event(new Registered($obj));
            $created = $obj->isCreated();
            if (!$created) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error when create patient", code: ResponseCode::INTERNAL_ERROR);
            }
            $this->data["patient"] = $obj;
            DB::commit();
            return ApiActions::generateResponse(PatientResource::make($this->data), message_key: "Added successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($patientId, PatientRepository $patientRepo)
    {
        try {
            $patient = Patient::findOrfail($patientId);
            $this->authorize('view', $patient);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Patient not found", code: ResponseCode::NOT_FOUND);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        try {
            $patient = $patientRepo->getPatientById($patient);

            if (!$patient) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "Patient not found", code: ResponseCode::NOT_FOUND);
            }

            DB::commit();
            $this->data['patient'] = $patient;
            return ApiActions::generateResponse(PatientResource::make($this->data), message_key: "Patient Retrieved Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, $patientId, PatientRepository $patientRepo)
    {
        try {
            $patient = Patient::findOrFail($patientId);
            $this->authorize('update', $patient);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Patient not found", code: ResponseCode::NOT_FOUND);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $obj    = $patientRepo->updatePatient($patient, $data);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }
            $updated = $obj->isCreated();
            if (!$updated) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error when edit patient", code: ResponseCode::INTERNAL_ERROR);
            }
            DB::commit();
            $this->data["patient"] = $patientRepo->getPatientById($patient);
            return ApiActions::generateResponse(PatientResource::make($this->data), message_key: "Updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }
}
