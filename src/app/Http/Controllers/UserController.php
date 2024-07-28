<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\user;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('is_active', '=', true)
            ->with('roles.permissions')
            ->get();
        return response()->json([
            'message' => 'Users Retrieved Successfully',
            'users' => $users
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = user::create($request->validated());

        event(new Registered($user));
        $created_user = User::where('user_name', '=', $request->user_name)->first();

        return response()->json([
            'message' => 'User created successfully',
            'user' => $created_user,
            'status' => 'registered',
            'verified' => false
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(user $user)
    {
        $this->authorize('view', $user);
        $user->load('roles.permissions');

        return response()->json([
            'message' => 'User Retrieved Successfully',
            'user' => $user
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, user $user)
    {
        $this->authorize('update', $user);
        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
            'status' => 'updated'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        $this->authorize('delete', $user);
        $user->is_active = false;
        $user->save();

        return response()->json([
            'message' => 'User deactivated successfully',
            'status' => 'deactivated'
        ], 200);
    }
}
