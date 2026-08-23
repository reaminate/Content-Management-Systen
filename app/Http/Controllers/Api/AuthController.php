<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthUserRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(AuthUserRequest $request){
        if($request->validated()){
            $user = User::where("email", $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                throw ValidationException::withMessages([
                    'error' => 'incorrect email or password'
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'login successful',
                'user' => UserResource::make($user),
                'access_token' => $token,
                'token_type' => 'bearer',
            ], 200);
        }
        return response()->json([
            'message'=> 'email or password is required'
        ], 422);
        
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'successfully logged out'
        ], 200);

    }

    public function loggedInUsers(){
        $users = User::whereHas('tokens', function($query){
            $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->get();

        return response()->json([
            'users' => UserResource::collection($users),
        ], 200);
    }
}
