<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ItemResource::collection(Item::cursorPaginate(5));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validate = $request->validated();
        Item::create($validate);
        return response()->noContent(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item, Request $request)
    {
        if($request->has('menu')){
            $item->load('menu');
        }
        if($request->has('page')){
            $item->load('page');
        } 
        return ItemResource::make($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();
        
        $item->update($validated);

        return response('',200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->noContent();
    }
}
