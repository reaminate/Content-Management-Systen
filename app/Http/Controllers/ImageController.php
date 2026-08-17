<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $images = Image::all();
        if($request->has('author')){
            $images->where('for_author', '=', null);
        }
        return response($images->toResourceCollection());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image, Request $request)
    {
        if($request->has('author')){
            $image->load('author');
        }
        if($request->has('blogs')){
            $image->load('blogs');
        }
        if($request->has('pages')){
            $image->load('pages');
        }

        return response($image->toResource(), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
