<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use stdClass;

class AuthController extends Controller
{


    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('user_name', 'password'))) {
            return response()->json([
                'user' => null,
                'message' => 'Invalid login details',
                'status' => 'failed'
            ], 200);
        }

        $user = User::where('user_name', $request['user_name'])->firstOrFail();
        $user_loggedin = new stdClass();
        $user_loggedin->id = $user->id;
        $user_loggedin->user_name = $user->user_name;
        $user_loggedin->status = 'loggedin';

        if ($user->is_active) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $user_loggedin->token = $token;
            $user_loggedin->token_type = 'Bearer';
        }

        return response()->json($user_loggedin, 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
            'status' => 'logged out'
        ], 200);
    }
}
