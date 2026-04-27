<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $communities = Community::withCount('users')->get();
        
        // Map to include if the current user is a member
        $communities->map(function ($community) use ($user) {
            $community->is_member = $user ? $community->users()->where('user_id', $user->id)->exists() : false;
            return $community;
        });

        // Sort: user's region communities first, then joined, then rest
        $sorted = $communities->sortByDesc(function ($community) use ($user) {
            $score = 0;
            if ($user && $community->region === $user->region) $score += 10;
            if ($community->is_member) $score += 5;
            return $score;
        })->values();

        return response()->json($sorted);
    }

    public function show($id)
    {
        $community = Community::with(['users' => function($q) {
            $q->select('users.id', 'users.name', 'users.region', 'users.profile_image');
        }])->findOrFail($id);

        return response()->json($community);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $community = Community::create([
            'name' => $request->name,
            'region' => $request->region,
            'description' => $request->description
        ]);

        // Automatically add the creator to the community
        $request->user()->communities()->attach($community->id);

        return response()->json($community, 201);
    }

    public function join(Request $request)
    {
        $request->validate(['community_id' => 'required|exists:communities,id']);
        $user = $request->user();
        
        $user->communities()->syncWithoutDetaching([$request->community_id]);
        
        return response()->json(['message' => 'Joined community successfully.']);
    }

    public function leave(Request $request)
    {
        $request->validate(['community_id' => 'required|exists:communities,id']);
        $user = $request->user();
        
        $user->communities()->detach($request->community_id);
        
        return response()->json(['message' => 'Left community successfully.']);
    }
}
