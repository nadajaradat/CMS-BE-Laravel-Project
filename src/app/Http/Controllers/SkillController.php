<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\skill;
use App\Models\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return response()->json($user->skills);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSkillRequest $request, User $user)
    {

        $skill = $user->skills()->create($request->validated());

        return response()->json(['message' => 'Skill Assigned Succefully', $skill], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        return response()->json($skill);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill)
    {

        $skill->update($request->validated());

        return response()->json(['message' => 'Skill Updated Succefully', $skill]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();

        return response()->json(['message' => 'Skill Deleted Successfully'], 200);
    }
}
