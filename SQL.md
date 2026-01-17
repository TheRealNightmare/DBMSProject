# Backend SQL Query Documentation

This document outlines the SQL queries executed by each method in the application's controllers.

---

## 1. AuthController

### `register(Request $request)`

Creates a new user and issues an access token.

- **Check Uniqueness:**
  ```sql
  SELECT count(*) as aggregate FROM `users` WHERE `username` = ?;
  SELECT count(*) as aggregate FROM `users` WHERE `email` = ?;
  ```
- **Create User:**
  ```sql
  INSERT INTO `users` (`username`, `email`, `password`, `updated_at`, `created_at`)
  VALUES (?, ?, ?, ?, ?);
  ```
- **Create Token (Sanctum):**
  ```sql
  INSERT INTO `personal_access_tokens` (`name`, `token`, `abilities`, `tokenable_id`, `tokenable_type`, `updated_at`, `created_at`)
  VALUES (?, ?, ?, ?, 'App\Models\User', ?, ?);
  ```

### `login(Request $request)`

Authenticates a user and issues a token.

- **Attempt Auth (Retrieve User by Email):**
  ```sql
  SELECT * FROM `users` WHERE `email` = ? LIMIT 1;
  ```
- **Create Token:**
  ```sql
  INSERT INTO `personal_access_tokens` (`name`, `token`, `abilities`, `tokenable_id`, `tokenable_type`, `updated_at`, `created_at`)
  VALUES (?, ?, ?, ?, 'App\Models\User', ?, ?);
  ```

### `logout(Request $request)`

Revokes the current user's access token.

- **Delete Token:**
  ```sql
  DELETE FROM `personal_access_tokens` WHERE `id` = ?;
  ```

---

## 2. AuthorController

### `toggleFollow($id)`

Follows or unfollows an author.

- **Find Author:**
  ```sql
  SELECT * FROM `authors` WHERE `author_id` = ? LIMIT 1;
  ```
- **Check Relationship (Exists):**
  ```sql
  SELECT EXISTS(
      SELECT * FROM `author_follows`
      WHERE `user_id` = ? AND `author_id` = ?
  ) as `exists`;
  ```
- **Unfollow (Detach):**
  ```sql
  DELETE FROM `author_follows` WHERE `user_id` = ? AND `author_id` = ?;
  ```
- **Follow (Attach):**
  ```sql
  INSERT INTO `author_follows` (`user_id`, `author_id`) VALUES (?, ?);
  ```

---

## 3. BookController

### `index(Request $request)`

Retrieves a list of books with filters.

- **Fetch Books (Base Query):**
  ```sql
  SELECT * FROM `books`
  -- If type='latest':
  ORDER BY `created_at` DESC
  -- If type='rated':
  ORDER BY `rating_avg` DESC
  -- If type='exclusive' or other category:
  WHERE `category` = ?
  -- If search (q) is provided:
  WHERE (`title` LIKE ? OR EXISTS (
      SELECT * FROM `authors`
      INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
      WHERE `books`.`book_id` = `book_authors`.`book_id`
      AND `name` LIKE ?
  ))
  LIMIT ?;
  ```
- **Eager Load Authors:**
  ```sql
  SELECT `authors`.*, `book_authors`.`book_id` as `pivot_book_id`, `book_authors`.`author_id` as `pivot_author_id`
  FROM `authors`
  INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
  WHERE `book_authors`.`book_id` IN (?, ?, ...);
  ```

### `show($id)`

Retrieves details for a specific book.

- **Fetch Book:**
  ```sql
  SELECT * FROM `books` WHERE `book_id` = ? LIMIT 1;
  ```
- **Fetch Authors (Eager Load):**
  ```sql
  SELECT * FROM `authors`
  INNER JOIN `book_authors` ON ...
  WHERE `book_authors`.`book_id` = ?;
  ```
