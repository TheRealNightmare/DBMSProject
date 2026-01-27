<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Book Ratings Table
        Schema::create('book_ratings', function (Blueprint $table) {
            $table->id('rating_id');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->integer('rating')->unsigned()->comment('1-5 stars');
            $table->timestamps();

            // One rating per user per book
            $table->unique(['book_id', 'user_id']);
        });

        // Book Comments Table
        Schema::create('book_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_comments');
        Schema::dropIfExists('book_ratings');
    }
};
