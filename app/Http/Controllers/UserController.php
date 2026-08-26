<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeUserAdminRequest;
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
    public function index(Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $users = User::simplePaginate(5);

        return UserResource::collection($users);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        if($request->has('is_admin')){
            $validated['is_admin'] = false;
        }
        User::create($validated);

        return response()->noContent(201);

    }

    //show specific user
    public function show(User $user, Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        return UserResource::make($user);
    }
    //shows the current logged in user
    public function showSelf(Request $request)
    {
        return UserResource::make($request->user());
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
        if($request->user()->cannot('updateDelete', $user)){
            abort(403);
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
        if($request->has('is_admin') && $request->user()->can('admin')){
            $validated['is_admin'] = $request['is_admin'];
        }
        $user->update($validated);

        return response('',200);
    }
    public function makeUserAdmin(MakeUserAdminRequest $request, User $user){
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $user->update($validated);

        return response('', 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        if($request->user()->cannot('updateDelete', $user)){
            abort(403);
        }
        $user->delete();
        return response()->noContent();
    }
}
