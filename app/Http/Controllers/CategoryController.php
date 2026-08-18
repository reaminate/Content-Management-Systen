<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\BlogResource;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
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
        })->get();

        return response(CategoryResource::collection($categories),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        Category::create($validated);
        return response(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Request $request)
    {
        if ($request->has('blogs')){
            $category->load('blog');
        }
        return response(CategoryResource::make($category),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->update($validated);
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response(200);
    }
    //for public viewing of active categories
    public function viewPublicActiveCategories(){
        $catgegories = Category::where('active_status', '=', 1)->get();
        return response(CategoryResource::collection($catgegories),200);
    }
    public function viewBlogsForCategory(Category $category){
        $blogs = $category->blog()->paginate(5);
        return response(BlogResource::collection($blogs),200);
    }
}
