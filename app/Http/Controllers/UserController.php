<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::simplePaginate(5);

        return response(UserResource::collection($users), 200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        User::create($validated);

        return response(200);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response(UserResource::make($user),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (!$request->has('password')){
            return response()->json([
                'error'=> 'Password is required to update information'
            ], 400);
        }
        $validated = $request->validated();
        
        if(!Hash::check($validated['password'], $user->password)){
            return response()->json([
                'error'=> 'password mismatch'
            ],400);
        }
        if($request->has('new_password')){
            $new_passowrd = $validated['new_password'];
            unset($validated['new_password']);
            $validated['password'] = $new_passowrd;
        }
        $user->update($validated);

        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(200);
    }
}
