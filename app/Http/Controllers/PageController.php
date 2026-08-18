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
            $query->where('publication_status', '=', $request['publish_status']);
        })->get();

        return response(PageResource::collection($pages),200);
    }
 
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $validated = $request->validated();

        Page::create($validated);

        return response(200);
    }
    /**
     * Display the specified resource.
     */
    public function show(Page $page, Request $request)
    {
        if($request->has('images')){
            $page->load('image');
        }
        return response(PageResource::make($page),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $validate = $request->validated();
        $page->update($validate);
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return response(200);
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
