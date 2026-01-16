<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. CLEAR EXISTING TABLES
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('chapters')->truncate();
        // DB::table('books')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. SEED FIXED "HIGH QUALITY" BOOKS
        $fixedBooks = [
            [
                'title' => 'Think Again',
                'description' => 'Adam Grant examines the critical art of rethinking: learning to question your opinions and open other people\'s minds.',
                'author_name' => 'Adam Grant',
                'cover_image' => 'https://covers.openlibrary.org/b/id/10603788-L.jpg',
                'category' => 'business',
                'rating_avg' => 4.5,
                'content_path' => 'mock_content.pdf',
                'publication_date' => '2021-02-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'The Great Gatsby',
                'description' => 'The story of the mysteriously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.',
                'author_name' => 'F. Scott Fitzgerald',
                'cover_image' => 'https://covers.openlibrary.org/b/id/8259443-L.jpg',
                'category' => 'classic',
                'rating_avg' => 4.8,
                'content_path' => 'mock_content.pdf',
                'publication_date' => '1925-04-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Atomic Habits',
                'description' => 'No matter your goals, Atomic Habits offers a proven framework for improving--every day.',
                'author_name' => 'James Clear',
                'cover_image' => 'https://covers.openlibrary.org/b/id/12556509-L.jpg',
                'category' => 'self-help',
                'rating_avg' => 4.9,
                'content_path' => 'mock_content.pdf',
                'publication_date' => '2018-10-16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert fixed books and generate chapters for them
        foreach ($fixedBooks as $bookData) {
            $bookId = DB::table('books')->insertGetId($bookData);
            $this->seedChapters($bookId, $faker);
        }

        // 3. GENERATE 50 RANDOM BOOKS
        $categories = ['classic', 'business', 'exclusive', 'rated', 'science', 'history', 'romance'];

        for ($i = 0; $i < 50; $i++) {
            $bookData = [
                'title' => $faker->catchPhrase,
                'description' => $faker->paragraph(3),
                'author_name' => $faker->name,
                'cover_image' => 'https://covers.openlibrary.org/b/id/' . $faker->numberBetween(1000000, 10000000) . '-L.jpg',
                'category' => $faker->randomElement($categories),
                'rating_avg' => $faker->randomFloat(1, 1, 5),
                'content_path' => 'mock_content_' . $i . '.pdf',
                'publication_date' => $faker->date(),
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ];

            // Insert book and get ID
            $bookId = DB::table('books')->insertGetId($bookData);
            
            // Generate chapters for this book
            $this->seedChapters($bookId, $faker);
        }
    }

    /**
     * Helper to seed chapters for a specific book.
     */
    private function seedChapters($bookId, $faker)
    {
        $chapters = [];
        // Generate 5 to 10 chapters per book
        $chapterCount = rand(5, 10);

        for ($c = 1; $c <= $chapterCount; $c++) {
            $chapters[] = [
                'book_id' => $bookId,
                'title' => "Chapter $c: " . $faker->words(3, true),
                'content' => $faker->paragraphs(rand(5, 15), true), // Rich text content
                'order_index' => $c,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('chapters')->insert($chapters);
    }
}