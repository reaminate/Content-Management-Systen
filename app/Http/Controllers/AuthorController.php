<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authors = Author::query()
        ->when($request->has('active'), function($query){
            $query->where('active', '=', 1);
        })->cursorPaginate(5);

        return response()->json(AuthorResource::collection($authors));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $validated = $request->validated();

        Author::create($validated);

        return response()->json(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author, Request $request)
    {
        if($request->has('profile_pic')){
            $author->load('image');
        }
        if($request->has('blogs')){
            $author->load('blogs');
        }
        return response(AuthorResource::make($author), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $validate = $request->validated();
        $author->update($validate);
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return response()->json(200);
    }
}
