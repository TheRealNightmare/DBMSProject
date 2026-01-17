<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Author;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1. SEED FIXED AUTHORS & BOOKS
        $fixedData = [
            [
                'author' => ['name' => 'Adam Grant', 'bio' => 'Organizational psychologist.'],
                'book' => [
                    'title' => 'Think Again',
                    'description' => 'Adam Grant examines the critical art of rethinking.',
                    'cover_image' => 'https://covers.openlibrary.org/b/id/10603788-L.jpg',
                    'category' => 'business',
                    'rating_avg' => 4.5,
                ]
            ],
            [
                'author' => ['name' => 'F. Scott Fitzgerald', 'bio' => 'American novelist of the Jazz Age.'],
                'book' => [
                    'title' => 'The Great Gatsby',
                    'description' => 'The story of the mysteriously wealthy Jay Gatsby.',
                    'cover_image' => 'https://covers.openlibrary.org/b/id/8259443-L.jpg',
                    'category' => 'classic',
                    'rating_avg' => 4.8,
                ]
            ]
        ];

        foreach ($fixedData as $data) {
            // Create or Find Author
            $author = Author::firstOrCreate(
                ['name' => $data['author']['name']],
                ['bio' => $data['author']['bio'], 'image' => 'https://placehold.co/100?text='.substr($data['author']['name'],0,1)]
            );

            // Create Book
            $book = Book::create(array_merge($data['book'], [
                'content_path' => 'mock_content.pdf',
                'publication_date' => now(),
            ]));

            // Attach
            $book->authors()->attach($author->author_id);
            $this->seedChapters($book->book_id, $faker);
        }

        // 2. GENERATE RANDOM BOOKS & AUTHORS
        $categories = ['classic', 'business', 'exclusive', 'rated', 'science', 'history'];

        for ($i = 0; $i < 30; $i++) {
            $author = Author::create([
                'name' => $faker->name,
                'bio' => $faker->sentence,
                'image' => 'https://placehold.co/100?text=A'
            ]);

            $book = Book::create([
                'title' => $faker->catchPhrase,
                'description' => $faker->paragraph(3),
                'cover_image' => 'https://covers.openlibrary.org/b/id/' . $faker->numberBetween(1000000, 10000000) . '-L.jpg',
                'category' => $faker->randomElement($categories),
                'rating_avg' => $faker->randomFloat(1, 1, 5),
                'content_path' => 'mock_content_' . $i . '.pdf',
                'publication_date' => $faker->date(),
            ]);

            $book->authors()->attach($author->author_id);
            $this->seedChapters($book->book_id, $faker);
        }
    }

    private function seedChapters($bookId, $faker)
    {
        $chapters = [];
        $chapterCount = rand(3, 5);
        for ($c = 1; $c <= $chapterCount; $c++) {
            $chapters[] = [
                'book_id' => $bookId,
                'title' => "Chapter $c",
                'content' => $faker->paragraphs(5, true),
                'order_index' => $c,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('chapters')->insert($chapters);
    }
}