- **Check if User Follows Authors (Iterated):**
  ```sql
  SELECT EXISTS(
      SELECT * FROM `author_follows`
      WHERE `user_id` = ? AND `author_id` = ?
  );
  ```

### `getContent(Request $request, $bookId, $chapterIndex)`

Retrieves chapter content and records history.

- **Find or Create Default Shelf:**
  ```sql
  -- Attempt to find:
  SELECT * FROM `shelves` WHERE `user_id` = ? AND `is_system_default` = 1 LIMIT 1;
  -- If not found, Insert:
  INSERT INTO `shelves` (`user_id`, `is_system_default`, `name`, `privacy_level`, ...) VALUES (...);
  ```
- **Find or Create Shelf Item (History):**
  ```sql
  -- Attempt to find:
  SELECT * FROM `shelf_items` WHERE `shelf_id` = ? AND `book_id` = ? LIMIT 1;
  -- If not found, Insert:
  INSERT INTO `shelf_items` (`shelf_id`, `book_id`, `status`, `added_at`, ...) VALUES (...);
  ```
- **Fetch Chapter:**
  ```sql
  SELECT * FROM `chapters` WHERE `book_id` = ? AND `order_index` = ? LIMIT 1;
  ```
- **Fetch Annotations:**
  ```sql
  SELECT * FROM `annotations` WHERE `book_id` = ? AND `chapter_id` = ? AND `user_id` = ?;
  ```
- **Count Chapters:**
  ```sql
  SELECT count(*) as aggregate FROM `chapters` WHERE `book_id` = ?;
  ```

### `storeAnnotation(Request $request, $bookId)`

Saves a user annotation.

- **Insert Annotation:**
  ```sql
  INSERT INTO `annotations` (`chapter_id`, `note`, `highlighted_text`, `color`, `user_id`, `book_id`, `created_at`, `updated_at`)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?);
  ```

### `getHistory(Request $request)`

Fetches books from the user's reading history.

- **Fetch Books in User Shelves:**
  ```sql
  SELECT * FROM `books`
  WHERE EXISTS (
      SELECT * FROM `shelf_items`
      INNER JOIN `shelves` ON `shelf_items`.`shelf_id` = `shelves`.`shelf_id`
      WHERE `books`.`book_id` = `shelf_items`.`book_id`
      AND `shelves`.`user_id` = ?
  )
  ORDER BY `updated_at` DESC;
  ```

### `removeFromHistory($id)`

Removes a book from the user's history/shelves.

- **Delete Shelf Item:**
  ```sql
  DELETE FROM `shelf_items`
  WHERE `book_id` = ?
  AND `shelf_id` IN (
      SELECT `shelf_id` FROM `shelves` WHERE `user_id` = ?
  );
  ```

---

## 4. CommunityController

### `getGroups()`

- **Fetch All Groups:**
  ```sql
  SELECT * FROM `groups`;
  ```

### `getMessages($groupId)`

- **Check Group Exists:**
  ```sql
  SELECT * FROM `groups` WHERE `group_id` = ? LIMIT 1;
  ```
- **Fetch Messages:**
  ```sql
  SELECT * FROM `group_messages` WHERE `group_id` = ? ORDER BY `sent_at` ASC;
  ```
- **Load Senders (Eager Load):**
  ```sql
  SELECT `user_id`, `username`, `profile_image` FROM `users` WHERE `user_id` IN (?, ?, ...);
  ```

### `sendMessage(Request $request, $groupId)`

- **Find Group:**
  ```sql
  SELECT * FROM `groups` WHERE `group_id` = ? LIMIT 1;
  ```
- **Insert Message:**
  ```sql
  INSERT INTO `group_messages` (`group_id`, `sender_id`, `message_body`, `sent_at`, `updated_at`, `created_at`)
  VALUES (?, ?, ?, ?, ?, ?);
  ```

---

## 5. DashboardController

### `stats(Request $request)`

