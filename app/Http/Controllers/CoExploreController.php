<?php

namespace App\Http\Controllers;

use App\Models\CommunityInvite;
use App\Models\MarketSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CoExploreController extends Controller
{
    /**
     * Send an invite to another community member.
     */
    public function sendInvite(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        if ($senderId == $receiverId) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot invite yourself to explore the market.',
            ], 400);
        }

        // Check if there's already a pending invite
        $existingInvite = CommunityInvite::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->first();

        if ($existingInvite) {
            return response()->json([
                'status' => 'success',
                'message' => 'Invitation already pending.',
                'invite' => $existingInvite,
            ]);
        }

        $invite = CommunityInvite::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        // Load relations
        $invite->load(['sender', 'receiver']);

        return response()->json([
            'status' => 'success',
            'message' => 'Invitation sent successfully.',
            'invite' => $invite,
        ]);
    }

    /**
     * Respond to an invite (accept or reject).
     */
    public function respondInvite(Request $request)
    {
        $request->validate([
            'invite_id' => 'required|exists:community_invites,id',
            'response' => 'required|in:accepted,rejected',
        ]);

        $invite = CommunityInvite::findOrFail($request->invite_id);

        if ($invite->receiver_id != auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized response to this invitation.',
            ], 403);
        }

        if ($invite->status != 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'This invitation has already been processed.',
            ], 400);
        }

        $invite->status = $request->response;
        $invite->save();

        if ($request->response == 'accepted') {
            // Deactivate any existing active sessions involving either user
            MarketSession::where(function ($query) use ($invite) {
                $query->where('host_id', $invite->sender_id)
                    ->orWhere('guest_id', $invite->sender_id)
                    ->orWhere('host_id', $invite->receiver_id)
                    ->orWhere('guest_id', $invite->receiver_id);
            })->where('active', true)
              ->update(['active' => false, 'status' => 'ended']);

            // Create a new session
            $session = MarketSession::create([
                'room_id' => 'room_' . Str::random(12),
                'host_id' => $invite->sender_id,
                'guest_id' => $invite->receiver_id,
                'status' => 'active',
                'active' => true,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Invitation accepted. Session created.',
                'invite' => $invite,
                'session' => $session,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Invitation rejected.',
            'invite' => $invite,
        ]);
    }

    /**
     * Get pending co-explore notifications for current user.
     */
    public function getNotifications()
    {
        $invites = CommunityInvite::where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->with('sender')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $invites,
        ]);
    }

    /**
     * Terminate an active co-explore session.
     */
    public function endSession(Request $request)
    {
        $request->validate([
            'room_id' => 'required|string',
        ]);

        $session = MarketSession::where('room_id', $request->room_id)->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session not found.',
            ], 404);
        }

        $session->active = false;
        $session->status = 'ended';
        $session->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Session ended successfully.',
        ]);
    }
}
