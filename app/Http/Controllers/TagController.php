<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Notifications\TagCreated;
use App\Notifications\TagDeleted;
use App\Notifications\TagUpdated;
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
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $tag = Tag::create($validated);
        $request->user()->notify(new TagCreated($tag));
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
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        
        $tag->update($validated);
        $changes = $tag->getChanges();
        $request->user()->notify(new TagUpdated($tag, $changes));
        return response('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag, Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $tag->delete();
        $request->user()->notify(new TagDeleted());
        return response()->noContent();
    }
}
