<?php

namespace App\Http\Controllers\Receptionist;

use App\Actions\ApiActions;
use App\Constants\ResponseCode;
use App\Http\Controllers\CustomController;
use App\Http\Requests\Receptionist\StoreReceptionistRequest;
use App\Http\Requests\Receptionist\UpdateReceptionistRequest;
use App\Http\Resources\ReceptionistResource;
use App\Models\Receptionist\Receptionist;
use App\Repositories\ReceptionistRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ReceptionistController extends CustomController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ReceptionistRepository $receptionistRepo)
    {
        try {
            $this->authorize('viewAny', Receptionist::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }
        $cntTotal = 0;
        $length = ($request->has('length')) ? $request->input('length') : 10;
        $start  = ($request->has('start')) ? (($request->input('start') / $length) + 1) : 0;
        $where  = $request->all();

        $this->data["receptionists"] = $receptionistRepo->getAllReceptionists($where, $start, $length, $cntTotal);

        if (count($this->data["receptionists"]) == 0)
            return ApiActions::generateResponse(message_key: "No results data", code: ResponseCode::OK);

        return ApiActions::generateResponse(ReceptionistResource::make($this->data), total_records: $cntTotal);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceptionistRequest $request, ReceptionistRepository $receptionistRepo, UserRepository $userRepo)
    {
        try {
            $this->authorize('create', Receptionist::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $userData = $request->only(['name', 'user_name', 'contact_information', 'password']);
            $receptionistData = $request->only(['']);

            $user = $userRepo->createUser($userData);
            if (!$user) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while creating user", code: ResponseCode::INTERNAL_ERROR);
            }

            $roleAssigned = $userRepo->assignRole($user, 'receptionist');
            if (!$roleAssigned) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while assigning role", code: ResponseCode::INTERNAL_ERROR);
            }

            $receptionistData['user_id'] = $user->id;

            // Create the receptionist
            $obj = $receptionistRepo->createReceptionist($receptionistData);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while creating receptionist", code: ResponseCode::INTERNAL_ERROR);
            }

            event(new Registered($user));
            $created = $user->isCreated();
            if (!$created) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred while creating receptionist", code: ResponseCode::INTERNAL_ERROR);
            }

            $this->data["receptionist"] = $obj;
            DB::commit();
            return ApiActions::generateResponse(ReceptionistResource::make($this->data), message_key: "Added successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($receptionistId, ReceptionistRepository $receptionistRepo)
    {
        try {
            $receptionist = Receptionist::findOrFail($receptionistId);
            $this->authorize('view', $receptionist);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Receptionist not found", code: ResponseCode::NOT_FOUND);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        try {
            $receptionist = $receptionistRepo->getReceptionistById($receptionistId);

            if (!$receptionist) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "Receptionist not found", code: ResponseCode::NOT_FOUND);
            }

            DB::commit();
            $this->data['receptionist'] = $receptionist;
            return ApiActions::generateResponse(ReceptionistResource::make($this->data), message_key: "Receptionist Retrieved Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceptionistRequest $request, $receptionistId, ReceptionistRepository $receptionistRepo, UserRepository $userRepo)
    {
        try {
            $receptionist = Receptionist::findOrFail($receptionistId);
            $this->authorize('update', $receptionist);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Receptionist not found", code: ResponseCode::NOT_FOUND);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $userData = $request->only(['name', 'user_name', 'contact_information', 'password']);

            if (!empty($userData)) {
                $user = $receptionist->User;
                $userUpdated = $userRepo->updateUser($user, $userData);
                if (!$userUpdated) {
                    DB::rollBack();
                    return ApiActions::generateResponse(message_key: "An error occurred while updating user", code: ResponseCode::INTERNAL_ERROR);
                }
            }

            // $receptionistData = $request->only(['']);

            // if (!empty($receptionistData)) {
            //     $updatedReceptionist = $receptionistRepo->updateReceptionist($receptionistId, $receptionistData);
            //     if (!$updatedReceptionist) {
            //         DB::rollBack();
            //         return ApiActions::generateResponse(message_key: "An error occurred while updating receptionist", code: ResponseCode::INTERNAL_ERROR);
            //     }
            // }
            $updatedReceptionist = $receptionistRepo->getReceptionistById($receptionistId);

            DB::commit();
            $this->data["receptionist"] = $updatedReceptionist;
            return ApiActions::generateResponse(ReceptionistResource::make($this->data), message_key: "Updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }
}