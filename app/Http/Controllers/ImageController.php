<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $images = Image::query()
        ->when($request->has('for_author'), function($query){
            $query->where('for_author', '=', true);
        })
        ->when($request->has('original_filename'), function($query) use($request){
            $query->where('original_filename', $request['original_filename']);
        })
        ->when($request->has('caption'), function($query) use($request){
            $query->where('caption', $request['caption']);
        })
        ->cursorPaginate(10);

        
        
        return response(ImageResource::collection($images));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request)
    {
        $validated = $request->validated();
        $file = $validated['image'];
        $storedPath = $file->store('images', 'public');

        Image::create([
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $validated['stored_filename'],
            'file_path' => $storedPath,
            'file_type' => $file->getMimeType(),
            'filesize' => $file->getSize(),
            'for_author' => $validated['for_author'] ?? false,
            'caption' => $validated['caption'] ?? null,
            'upload_date' => now(),
        ]);

        return response(200);
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

        return response(ImageResource::make($image), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        $validated = $request->validated();

        if (isset($validated['image'])) {
            $file = $validated['image'];
            $storedPath = $file->store('images', 'public');

            Storage::disk('public')->delete($image->file_path);

            $validated['original_filename'] = $file->getClientOriginalName();
            $validated['stored_filename'] = basename($storedPath);
            $validated['file_path'] = $storedPath;
            $validated['file_type'] = $file->getMimeType();
            $validated['filesize'] = $file->getSize();
            unset($validated['image']);
        }

        $image->update($validated);
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image->delete();
        return response(200);
    }
}
