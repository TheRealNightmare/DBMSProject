# Backend SQL Query Documentation

## 1. AuthController

### `register(Request $request)`

```sql
SELECT count(*) as aggregate FROM `users` WHERE `username` = ?;
SELECT count(*) as aggregate FROM `users` WHERE `email` = ?;

```

```sql
INSERT INTO `users` (`username`, `email`, `password`, `updated_at`, `created_at`)
VALUES (?, ?, ?, ?, ?);

```

```sql
INSERT INTO `personal_access_tokens` (`name`, `token`, `abilities`, `tokenable_id`, `tokenable_type`, `updated_at`, `created_at`)
VALUES (?, ?, ?, ?, ?, ?, ?);

```

### `login(Request $request)`

```sql
SELECT * FROM `users` WHERE `email` = ? LIMIT 1;

```

```sql
INSERT INTO `personal_access_tokens` (`name`, `token`, `abilities`, `tokenable_id`, `tokenable_type`, `updated_at`, `created_at`)
VALUES (?, ?, ?, ?, ?, ?, ?);

```

### `logout(Request $request)`

```sql
DELETE FROM `personal_access_tokens` WHERE `id` = ?;

```

---

## 2. AuthorController

### `toggleFollow($id)`

```sql
SELECT * FROM `authors` WHERE `author_id` = ? LIMIT 1;

```

```sql
SELECT EXISTS(
    SELECT * FROM `author_follows`
    WHERE `user_id` = ? AND `author_id` = ?
) as `exists`;

```

```sql
DELETE FROM `author_follows` WHERE `user_id` = ? AND `author_id` = ?;

```

```sql
INSERT INTO `author_follows` (`user_id`, `author_id`) VALUES (?, ?);

```

---

## 3. BookController

### `index(Request $request)`

```sql
SELECT * FROM `books`
ORDER BY `created_at` DESC
ORDER BY `rating_avg` DESC
WHERE `category` = ?
WHERE (`title` LIKE ? OR EXISTS (
    SELECT * FROM `authors`
    INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
    WHERE `books`.`book_id` = `book_authors`.`book_id`
    AND `name` LIKE ?
))
LIMIT ?;

```

```sql
SELECT `authors`.*, `book_authors`.`book_id` as `pivot_book_id`, `book_authors`.`author_id` as `pivot_author_id`
FROM `authors`
INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
WHERE `book_authors`.`book_id` IN (?, ?, ...);

```

### `show($id)`

```sql
SELECT * FROM `books` WHERE `book_id` = ? LIMIT 1;

```

```sql
SELECT * FROM `authors`
INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
WHERE `book_authors`.`book_id` = ?;

```

```sql
SELECT EXISTS(
    SELECT * FROM `author_follows`
    WHERE `user_id` = ? AND `author_id` = ?
);

```

### `getContent(Request $request, $bookId, $chapterIndex)`

```sql
SELECT * FROM `shelves` WHERE `user_id` = ? AND `is_system_default` = 1 LIMIT 1;
INSERT INTO `shelves` (`user_id`, `is_system_default`, `name`, `privacy_level`) VALUES (?, ?, ?, ?);

```

```sql
SELECT * FROM `shelf_items` WHERE `shelf_id` = ? AND `book_id` = ? LIMIT 1;
INSERT INTO `shelf_items` (`shelf_id`, `book_id`, `status`, `added_at`) VALUES (?, ?, ?, ?);

```

```sql
SELECT * FROM `chapters` WHERE `book_id` = ? AND `order_index` = ? LIMIT 1;

```

```sql
SELECT * FROM `annotations` WHERE `book_id` = ? AND `chapter_id` = ? AND `user_id` = ?;

```

```sql
SELECT count(*) as aggregate FROM `chapters` WHERE `book_id` = ?;

```

### `storeAnnotation(Request $request, $bookId)`

```sql
INSERT INTO `annotations` (`chapter_id`, `note`, `highlighted_text`, `color`, `user_id`, `book_id`, `created_at`, `updated_at`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?);

```

### `getHistory(Request $request)`

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

```sql
SELECT * FROM `groups`;

```

### `getMessages($groupId)`

```sql
SELECT * FROM `groups` WHERE `group_id` = ? LIMIT 1;

```

```sql
SELECT * FROM `group_messages` WHERE `group_id` = ? ORDER BY `sent_at` ASC;

```

```sql
SELECT `user_id`, `username`, `profile_image` FROM `users` WHERE `user_id` IN (?, ?, ...);

```

### `sendMessage(Request $request, $groupId)`

```sql
SELECT * FROM `groups` WHERE `group_id` = ? LIMIT 1;

```

```sql
INSERT INTO `group_messages` (`group_id`, `sender_id`, `message_body`, `sent_at`, `updated_at`, `created_at`)
VALUES (?, ?, ?, ?, ?, ?);

```

