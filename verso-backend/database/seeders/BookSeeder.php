<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use Carbon\Carbon;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Great Gatsby',
                'description' => 'A classic novel of the Jazz Age.',
                'author_name' => 'F. Scott Fitzgerald',
                'category' => 'Fiction',
                'rating_avg' => 4.5,
                'cover_image' => 'https://via.placeholder.com/150', // Replace with real paths if you have them
                'content_path' => '/books/gatsby.pdf',
                'publication_date' => '1925-04-10',
            ],
            [
                'title' => '1984',
                'description' => 'A dystopian social science fiction novel and cautionary tale.',
                'author_name' => 'George Orwell',
                'category' => 'Sci-Fi',
                'rating_avg' => 4.8,
                'cover_image' => 'https://via.placeholder.com/150',
                'content_path' => '/books/1984.pdf',
                'publication_date' => '1949-06-08',
            ],
            [
                'title' => 'Pride and Prejudice',
                'description' => 'A romantic novel of manners.',
                'author_name' => 'Jane Austen',
                'category' => 'Romance',
                'rating_avg' => 4.6,
                'cover_image' => 'https://via.placeholder.com/150',
                'content_path' => '/books/pride.pdf',
                'publication_date' => '1813-01-28',
            ],
            [
                'title' => 'The Hobbit',
                'description' => 'A fantasy novel of an adventure undertaken by the hobbit Bilbo Baggins.',
                'author_name' => 'J.R.R. Tolkien',
                'category' => 'Fantasy',
                'rating_avg' => 4.9,
                'cover_image' => 'https://via.placeholder.com/150',
                'content_path' => '/books/hobbit.pdf',
                'publication_date' => '1937-09-21',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}