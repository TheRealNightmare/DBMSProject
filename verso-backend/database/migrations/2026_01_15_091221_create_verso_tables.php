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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Custom Primary Key
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password'); // Added for Auth
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id');
            $table->string('title');
            $table->string('content_path')->nullable(); // PDF/Epub file path
            $table->string('cover_image')->nullable(); // Added for UI
            $table->string('author_name')->nullable(); // Cached author name
            $table->date('publication_date')->nullable();
            $table->string('category')->nullable(); // For filtering (Latest, Exclusive, etc.)
            $table->float('rating_avg')->default(0); // For "Highly Rated"
            $table->timestamps();
        });

        Schema::create('shelves', function (Blueprint $table) {
            $table->id('shelf_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('privacy_level', 20)->default('Public');
            $table->boolean('is_system_default')->default(false);
            $table->timestamps();
        });

        Schema::create('shelf_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('shelf_id')->constrained('shelves', 'shelf_id')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->string('status', 20); // 'Read', 'Reading', 'Want to Read'
            $table->timestamp('added_at')->useCurrent();
        });

        Schema::create('reading_sessions', function (Blueprint $table) {
            $table->id('session_id');
            $table->foreignId('item_id')->constrained('shelf_items', 'item_id')->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('pages_read_count')->default(0);
            $table->timestamps();
        });
        
        Schema::create('groups', function (Blueprint $table) {
            $table->id('group_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('group_messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->foreignId('group_id')->constrained('groups', 'group_id')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->text('message_body');
            $table->timestamp('sent_at')->useCurrent();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('title');
            $table->string('host_name'); // Simplified from host_type/host_id for MVP
            $table->text('description')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verso_tables');
    }
};
