<?php

namespace App\Http\Controllers;

use App\Actions\ApiActions;
use App\Constants\ResponseCode;
use App\Http\Controllers\CustomController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class UserController extends CustomController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserRepository $user)
    {
        try {
            $this->authorize('viewAny', User::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }
        $cntTotal = 0;
        $length = ($request->has('length')) ? $request->input('length') : 10;
        $start  = ($request->has('start')) ? (($request->input('start') / $length) + 1) : 0;
        $where  = $request->all();

        $this->data["users"] = $user->getAllUsers($where, $start, $length, $cntTotal);

        if (count($this->data["users"]) == 0)
            return ApiActions::generateResponse(message_key: "No results data", code: ResponseCode::OK);

        return ApiActions::generateResponse(UserResource::make($this->data), total_records: $cntTotal);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, UserRepository $user)
    {
        try {
            $this->authorize('create', User::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $obj    = $user->createUser($data);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }
            event(new Registered($user));
            $created = $obj->isCreated();
            if (!$created) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error when create user", code: ResponseCode::INTERNAL_ERROR);
            }
            $this->data["user"] = $obj;
            DB::commit();
            return ApiActions::generateResponse(UserResource::make($this->data), message_key: "Added successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, UserRepository $userRepo)
    {
        try {
            $this->authorize('view', $user);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        try {
            $user = $userRepo->getUserById($user);

            if (!$user) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "User not found", code: ResponseCode::NOT_FOUND);
            }

            DB::commit();
            $this->data['user'] = $user;
            return ApiActions::generateResponse(UserResource::make($this->data), message_key: "User Retrieved Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UserRepository $userRepo)
    {
        try {
            $this->authorize('update', $user);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $obj    = $userRepo->updateUser($user, $data);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }
            $updated = $obj->isCreated();
            if (!$updated) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error when edit folder", code: ResponseCode::INTERNAL_ERROR);
            }
            DB::commit();
            $this->data["user"] = $userRepo->getUserById($user);
            return ApiActions::generateResponse(UserResource::make($this->data), message_key: "Updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserRepository $userRepo)
    {
        try {
            $this->authorize('delete', $user);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $obj = $userRepo->deleteUser($user);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }

            DB::commit();
            return ApiActions::generateResponse(message_key: "Deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(['e' => $e->getMessage()], message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }
}
