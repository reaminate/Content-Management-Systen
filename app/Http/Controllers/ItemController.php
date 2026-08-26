<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        if($request->user()->cannot('admin')){
            abort(403);
        }
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
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();

        DB::transaction(function () use ($item, $validated) {
            if (array_key_exists('order', $validated) && $validated['order'] != $item->order) {
                $menuId = $validated['menu_id'] ?? $item->menu_id;

                $sibling = Item::where('menu_id', $menuId)
                    ->where('id', '!=', $item->id)
                    ->where('order', $validated['order'])
                    ->first();

                if ($sibling) {
                    $sibling->update(['order' => $item->order]);
                }
            }

            $item->update($validated);
        });

        return response('',200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item, Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $item->delete();

        return response()->noContent();
    }
}
