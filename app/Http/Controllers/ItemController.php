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
        return (ItemResource::collection(Item::cursorPaginate(5)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validate = $request->validated();
        Item::create($validate);
        return response(200);
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
        return response(ItemResource::make($item), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validate = $request->validated();
        if($request->has('label')){
            $item->label = $validate['label'];
        }if($request->has('order')){
            $item->order = $validate['order'];
        }
        if($request->has('menu_id')){
            $item->menu_id = $validate['menu_id'];
        }
        if($request->has('page_id')){
            $item->page_id = $validate['page_id'];
        }
        $item->save();

        return response(200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response(200);
    }
}
