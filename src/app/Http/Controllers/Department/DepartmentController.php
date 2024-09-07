<?php

namespace App\Http\Controllers\Department;

use App\Actions\ApiActions;
use App\Constants\ResponseCode;
use App\Http\Controllers\CustomController;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends CustomController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DepartmentRepository $department)
    {
        try {
            $this->authorize('viewAny', Department::class);
        } catch (\Exception $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        $cntTotal = 0;
        $length = ($request->has('length')) ? $request->input('length') : 10;
        $start  = ($request->has('start')) ? (($request->input('start') / $length) + 1) : 0;
        $where  = $request->all();

        $this->data["departments"] = $department->getAllDepartments($where, $start, $length, $cntTotal);

        if (count($this->data["departments"]) == 0)
            return ApiActions::generateResponse(message_key: "No results data", code: ResponseCode::OK);

        return ApiActions::generateResponse(DepartmentResource::make($this->data), total_records: $cntTotal);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request, DepartmentRepository $department)
    {
        try {
            $this->authorize('create', Department::class);
        } catch (AuthorizationException $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $obj    = $department->createDepartment($data);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "Failed to create department", code: ResponseCode::INTERNAL_ERROR);
            }
            DB::commit();
            $this->data["department"] = $obj;
            return ApiActions::generateResponse(DepartmentResource::make($this->data), message_key: "Department created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(message_key: "Failed to create department", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($departmentId, DepartmentRepository $departmentRepository)
    {
        try {
            $department = Department::findOrfail($departmentId);
            $this->authorize('view', $department);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Department not found", code: ResponseCode::NOT_FOUND);
        } catch (\Exception $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        try {
            $department = $departmentRepository->getDepartmentById($department);
            if (!$department) {
                return ApiActions::generateResponse(message_key: "Department not found", code: ResponseCode::NOT_FOUND);
            }

            DB::commit();

            $this->data["department"] = $department;
            return ApiActions::generateResponse(DepartmentResource::make($this->data), message_key: "Department found successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, $departmentId, DepartmentRepository $departmentRepository)
    {
        try {
            $department = Department::findOrFail($departmentId);
            $this->authorize('update', $department);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Department not found", code: ResponseCode::NOT_FOUND);
        } catch (\Exception $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $obj    = $departmentRepository->updateDepartment($department, $data);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }
            DB::commit();
            $this->data["department"] = $obj;
            return ApiActions::generateResponse(DepartmentResource::make($this->data), message_key: "Department updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($departmentId, DepartmentRepository $departmentRepository)
    {
        try {
            $department = Department::findOrFail($departmentId);
            $this->authorize('delete', $department);
        } catch (ModelNotFoundException $e) {
            return ApiActions::generateResponse(message_key: "Department not found", code: ResponseCode::NOT_FOUND);
        } catch (\Exception $e) {
            return ApiActions::generateResponse(message_key: "Unauthorized", code: ResponseCode::UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $obj = $departmentRepository->deleteDepartment($department);
            if (!$obj) {
                DB::rollBack();
                return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
            }
            DB::commit();
            $this->data["department"] = $obj;
            return ApiActions::generateResponse(DepartmentResource::make($this->data), message_key: "Department deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiActions::generateResponse(message_key: "An error occurred", code: ResponseCode::INTERNAL_ERROR);
        }
    }
}
