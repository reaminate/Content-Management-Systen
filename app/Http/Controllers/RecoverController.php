<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Image;
use App\Models\Page;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RecoverController extends Controller
{

    //restores soft deleted models
    public function restore(Request $request, int $id){
        if(!$request->hasAny('author', 'images', 'pages', 'blogs')){
            return response()->json([
                'error' => 'invalid restore request',
            ]);
        }
        if($request->has('author')){
            Author::withTrashed()->findOrFail($id)->restore();
        }
        if($request->has('images')){
            Image::withTrashed()->findOrFail($id)->restore();
        }
        if($request->has('pages')){
            Page::withTrashed()->findOrFail($id)->restore();
        }
        if($request->has('blogs')){
            Blog::withTrashed()->findOrFail($id)->restore();
        }    
        return response()->json([
            'message'=> 'succesfully restored'
        ], 200);
    }

    //fully destroys soft deleted models
    public function destroy(Request $request, int $id){
        if(!$request->hasAny('author', 'images', 'pages', 'blogs')){
            return response()->json([
                'error' => 'invalid force delete request',
            ]);
        }
        if($request->has('author')){
            Author::withTrashed()->findOrFail($id)->forceDelete();
        }
        if($request->has('images')){
            Image::withTrashed()->findOrFail($id)->forceDelete();
        }
        if($request->has('pages')){
            Page::withTrashed()->findOrFail($id)->forceDelete();
        }
        if($request->has('blogs')){
            Blog::withTrashed()->findOrFail($id)->forceDelete();
        }    
        return response()->json([
            'message'=> 'succesfully destroyed'
        ], 200);
    }
}
