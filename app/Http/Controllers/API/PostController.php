<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        if(!count($posts)) {
            return response(['message' => 'Posts not found.'], 404);
        }
        return response(Post::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'file' => 'required',
        ]);

        $file_url = "";

        if($request->hasFile('file')) {

            $file = $request->file('file');

            $file_path = $file->store('public/postFiles');
            $file_storage_url = Storage::url($file_path);
            $file_url = url($file_storage_url);
        }

        $post = Post::create([
            'title' => $fields['title'],
            'description' => $fields['description'],
            'slug' => Str::slug($fields['title'], '-'),
            'image_url' => $file_url,
        ]);

        return response($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response(['message' => 'Post not found.'], 404);
        }

        return response($post, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Post::destroy($id)) {
            return  response(['message' => 'Post not found'], 404);
        }
        return  response(['message' => 'Post deleted successfully'], 200);
    }
}
