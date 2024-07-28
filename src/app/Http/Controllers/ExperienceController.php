<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return response()->json([
            'message' => 'Experiences Retrieved Successfully',
            'experiences' => $user->experiences
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExperienceRequest $request, User $user)
    {
        $experience = $user->experiences()->create($request->validated());

        return response()->json([
            'message' => 'Experience Assigned Successfully',
            'experience' => $experience
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience)
    {
        return response()->json([
            'message' => 'Experience Retrieved Successfully',
            'experience' => $experience
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExperienceRequest $request, Experience $experience)
    {
        $experience->update($request->validated());

        return response()->json([
            'message' => 'Experience Updated Successfully',
            'experience' => $experience
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        $experience->delete();

        return response()->json(['message' => 'Experience Deleted Successfully'], 200);
    }
}