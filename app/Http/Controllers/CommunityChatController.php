<?php

namespace App\Http\Controllers;

use App\Models\CommunityMessage;
use Illuminate\Http\Request;

class CommunityChatController extends Controller
{
    public function index($community_id)
    {
        $messages = CommunityMessage::where('community_id', $community_id)
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();
            
        return response()->json($messages);
    }

    public function store(Request $request, $community_id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $message = CommunityMessage::create([
            'community_id' => $community_id,
            'user_id' => $request->user()->id,
            'content' => $request->content
        ]);

        $message->load('user');

        return response()->json($message, 201);
    }
}
