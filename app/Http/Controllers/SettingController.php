<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Setting;

use App\Http\Requests\UpdateSettingRequest;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(SettingResource::collection(Setting::all()), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();

        $setting->update($validated);

        return response(200);
    }
}
