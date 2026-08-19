<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return(MenuResource::collection(Menu::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $validated = $request->validated();
        Menu::create($validated);

        return response(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu, Request $request)
    {
        if($request->has('items')){
            $menu->load('items');
        }
        return response(MenuResource::make($menu), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validate = $request->validated();
        if($request->has('name')){
            $menu->name = $validate['name'];
        }
        if($request->has('description')){
            $menu->name = $validate['description'];
        }
        if($request->has('active_status')){
            $menu->name = $validate['active_status'];
        }
        $menu->save();

        return response(200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response(200);
    }
}
