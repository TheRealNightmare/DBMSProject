<?php
namespace App\Http\Controllers;

use App\Models\Annotation;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Shelf;
use App\Models\ShelfItem;
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

    public function getContent(Request $request, $bookId, $chapterIndex) {
        $user = Auth::user();
        
        // Find or create the user's default shelf (you might want a dedicated 'History' shelf logic later)
        $shelf = Shelf::firstOrCreate(
            ['user_id' => $user->user_id, 'is_system_default' => true],
            ['name' => 'Reading List', 'privacy_level' => 'Private']
        );

        // Add to history (Shelf Item) if not exists
        ShelfItem::firstOrCreate(
            ['shelf_id' => $shelf->shelf_id, 'book_id' => $bookId],
            ['status' => 'Reading', 'added_at' => now()]
        );

        // ... existing logic to fetch chapter content ...
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

    public function getHistory(Request $request) {
        $userId = Auth::id();
        
        // Fetch books linked to the user via shelf_items
        $books = Book::whereHas('shelfItems.shelf', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with(['authors'])
        ->latest('updated_at') // Requires books timestamp or pivot timestamp
        ->get();

        // Transform for frontend
        return response()->json($books->map(function($book) {
            return [
                'id' => $book->book_id,
                'title' => $book->title,
                'authors' => $book->authors->map(fn($a) => $a->name)->join(', '),
                'image' => $book->cover_image,
                'rating' => $book->rating_avg,
                'category' => $book->category,
                'statusLabel' => 'Reading', // simplified
                'date' => $book->created_at->format('M d, Y') // simplified
            ];
        }));
    }

    public function removeFromHistory($id) {
        $userId = Auth::id();
        
        // Find the item on any of the user's shelves and delete it
        $deleted = ShelfItem::where('book_id', $id)
            ->whereHas('shelf', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Removed from history']);
        }
        
        return response()->json(['message' => 'Book not found in history'], 404);
    }
}