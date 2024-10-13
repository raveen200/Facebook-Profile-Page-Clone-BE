<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postContent' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $post = new Post();
        $post->postContent = $request->postContent;
        $post->user_id = $request->user()->id;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/posts'), $imageName);
            $post->image = $imageName;
        }

        $post->save();

        return response()->json([
            'message' => 'Post added successfully!',
            'post' => $post
        ], 201);
    }

 
    
}