Aggregates user reading statistics.

- **Count Completed Books:**
  ```sql
  SELECT count(*) as aggregate
  FROM `shelf_items`
  INNER JOIN `shelves` ON `shelves`.`shelf_id` = `shelf_items`.`shelf_id`
  WHERE `shelves`.`user_id` = ? AND `status` = 'Read';
  ```
- **Calculate Total Minutes Read:**
  ```sql
  SELECT SUM(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as aggregate
  FROM `reading_sessions`
  INNER JOIN `shelf_items` ON `shelf_items`.`item_id` = `reading_sessions`.`item_id`
  INNER JOIN `shelves` ON `shelves`.`shelf_id` = `shelf_items`.`shelf_id`
  WHERE `shelves`.`user_id` = ?;
  ```

---

## 6. EventController

### `index()`

- **Fetch Events:**
  ```sql
  SELECT * FROM `events` ORDER BY `start_time` ASC;
  ```

### `store(Request $request)`

- **Create Event:**
  ```sql
  INSERT INTO `events` (`title`, `host_name`, `category`, `description`, `start_time`, `end_time`, `updated_at`, `created_at`)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?);
  ```

---

## 7. ProfileController

### `show(Request $request)`

- **Load Follow Counts for Current User:**
  ```sql
  -- Count Followers
  SELECT count(*) FROM `user_follows` WHERE `following_id` = ?;
  -- Count Following
  SELECT count(*) FROM `user_follows` WHERE `follower_id` = ?;
  ```

### `update(Request $request)`

- **Check Uniqueness (Username/Email):**
  ```sql
  SELECT count(*) FROM `users` WHERE `username` = ? AND `user_id` != ?;
  SELECT count(*) FROM `users` WHERE `email` = ? AND `user_id` != ?;
  ```
- **Update User:**
  ```sql
  UPDATE `users` SET `username` = ?, `email` = ?, `bio` = ?, `profile_image` = ?, `updated_at` = ?
  WHERE `user_id` = ?;
  ```

---

## 8. UserController

### `search(Request $request)`

Searches for users.

- **Search Query:**
  ```sql
  SELECT * FROM `users`
  WHERE `username` LIKE ?
  AND `user_id` != ?
  LIMIT 10;
  ```
- **Load Counts (Followers/Following):**
  Executed as subqueries or separate aggregate queries depending on driver.
  ```sql
  SELECT count(*) FROM `user_follows` WHERE `following_id` = users.user_id;
  SELECT count(*) FROM `user_follows` WHERE `follower_id` = users.user_id;
  ```
- **Check Is Following (Iterated):**
  ```sql
  SELECT EXISTS(
      SELECT * FROM `user_follows`
      WHERE `follower_id` = ? AND `following_id` = ?
  );
  ```

### `show($id)`

Fetches a public profile.

- **Find User with Counts:**
  ```sql
  SELECT * FROM `users` WHERE `user_id` = ? LIMIT 1;
  -- + Follow counts queries (see above)
  ```
- **Check Relationship:**
  ```sql
  SELECT EXISTS(
      SELECT * FROM `user_follows`
      WHERE `follower_id` = ? AND `following_id` = ?
  );
  ```

### `follow(Request $request, $id)`

- **Find Target:**
  ```sql
  SELECT * FROM `users` WHERE `user_id` = ? LIMIT 1;
  ```
- **Check if Already Following:**
  ```sql
  SELECT EXISTS(
      SELECT * FROM `user_follows`
      WHERE `follower_id` = ? AND `following_id` = ?
  );
  ```
- **Insert Follow:**
  ```sql
  INSERT INTO `user_follows` (`follower_id`, `following_id`) VALUES (?, ?);
  ```

### `unfollow(Request $request, $id)`

- **Remove Follow:**
  ```sql
  DELETE FROM `user_follows` WHERE `follower_id` = ? AND `following_id` = ?;
  ```
