<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request) {
        $query = Book::query();
        $limit = $request->get('limit', 10);
        $type = $request->get('type'); 

        // Handle various frontend filters
        if ($type === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($type === 'rated') {
            $query->orderBy('rating_avg', 'desc');
        } elseif ($type === 'exclusive') {
            $query->where('category', 'exclusive');
        } elseif ($type && $type !== 'recommended') {
            // Fallback: assume 'classic', 'business' are categories in DB
            $query->where('category', 'LIKE', "%{$type}%");
        }

        // Search
        if ($search = $request->get('q')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        $books = $query->take($limit)->get();

        // Transform to match frontend BookCard props
        return response()->json($books->map(function($book) {
            return [
                'id' => $book->book_id,
                'title' => $book->title,
                'author' => $book->author_name,
                'image' => $book->cover_image ?? 'https://placehold.co/150x220?text=No+Cover',
                'rating' => (int)$book->rating_avg,
                'category' => $book->category
            ];
        }));
    }

    public function show($id) {
    $book = Book::findOrFail($id);
    return response()->json([
        'id' => $book->book_id,
        'title' => $book->title,
        'description' => $book->description ?? 'No description available.', // Use real column
        'image' => $book->cover_image,
        'authors' => [['name' => $book->author_name]],
        'rating' => $book->rating_avg
    ]);
}
}