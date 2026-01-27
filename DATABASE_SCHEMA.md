# Verso Application Database Schema

## Overview

This document outlines the database structure for the Verso application, including table definitions, column details, and entity relationships.

## Tables

### 1. `users`

Stores user account information.

- **Primary Key:** `user_id`
- **Columns:**
  - `username` (string, 50, unique): User's display name.
  - `email` (string, 100, unique): User's email address.
  - `password` (string): Hashed password.
  - `bio` (text, nullable): User biography.
  - `profile_image` (string, nullable): Path to profile image.
  - `zip_code` (string, 20, nullable): Location data.
  - `created_at`, `updated_at` (timestamps)

### 2. `authors`

Stores information about book authors.

- **Primary Key:** `author_id`
- **Columns:**
  - `name` (string): Author's full name.
  - `bio` (text, nullable): Author biography.
  - `image` (string, nullable): Path to author image.
  - `created_at`, `updated_at` (timestamps)

### 3. `books`

Stores details about books available in the system.

- **Primary Key:** `book_id`
- **Columns:**
  - `title` (string): Book title.
  - `description` (text, nullable): Book summary.
  - `content_path` (string, nullable): Path to book file/content.
  - `cover_image` (string, nullable): Path to cover image.
  - `publication_date` (date, nullable).
  - `category` (string, nullable).
  - `rating_avg` (float, default: 0).
  - `created_at`, `updated_at` (timestamps)

### 4. `chapters`

Stores individual chapters for books.

- **Primary Key:** `chapter_id`
- **Foreign Keys:**
  - `book_id` -> `books(book_id)` (Cascade Delete)
- **Columns:**
  - `title` (string): Chapter title.
  - `content` (longText): The actual text content.
  - `order_index` (integer): Sequence order of the chapter.
  - `created_at`, `updated_at` (timestamps)

### 5. `shelves`

Custom or default collections created by users to organize books.

- **Primary Key:** `shelf_id`
- **Foreign Keys:**
  - `user_id` -> `users(user_id)` (Cascade Delete)
- **Columns:**
  - `name` (string, 100): Shelf name.
  - `privacy_level` (string, 20, default: 'Public').
  - `is_system_default` (boolean, default: false).
  - `created_at`, `updated_at` (timestamps)

### 6. `shelf_items`

Represents the many-to-many relationship between Shelves and Books.

- **Primary Key:** `item_id`
- **Foreign Keys:**
  - `shelf_id` -> `shelves(shelf_id)` (Cascade Delete)
  - `book_id` -> `books(book_id)` (Cascade Delete)
- **Columns:**
  - `status` (string, 20): e.g., 'Read', 'Reading', 'Want to Read'.
  - `added_at` (timestamp, useCurrent).

### 7. `reading_sessions`

Tracks reading activity for specific shelf items.

- **Primary Key:** `session_id`
- **Foreign Keys:**
  - `item_id` -> `shelf_items(item_id)` (Cascade Delete)
- **Columns:**
  - `start_time` (timestamp).
  - `end_time` (timestamp, nullable).
  - `pages_read_count` (integer, default: 0).
  - `created_at`, `updated_at` (timestamps)

### 8. `annotations`

User highlights and notes on specific book chapters.

- **Primary Key:** `annotation_id`
- **Foreign Keys:**
  - `user_id` -> `users(user_id)` (Cascade Delete)
  - `book_id` -> `books(book_id)` (Cascade Delete)
  - `chapter_id` -> `chapters(chapter_id)` (Cascade Delete)
- **Columns:**
  - `highlighted_text` (text, nullable).
  - `note` (text).
  - `color` (string, 20, default: 'yellow').
  - `created_at`, `updated_at` (timestamps)

### 9. `groups`

Community groups for users.

- **Primary Key:** `group_id`
- **Foreign Keys:**
  - `created_by` -> `users(user_id)` (Set Null)
- **Columns:**
  - `name` (string, 100).
  - `description` (text, nullable).
  - `room_code` (string, 50, unique): Unique code for joining groups.
  - `is_default` (boolean, default: false): Mark default channels.
  - `created_at`, `updated_at` (timestamps)

### 10. `group_messages`

Messages sent within groups.

- **Primary Key:** `message_id`
- **Foreign Keys:**
  - `group_id` -> `groups(group_id)` (Cascade Delete)
  - `sender_id` -> `users(user_id)` (Cascade Delete)
