<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $blog = Blog::query()
        ->when($request->has('publication_status'), function ($query) use ($request){
            $query->where('publication_status', '=', $request['publication_status']);
        })
        ->when($request->has('category_id'), function ($query) use ($request){
            $query->where('category_id', '=', $request['category_id']);
        })
        ->when($request->has('author_id'), function ($query) use ($request){
            $query->where('author_id', '=', $request['author_id']);
        })
        
        ->when($request->has('title'), function($query) use($request){
            $query->where('title', $request['title']);
        })
        ->when($request->has('content'), function($query) use($request){
            $query->where('content', $request['content']);
        })
        ->when($request->has('excerpt'), function($query) use($request){
            $query->where('excerpt', $request['excerpt']);
        })
        ->when($request->has('tags'), function($query) use($request){
            $query->whereHas('tags', function($q) use ($request){
                $q->whereIn('tags.id', (array) $request['tags']);
            });
        })
        ->when(($request->has('published_from')||$request->has('published_until')), function($query) use ($request){
            $query->where('publication_status', '=' ,'published')
            ->where('published_at', '>', $request['published_from']??null)
            ->where('published_at', '<', $request['published_until']??null);
        })
        ->when($request->has('order'), function($query) use ($request){
            $query->when(($request['order']=='new'), function($q){
                $q->orderBy('published_at', 'asc');
            });
            $query->when(($request['order']=='old'), function($q){
                $q->orderBy('published_at', 'desc');
            });
            $query->when(($request['order']=='A-Z'), function($q){
                $q->orderBy('title', 'asc');
            });
            $query->when(($request['order']=='Z-A'), function($q){
                $q->orderBy('title', 'asc');
            });
        })
        ->cursorPaginate(15);

        return response(BlogResource::collection($blog),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $validate = $request->validated();
        $tags = $validate['tags'] ?? [];
        unset($validate['tags']);

        $blog = Blog::create($validate);
        $blog->tags()->sync($tags);

        return response(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog, Request $request)
    {
        if($request->has('tags')) {
            $blog->load('tags');
        }
        if($request->has('author_id')) {
            $blog->load('author');
        }
        if($request->has('image_id')) {
            $blog->load('image');
        }
        if($request->has('category_id')) {
            $blog->load('category');
        }
        return response(BlogResource::make($blog),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();
        $tags = $validated['tags'] ?? [];
        if((!($request['publication_status']=='published'))&& !$request->has('published_at')){
            $validated['published_at'] = null;
        }
        unset($validated['tags']);
        if(($request->has('published_at'))){
            $validated['published_at'] = $request['published_at']??now();
            $validated['publication_status'] = 'published';
        }
        
        $blog->update($validated);
        $blog->tags()->sync($tags);
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return response(200);
    }
       //for public end point viewing, only shows published blogs
    public function publicViewIndex(){
        $blogs = Blog::where('publication_status', '=', 'published')->get();
        return response(BlogResource::collection($blogs),200);
    }
    //for public end point viewing a single blog by slug, only shows published blogs
    public function viewBySlug(Blog $blogs){
        if($blogs->publication_status !== 'published'){
            abort(404);
        }
        return response(BlogResource::make($blogs),200);
    }
}
