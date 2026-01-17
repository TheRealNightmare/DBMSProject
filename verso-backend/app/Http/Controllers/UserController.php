<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Get public profile of a user (not the logged-in user)
    public function show($id)
    {
        $user = User::withCount(['followers', 'following'])->findOrFail($id);
        
        // Check if the currently authenticated user is following this user
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->following()->where('following_id', $id)->exists();
        }

        return response()->json([
            'user' => $user,
            'is_following' => $isFollowing
        ]);
    }

    // Follow a user
    public function follow(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = $request->user();

        if ($currentUser->user_id == $targetUser->user_id) {
            return response()->json(['message' => 'You cannot follow yourself.'], 400);
        }

        if (!$currentUser->following()->where('following_id', $id)->exists()) {
            $currentUser->following()->attach($id);
        }

        return response()->json(['message' => 'Followed successfully']);
    }

    // Unfollow a user
    public function unfollow(Request $request, $id)
    {
        $currentUser = $request->user();
        $currentUser->following()->detach($id);

        return response()->json(['message' => 'Unfollowed successfully']);
    }
}