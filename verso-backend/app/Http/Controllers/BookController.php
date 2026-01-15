<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Replaces bookService.getBooks()
    public function index(Request $request) {
        $query = Book::query();
        $limit = $request->get('limit', 10);
        $type = $request->get('type'); // latest, recommended, etc.

        if ($type === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($type === 'rated') {
            $query->orderBy('rating_avg', 'desc');
        } elseif ($type === 'exclusive') {
            $query->where('category', 'exclusive');
        }

        // Search functionality
        if ($search = $request->get('q')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
        }

        return response()->json($query->take($limit)->get());
    }

    // Replaces bookService.getBookDetails()
    public function show($id) {
        return Book::findOrFail($id);
    }
}