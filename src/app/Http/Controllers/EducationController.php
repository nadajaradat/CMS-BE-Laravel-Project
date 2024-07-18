<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\User;
use App\Http\Requests\StoreEducationRequest;
use App\Http\Requests\UpdateEducationRequest;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    // Display a listing of the education records for a specific user.
    public function index(User $user)
    {
        return response()->json($user->educations);
    }

    // Store a newly created education record in storage.
    public function store(StoreEducationRequest $request, User $user)
    {
        $education = new Education($request->validated());
        $user->educations()->save($education);

        return response()->json(['message' => 'Education Assigned Succesfully', $education], 201);
    }

    // Display the specified education record.
    public function show(Education $education)
    {
        return response()->json($education);
    }

    // Update the specified education record in storage.
    public function update(UpdateEducationRequest $request, Education $education)
    {
        $education->update($request->validated());

        return response()->json(['message' => 'Education Assigned Succesfully', $education]);
    }

    // Remove the specified education record from storage.
    public function destroy(Education $education)
    {
        $education->delete();

        return response()->json(['message' => 'Education Deleted Successfully'], 200);
    }
}
