<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use Illuminate\Http\Request;
class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::all()->toResourceCollection();

        return response()->json($authors);
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
        return response($author->toResource(), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        //
    }
}
