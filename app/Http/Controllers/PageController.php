<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
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
        ->when($request->has('title'), function($query) use($request){
            $query->where('title', $request['title']);
        })
        ->when($request->has('content'), function($query) use($request){
            $query->where('content', $request['content']);
        })
        ->when($request->has('order'), function($query) use($request){
            $query->when($request['order']=='title', function($q){
                $q->orderBy('title', 'asc');
            });
            $query->when($request['order']=='title', function($q){
                $q->orderBy('created_at', 'asc');
            });
            $query->when($request['order']=='title', function($q){
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
        $validated = $request->validated();

        Page::create($validated);

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
        $validate = $request->validated();
        $page->update($validate);
        return response('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return response()->noContent();
    }
       //for public end point viewing, only shows published pages
    public function publicViewIndex(){
        $pages = Page::where('publication_status', '=', 'published')->get();
        return response(PageResource::collection($pages),200);
    }
    //for public end point viewing a single page by slug, only shows published pages
    public function viewBySlug(Page $page){
        if($page->publication_status !== 'published'){
            abort(404);
        }
        return response(PageResource::make($page),200);
    }
}
