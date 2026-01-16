<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommunityController extends Controller
{
    // Get list of all community groups
    public function getGroups()
    {
        $groups = Group::all();
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
        ]);

        $group = Group::findOrFail($groupId);
        $user = $request->user();

        // Create the message
        $message = GroupMessage::create([
            'group_id' => $group->group_id,
            'sender_id' => $user->user_id,
            'message_body' => $request->input('message'),
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
}