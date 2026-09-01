<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Notifications\MenuCreated;
use App\Notifications\MenuDeleted;
use App\Notifications\MenuUpdated;
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
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $menu = Menu::create($validated);
        $request->user()->notify(new MenuCreated($menu));

        return response()->noContent(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu, Request $request)
    {
        if($request->has('items')){
            $menu->load('items');
        }
        return MenuResource::make($menu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $menu->update($validated);
        $changes = $menu->getChanges();
        $request->user()->notify(new MenuUpdated($menu, $changes));

        return response('',200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu, Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $menu->delete();
        $request->user()->notify(new MenuDeleted());
        return response()->noContent();
    }
    public function view(Menu $menu){
        if($menu->active_status == false){
            abort(404);
        }
        return MenuResource::make($menu);
    }
}
