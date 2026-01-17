<?php
namespace App\Http\Controllers;

use App\Models\Annotation;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request) {
        $query = Book::with('authors'); // Eager load authors
        $limit = $request->get('limit', 10);
        $type = $request->get('type'); 

        if ($type === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($type === 'rated') {
            $query->orderBy('rating_avg', 'desc');
        } elseif ($type === 'exclusive') {
            $query->where('category', 'exclusive');
        } elseif ($type && $type !== 'recommended') {
            $query->where('category', 'LIKE', "%{$type}%");
        }

        // Search in Title OR Author Name
        if ($search = $request->get('q')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('authors', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $books = $query->take($limit)->get();

        return response()->json($books->map(function($book) {
            return [
                'id' => $book->book_id,
                'title' => $book->title,
                'authors' => $book->authors->map(fn($a) => ['id' => $a->author_id, 'name' => $a->name]),
                'image' => $book->cover_image ?? 'https://placehold.co/150x220?text=No+Cover',
                'rating' => (int)$book->rating_avg,
                'category' => $book->category
            ];
        }));
    }

    public function show($id) {
        $book = Book::with('authors')->findOrFail($id);
        
        // Check if the current user follows any of these authors
        $authors = $book->authors->map(function($author) {
            $isFollowing = false;
            if (Auth::check()) {
                $isFollowing = Auth::user()->followingAuthors()->where('author_follows.author_id', $author->author_id)->exists();
            }
            return [
                'id' => $author->author_id,
                'name' => $author->name,
                'image' => $author->image,
                'bio' => $author->bio,
                'is_following' => $isFollowing
            ];
        });

        return response()->json([
            'id' => $book->book_id,
            'title' => $book->title,
            'description' => $book->description ?? 'No description available.',
            'image' => $book->cover_image,
            'authors' => $authors,
            'rating' => $book->rating_avg,
            'category' => $book->category
        ]);
    }

    // ... getContent and storeAnnotation remain unchanged ...
    public function getContent(Request $request, $bookId, $chapterIndex) {
        $chapter = Chapter::where('book_id', $bookId)
                          ->where('order_index', $chapterIndex)
                          ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Chapter not found'], 404);
        }

        $annotations = Annotation::where('book_id', $bookId)
                                 ->where('chapter_id', $chapter->chapter_id)
                                 ->where('user_id', Auth::id())
                                 ->get();

        return response()->json([
            'chapter' => $chapter,
            'annotations' => $annotations,
            'total_chapters' => Chapter::where('book_id', $bookId)->count()
        ]);
    }

    public function storeAnnotation(Request $request, $bookId) {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,chapter_id',
            'note' => 'required|string',
            'highlighted_text' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $annotation = Annotation::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'book_id' => $bookId,
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return response()->json($annotation, 201);
    }
}