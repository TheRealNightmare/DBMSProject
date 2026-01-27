<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    // Get list of all community groups
    public function getGroups()
    {
        $groups = Group::with('creator:user_id,username')->get();
        return response()->json($groups);
    }

    // Get chat history for a specific group
    public function getMessages($groupId)
    {
        $group = Group::findOrFail($groupId);

        $messages = GroupMessage::with('sender:user_id,username,profile_image')
            ->where('group_id', $groupId)
            ->orderBy('sent_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Send a message to a group
    public function sendMessage(Request $request, $groupId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'is_blurred' => 'nullable|boolean',
        ]);

        $group = Group::findOrFail($groupId);
        $user = $request->user();

        // Create the message
        $message = GroupMessage::create([
            'group_id' => $group->group_id,
            'sender_id' => $user->user_id,
            'message_body' => $request->input('message'),
            'is_blurred' => $request->input('is_blurred', false),
            'sent_at' => now(),
        ]);

        // Load the sender relationship for the broadcast
        $message->load('sender');

        // Broadcast the event to the WebSocket channel
        // REMOVED ->toOthers() so the sender also receives the event
        broadcast(new MessageSent($message));

        return response()->json([
            'status' => 'Message Sent!',
            'message' => $message
        ]);
    }

    // Create a new channel
    public function createChannel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'room_code' => 'required|string|max:50|unique:groups,room_code',
        ]);

        $user = $request->user();

        $group = Group::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'room_code' => $request->input('room_code'),
            'is_default' => false,
            'created_by' => $user->user_id,
        ]);

        $group->load('creator:user_id,username');

        return response()->json([
            'message' => 'Channel created successfully',
            'channel' => $group
        ], 201);
    }

    // Join a channel using room code
    public function joinChannel(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string|max:50',
        ]);

        $group = Group::where('room_code', $request->input('room_code'))->first();

        if (!$group) {
            return response()->json([
                'message' => 'Channel not found with this room code'
            ], 404);
        }

        $group->load('creator:user_id,username');

        return response()->json([
            'message' => 'Channel found',
            'channel' => $group
        ]);
    }
}