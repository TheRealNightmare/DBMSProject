<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Unified search for users and books
     */
    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([
                'users' => [],
                'books' => []
            ]);
        }

        $currentUser = $request->user();

        // Search Users
        $users = User::where('username', 'LIKE', "%{$query}%")
            ->where('user_id', '!=', $currentUser->user_id)
            ->withCount(['followers', 'following'])
            ->limit(5)
            ->get();

        // Check is_following status for each user
        $users->each(function ($user) use ($currentUser) {
            $user->is_following = $currentUser->following()
                ->where('following_id', $user->user_id)
                ->exists();
        });

        // Search Books (by title or author)
        $books = Book::with('authors')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhereHas('authors', function($authorQuery) use ($query) {
                      $authorQuery->where('name', 'like', "%{$query}%");
                  });
            })
            ->limit(5)
            ->get();


        // Format books
        $booksFormatted = $books->map(function($book) {
            return [
                'book_id' => $book->book_id,
                'title' => $book->title,
                'authors' => $book->authors->pluck('name')->join(', '),
                'cover_image' => $book->cover_image ?? 'https://placehold.co/150x220?text=No+Cover',
                'rating_avg' => $book->rating_avg,
                'category' => $book->category
            ];
        });

        return response()->json([
            'users' => $users,
            'books' => $booksFormatted
        ]);
    }
}
