<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::cursorPaginate(15);

        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();
        Tag::create($validated);

        return response()->noContent(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag, Request $request)
    {
        if($request->has('blogs')) {
            $tag->load('blogs');
        }
        return TagResource::make($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validated = $request->validated();
        
        $tag->update($validated);
        return response('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->noContent();
    }
}
