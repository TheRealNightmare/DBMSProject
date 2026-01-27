<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // [NEW] Search users
    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([]);
        }

        $currentUser = $request->user();

        // Search by username, exclude current user
        $users = User::where('username', 'LIKE', "%{$query}%")
            ->where('user_id', '!=', $currentUser->user_id)
            ->withCount(['followers', 'following'])
            ->limit(10)
            ->get();

        // Check is_following status for each result
        $users->each(function ($user) use ($currentUser) {
            $user->is_following = $currentUser->following()
                ->where('following_id', $user->user_id)
                ->exists();
        });

        return response()->json($users);
    }

    // Get public profile of a user (Existing)
    public function show($id)
    {
        $user = User::withCount(['followers', 'following'])->findOrFail($id);
        
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->following()->where('following_id', $id)->exists();
        }

        return response()->json([
            'user' => $user,
            'is_following' => $isFollowing
        ]);
    }

    // Follow (Existing)
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

    // Unfollow (Existing)
    public function unfollow(Request $request, $id)
    {
        $currentUser = $request->user();
        $currentUser->following()->detach($id);

        return response()->json(['message' => 'Unfollowed successfully']);
    }
}