<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'region' => 'sometimes|string|in:North India,South India,East India,West India,Central India',
            'profile_image' => 'sometimes|image|max:5120', // 5MB max
        ]);

        if ($request->has('name')) {
            $user->name = $validated['name'];
        }
        
        if ($request->has('region')) {
            $user->region = $validated['region'];
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_image));
            }
            
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = '/storage/' . $path;
        }

        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'Profile updated successfully',
        ]);
    }
}

