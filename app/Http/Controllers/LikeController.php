<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $post_id)
    {
        $user_id = $request->user()->id;

        $like = Like::where('user_id', $user_id)->where('post_id', $post_id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked', 'liked' => false]);
        } else {
            Like::create([
                'user_id' => $user_id,
                'post_id' => $post_id
            ]);
            return response()->json(['message' => 'Liked', 'liked' => true]);
        }
    }
}