---

## 5. DashboardController

### `stats(Request $request)`

```sql
SELECT count(*) as aggregate
FROM `shelf_items`
INNER JOIN `shelves` ON `shelves`.`shelf_id` = `shelf_items`.`shelf_id`
WHERE `shelves`.`user_id` = ? AND `status` = 'Read';

```

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

```sql
SELECT * FROM `events` ORDER BY `start_time` ASC;

```

### `store(Request $request)`

```sql
INSERT INTO `events` (`title`, `host_name`, `category`, `description`, `start_time`, `end_time`, `updated_at`, `created_at`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?);

```

---

## 7. ProfileController

### `show(Request $request)`

```sql
SELECT count(*) FROM `user_follows` WHERE `following_id` = ?;
SELECT count(*) FROM `user_follows` WHERE `follower_id` = ?;

```

### `update(Request $request)`

```sql
SELECT count(*) FROM `users` WHERE `username` = ? AND `user_id` != ?;
SELECT count(*) FROM `users` WHERE `email` = ? AND `user_id` != ?;

```

```sql
UPDATE `users` SET `username` = ?, `email` = ?, `bio` = ?, `profile_image` = ?, `updated_at` = ?
WHERE `user_id` = ?;

```

---

## 8. UserController

### `search(Request $request)`

```sql
SELECT * FROM `users`
WHERE `username` LIKE ?
AND `user_id` != ?
LIMIT 10;

```

```sql
SELECT count(*) FROM `user_follows` WHERE `following_id` = users.user_id;
SELECT count(*) FROM `user_follows` WHERE `follower_id` = users.user_id;

```

```sql
SELECT EXISTS(
    SELECT * FROM `user_follows`
    WHERE `follower_id` = ? AND `following_id` = ?
);

```

### `show($id)`

```sql
SELECT * FROM `users` WHERE `user_id` = ? LIMIT 1;

```

```sql
SELECT EXISTS(
    SELECT * FROM `user_follows`
    WHERE `follower_id` = ? AND `following_id` = ?
);

```

### `follow(Request $request, $id)`

```sql
SELECT * FROM `users` WHERE `user_id` = ? LIMIT 1;

```

```sql
SELECT EXISTS(
    SELECT * FROM `user_follows`
    WHERE `follower_id` = ? AND `following_id` = ?
);

```

```sql
INSERT INTO `user_follows` (`follower_id`, `following_id`) VALUES (?, ?);

```

### `unfollow(Request $request, $id)`

```sql
DELETE FROM `user_follows` WHERE `follower_id` = ? AND `following_id` = ?;

```

---

## 9. NotificationController

### `index(Request $request)`

```sql
SELECT * FROM `notifications`
WHERE `user_id` = ?
ORDER BY `created_at` DESC
LIMIT 50;

```

```sql
SELECT count(*) as aggregate FROM `notifications`
WHERE `user_id` = ? AND `is_read` = 0;

```

### `markAsRead($id)`

```sql
SELECT * FROM `notifications`
WHERE `id` = ? AND `user_id` = ?
LIMIT 1;

```

```sql
UPDATE `notifications`
SET `is_read` = ?, `updated_at` = ?
WHERE `id` = ?;

```

### `markAllAsRead()`

```sql
UPDATE `notifications`
SET `is_read` = ?, `updated_at` = ?
WHERE `user_id` = ? AND `is_read` = 0;

```

### `sendScheduledNotifications()`

```sql
SELECT * FROM `users`;

```

```sql
INSERT INTO `notifications` (`user_id`, `type`, `title`, `message`, `created_at`, `updated_at`)
VALUES (?, ?, ?, ?, ?, ?);

```

---

## 10. SearchController

### `search(Request $request)`

```sql
SELECT * FROM `users`
WHERE `username` LIKE ?
AND `user_id` != ?
LIMIT 5;

```

```sql
SELECT count(*) FROM `user_follows` WHERE `following_id` = users.user_id;
SELECT count(*) FROM `user_follows` WHERE `follower_id` = users.user_id;

```

```sql
SELECT EXISTS(
    SELECT * FROM `user_follows`
    WHERE `follower_id` = ? AND `following_id` = ?
);

```

```sql
SELECT * FROM `books`
WHERE `title` LIKE ?
OR EXISTS (
    SELECT * FROM `authors`
    INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
    WHERE `books`.`book_id` = `book_authors`.`book_id`
    AND `name` LIKE ?
)
LIMIT 5;

```

```sql
SELECT `authors`.*, `book_authors`.`book_id` as `pivot_book_id`, `book_authors`.`author_id` as `pivot_author_id`
FROM `authors`
INNER JOIN `book_authors` ON `authors`.`author_id` = `book_authors`.`author_id`
WHERE `book_authors`.`book_id` IN (?, ?, ...);

```
