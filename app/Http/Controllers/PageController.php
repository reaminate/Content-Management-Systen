<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Notifications\PageCreated;
use App\Notifications\PageUpdated;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = Page::query()
        ->when($request->has('publish_status'), function ($query) use ($request){
            $query->where('publication_status', $request['publish_status']);
        })
        ->when($request->has('order'), function($query) use($request){
            $query->when($request['order']=='title', function($q){
                $q->orderBy('title', 'asc');
            });
            $query->when($request['order']=='created_at', function($q){
                $q->orderBy('created_at', 'asc');
            });
            $query->when($request['order']=='updated_at', function($q){
                $q->orderBy('updated_At', 'asc');
            });
        })
        ->cursorPaginate(15);

        return PageResource::collection($pages);
    }
 
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        if($request->user()->cannot('admin')){
            abort(402);
        }
        $validated = $request->validated();

        $page = Page::create($validated);

        $request->user()->notify(new PageCreated($page));

        return response()->noContent(201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Page $page, Request $request)
    {
        if($request->has('images')){
            $page->load('image');
        }
        return PageResource::make($page);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $newStatus = $validated['publication_status'] ?? $page->publication_status;
        if($newStatus === 'published'){
            $validated['published_date'] = $validated['published_date'] ?? $page->published_date ?? now();
        } else {
            unset($validated['published_date']);
            if($request->has('publication_status')){
                $validated['published_date'] = null;
            }
        }
        $page->update($validated);
        $changes = $page->getChanges();
        $request->user()->notify(new PageUpdated($page, $changes));
        return response('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page, Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(402);
        }
        $page->delete();
        return response()->noContent();
    }
       //for public end point viewing, only shows published pages
    public function publicViewIndex(){
        $pages = Page::where('publication_status', '=', 'published')->cursorPaginate(15);
        return PageResource::collection($pages);
    }
    //for public end point viewing a single page by slug, only shows published pages
    public function viewBySlug(Page $page){
        if($page->publication_status !== 'published'){
            abort(404);
        }
        return PageResource::make($page);
    }
}
