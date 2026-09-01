<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Image;
use App\Notifications\AuthorCreated;
use App\Notifications\AuthorDeleted;
use App\Notifications\AuthorUpdated;
use App\Notifications\ImageUpdated;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
use function Laravel\Prompts\notify;
class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authors = Author::query()
        ->when($request->has('active'), function($query){
            $query->where('active', 1);
        })
        ->when($request->has('name'), function($query) use($request){
            $query->where('name', $request['name']);
        })
        ->latest()->cursorPaginate(5);

        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $validated = $request->validated();

        $author = Author::create($validated);
        $author->image()->update(['for_author'=>true]);
        $request->user()->notify(new AuthorCreated($author));
        return response()->noContent(201);
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
        return AuthorResource::make($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $validated = $request->validated();
        $author->update($validated);
        $changes = $author->getChanges();
        if(!isset($changes['profile_pic'])){
            $request->user()->notify(new AuthorUpdated($author, $changes));
            return response( '',200);
        }
        $prev_profile_pic_id = $author->getPrevious()['profile_pic'];
        $prev_profile_pic = Image::findOrFail($prev_profile_pic_id);
        $prev_profile_pic->update(['for_author'=>false]);
        $request->user()->notify(new ImageUpdated($prev_profile_pic, $changes));
        $author->image()->update(['for_author'=>true]);
        $request->user()->notify(new AuthorUpdated($author, $changes));
        $request->user()->notify(new ImageUpdated($author->image, $changes));
        return response( '',200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author, Request $request)
    {
        $author->delete();
        $request->user()->notify(new AuthorDeleted());
        return response()->noContent();

    }
}
