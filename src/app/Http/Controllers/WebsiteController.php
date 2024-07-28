<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebsiteRequest;
use App\Http\Requests\UpdateWebsiteRequest;
use App\Models\Website;
use App\Models\User;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return response()->json([
            'message' => 'Websites Retrieved Successfully',
            'websites' => $user->websites
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request, User $user)
    {
        $website = $user->websites()->create($request->validated());

        return response()->json([
            'message' => 'Website Assigned Successfully',
            'website' => $website
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        return response()->json([
            'message' => 'Website Retrieved Successfully',
            'website' => $website
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        $website->update($request->validated());

        return response()->json([
            'message' => 'Website Updated Successfully',
            'website' => $website
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        $website->delete();

        return response()->json(['message' => 'Website Deleted Successfully'], 200);
    }
}