- **Columns:**
  - `message_body` (text).
  - `is_blurred` (boolean, default: false): Whether message content is blurred.
  - `sent_at` (timestamp, useCurrent).

### 11. `events`

Events hosted by users or system.

- **Primary Key:** `event_id`
- **Columns:**
  - `title` (string).
  - `category` (string, nullable).
  - `host_name` (string).
  - `description` (text, nullable).
  - `start_time` (timestamp).
  - `end_time` (timestamp, nullable).
  - `created_at`, `updated_at` (timestamps)

### 12. `follows`

User-to-User following relationship.

- **Primary Key:** `id`
- **Foreign Keys:**
  - `follower_id` -> `users(user_id)` (Cascade Delete)
  - `following_id` -> `users(user_id)` (Cascade Delete)
- **Constraints:** Unique combination of `follower_id` and `following_id`.

### 13. `author_follows`

User-to-Author following relationship.

- **Primary Key:** `id`
- **Foreign Keys:**
  - `user_id` -> `users(user_id)` (Cascade Delete)
  - `author_id` -> `authors(author_id)` (Cascade Delete)
- **Columns:**
  - `created_at`, `updated_at` (timestamps)
- **Constraints:** Unique combination of `user_id` and `author_id`.

### 14. `book_author`

Pivot table for Books and Authors (Many-to-Many).

- **Primary Key:** `id`
- **Foreign Keys:**
  - `book_id` -> `books(book_id)` (Cascade Delete)
  - `author_id` -> `authors(author_id)` (Cascade Delete)
- **Columns:**
  - `created_at`, `updated_at` (timestamps)

### 15. `book_ratings`

Stores user ratings for books.

- **Primary Key:** `rating_id`
- **Foreign Keys:**
  - `book_id` -> `books(book_id)` (Cascade Delete)
  - `user_id` -> `users(user_id)` (Cascade Delete)
- **Columns:**
  - `rating` (integer, unsigned): Rating value (1-5 stars).
  - `created_at`, `updated_at` (timestamps)
- **Constraints:** Unique combination of `book_id` and `user_id` (one rating per user per book).

### 16. `book_comments`

Stores user comments on books.

- **Primary Key:** `comment_id`
- **Foreign Keys:**
  - `book_id` -> `books(book_id)` (Cascade Delete)
  - `user_id` -> `users(user_id)` (Cascade Delete)
- **Columns:**
  - `comment` (text): User's comment on the book.
  - `created_at`, `updated_at` (timestamps)

### 17. `notifications`

Stores user notifications.

- **Primary Key:** `id`
- **Foreign Keys:**
  - `user_id` -> `users(user_id)` (Cascade Delete)
- **Columns:**
  - `type` (string): Notification type (e.g., 'read_reminder', 'explore_books', 'connect_people').
  - `title` (string): Notification title.
  - `message` (text): Notification message content.
  - `is_read` (boolean, default: false): Whether notification has been read.
  - `created_at`, `updated_at` (timestamps)

### System/Utility Tables

- **`personal_access_tokens`**: For Sanctum authentication (Polymorphic).
  - **Primary Key:** `id`
  - **Columns:**
    - `tokenable_type`, `tokenable_id` (polymorphic relation)
    - `name` (text): Token name
    - `token` (string, 64, unique): Hashed token
    - `abilities` (text, nullable): Token permissions
    - `last_used_at` (timestamp, nullable)
    - `expires_at` (timestamp, nullable, indexed)
    - `created_at`, `updated_at` (timestamps)

- **`sessions`**: Web session storage.
  - **Primary Key:** `id` (string)
  - **Columns:**
    - `user_id` (foreignId, nullable, indexed)
    - `ip_address` (string, 45, nullable)
    - `user_agent` (text, nullable)
    - `payload` (longText): Session data
    - `last_activity` (integer, indexed)

- **`cache`**: Key-value cache storage.
  - **Primary Key:** `key` (string)
  - **Columns:**
    - `value` (mediumText)
    - `expiration` (integer, indexed)

- **`cache_locks`**: Atomic lock storage.
  - **Primary Key:** `key` (string)
  - **Columns:**
    - `owner` (string)
    - `expiration` (integer, indexed)
