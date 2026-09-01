<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\BlogResource;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Notifications\CategoryCreated;
use App\Notifications\CategoryDeleted;
use App\Notifications\CategoryUpdated;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query()
        ->when($request->has('active_status'), function($query){
            $query->where('active_status', '=', 1);
        })->cursorPaginate();

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $category = Category::create($validated);
        $request->user()->notify(new CategoryCreated($category));
        return response()->noContent(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Request $request)
    {
        if ($request->has('blogs')){
            $category->load('blog');
        }
        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $validated = $request->validated();
        $category->update($validated);
        $changes = $category->getChanges();
        $request->user()->notify(new CategoryUpdated($category, $changes));
        return response('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Request $request)
    {
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $category->delete();
        $request->user()->notify(new CategoryDeleted());
        return response()->noContent();
    }
    //for public viewing of active categories
    public function viewPublicActiveCategories(){
        $catgegories = Category::where('active_status', '=', 1)->get();
        return CategoryResource::collection($catgegories);
    }
    public function viewBlogsForCategory(Category $category){
        $blogs = $category->blog()->paginate(5);
        $blogs->where('publication_status', 'published');
        return response(BlogResource::collection($blogs),200);
    }
}
