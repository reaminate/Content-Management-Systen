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
        })->cursorPaginate(15);

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
        $validate = $request->validated();
        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $blog->update($validate);
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
