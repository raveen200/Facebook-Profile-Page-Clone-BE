<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $posts = Post::where('user_id', $user->id)->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_image' => $user->profile_image ? url('/images/profile/' . $user->profile_image) : null,
            ],
            'posts' => $posts
        ], 200);
    }
}
