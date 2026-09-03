<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Jobs\SummarizePost;
use App\Notifications\BlogCreated;
use App\Notifications\BlogUpdated;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $blog = Blog::query()
        ->when($request->has('publication_status'), function ($query) use ($request){
            $query->where('publication_status',$request['publication_status']);
        })
        ->when($request->has('category_id'), function ($query) use ($request){
            $query->where('category_id', $request['category_id']);
        })
        ->when($request->has('author_id'), function ($query) use ($request){
            $query->where('author_id', $request['author_id']);
        })
        
        ->when($request->has('tags'), function($query) use($request){
            $query->whereHas('tags', function($q) use ($request){
                $q->whereIn('tags.id', (array) $request['tags']);
            });
        })
        ->when(($request->has('published_from')||$request->has('published_until')), function($query) use ($request){
            $query->where('publication_status' ,'published')
            ->where('published_at', '>', $request['published_from']??'0-0-0')
            ->where('published_at', '<', $request['published_until']??'200000-12-30');
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

        return BlogResource::collection($blog);
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

        $request->user()->notify(new BlogCreated($blog));

        if($request['publication_status'] == 'published'){
            SummarizePost::dispatch($blog);
        }
        return response()->noContent(201);
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
        return BlogResource::make($blog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        if($request->user()->cannot('updateDelete', $blog)){
            abort(403);
        }
        $validated = $request->validated();
        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $wasPublished = $blog->publication_status === 'published';
        $newStatus = $validated['publication_status'] ?? $blog->publication_status;
        if($newStatus === 'published'){
            $validated['published_at'] = $validated['published_at'] ?? $blog->published_at ?? now();
        } else {
            unset($validated['published_at']);
            if($request->has('publication_status')){
                $validated['published_at'] = null;
            }
        }

        $blog->update($validated);
        $blog->tags()->sync($tags);
        $changes = $blog->getChanges();
        $request->user()->notify(new BlogUpdated($blog, $changes));

        if($newStatus === 'published' && !$wasPublished){
            SummarizePost::dispatch($blog);
        }

        return response('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Blog $blog)
    {
        if($request->user()->cannot('updateDelete', $blog)){
            abort(403);
        }
        $blog->delete();
        return response()->noContent();
    }
       //for public end point viewing, only shows published blogs
    public function publicViewIndex(){
        $blogs = Blog::where('publication_status', '=', 'published')->get();
        return BlogResource::collection($blogs);
    }
    //for public end point viewing a single blog by slug, only shows published blogs
    public function viewBySlug(Blog $blog){
        if($blog->publication_status !== 'published'){
            abort(404);
        }
        return BlogResource::make($blog);
    }

    public function export(Request $request){
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $blogs = Blog::all()->toArray();
        $writer = SimpleExcelWriter::create('export.xlsx');
        $writer->addRows($blogs);
        return response('', 200);
    }
    public function import(Request $request){
        if($request->user()->cannot('admin')){
            abort(403);
        }
        $path = 'export.xlsx';
        $existingTitles = Blog::pluck('title')->toArray();
        $new = [];
        $reader = SimpleExcelReader::create($path)
        ->fromSheet(1)->getRows();
        $reader->each(function(array $row) use($existingTitles, &$new){
            if(in_array($row['title'], $existingTitles)){
                return;
            }
            $new[] = $row;
        });

        foreach($new as $row){
            $storeRequest = StoreBlogRequest::create('/blogs', 'POST', $row);
            $storeRequest->setContainer(app())
                ->setUserResolver(request()->getUserResolver());
            $storeRequest->validateResolved();

            $this->store($storeRequest);
        }
    }
}
