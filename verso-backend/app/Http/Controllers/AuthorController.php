<?php
namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function toggleFollow($id)
    {
        $user = Auth::user();
        $author = Author::findOrFail($id);

        // Check if already following
        $isFollowing = $user->followingAuthors()->where('author_follows.author_id', $id)->exists();

        if ($isFollowing) {
            $user->followingAuthors()->detach($id);
            return response()->json(['message' => 'Unfollowed author', 'is_following' => false]);
        } else {
            $user->followingAuthors()->attach($id);
            return response()->json(['message' => 'Followed author', 'is_following' => true]);
        }
    }
}