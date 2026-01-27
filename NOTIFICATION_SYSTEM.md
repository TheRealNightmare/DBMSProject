# Verso Notification System Documentation

## Overview
The Verso notification system provides scheduled notifications to encourage user engagement with reading, book discovery, and community features.

## Features

### Notification Types
1. **Read Reminder** 📚
   - Type: `read_reminder`
   - Message: Encourages users to continue their reading journey
   - Icon: BookOpen (blue background)

2. **Explore Books** 🔍
   - Type: `explore_books`
   - Message: Prompts users to discover new books
   - Icon: Search (green background)

3. **Connect with People** 👥
   - Type: `connect_people`
   - Message: Encourages joining community channels
   - Icon: Users (purple background)

## Backend Implementation

### Database Schema
**Table: `notifications`**
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key to users.user_id)
- type (string) - notification type identifier
- title (string) - notification heading
- message (text) - notification content
- is_read (boolean, default: false) - read status
- created_at (timestamp)
- updated_at (timestamp)
```

### API Endpoints

#### Get Notifications
```
GET /api/notifications
Authorization: Bearer {token}

Response:
{
  "notifications": [...],
  "unread_count": 3
}
```

#### Mark Notification as Read
```
POST /api/notifications/{id}/read
Authorization: Bearer {token}

Response:
{
  "message": "Notification marked as read",
  "notification": {...}
}
```

#### Mark All as Read
```
POST /api/notifications/read-all
Authorization: Bearer {token}

Response:
{
  "message": "All notifications marked as read"
}
```

#### Send Notifications (Admin/Testing)
```
POST /api/notifications/send
Authorization: Bearer {token}

Response:
{
  "message": "Notifications sent successfully",
  "sent_to_users": 2
}
```

### Console Commands

#### Send Notifications
Send scheduled notifications to all users via command line:

```bash
# Send random notifications to all users
php artisan notifications:send

# Send specific type of notification
php artisan notifications:send --type=read      # Read reminders
php artisan notifications:send --type=explore   # Explore books
php artisan notifications:send --type=connect   # Connect with people
php artisan notifications:send --type=all       # Random type (default)
```

**Command Features:**
- Progress bar showing notification delivery status
- Error handling for validation and database issues
- Success confirmation with count of sent notifications
- Type-specific filtering

### Models

**Notification Model** (`app/Models/Notification.php`)
- Fillable: `user_id`, `type`, `title`, `message`, `is_read`
- Relationship: `belongsTo(User::class)`
- Casts: `is_read` to boolean, timestamps to datetime

### Controller

**NotificationController** (`app/Http/Controllers/NotificationController.php`)

Methods:
- `index()` - Get all notifications for authenticated user
- `markAsRead($id)` - Mark single notification as read
- `markAllAsRead()` - Mark all user notifications as read
- `sendScheduledNotifications()` - Send notifications to all users (admin)

## Frontend Implementation

### Components

#### NotificationPanel Component
**Location:** `src/components/layout/NotificationPanel.vue`

**Features:**
- Bell icon with animated badge showing unread count
- Dropdown panel with notifications list
- Auto-refresh every 30 seconds
- Click-outside to close
- Color-coded icons based on notification type
- Time-ago formatting (e.g., "5m ago", "2h ago")
- Mark as read on click
- Mark all as read button
- Empty state with helpful message
- Test notification button

**States:**
- Loading state with spinner
- Empty state with bell icon
- Unread notifications highlighted with blue background
- Pulsing blue dot indicator for unread items
- Wiggle animation on bell icon when unread exists

#### Navbar Integration
The NotificationPanel component is integrated into the Navbar component, replacing the static bell icon.

### Styling

**Features:**
- Gradient backgrounds and colored icon containers
- Smooth transitions and hover effects
- Custom scrollbar styling
- Responsive design (max width 96 for panel)
- Pulse animation for unread badge
- Wiggle animation for bell icon

## Usage Examples

### Backend - Sending Notifications

**Manually trigger via console:**
```bash
# Production scheduled task (add to cron)
php artisan notifications:send --type=read

# Testing
php artisan notifications:send
```

**Programmatically from code:**
```php
use App\Models\Notification;

Notification::create([
    'user_id' => $user->user_id,
    'type' => 'read_reminder',
    'title' => '📚 Time to Read!',
    'message' => 'Don\'t forget to continue your reading journey today.',
]);
```

### Frontend - Using NotificationPanel

**Import and use in any component:**
```vue
<template>
  <NotificationPanel />
</template>

<script setup>
import NotificationPanel from '@/components/layout/NotificationPanel.vue';
</script>
```

**API calls:**
```javascript
import api from '@/services/api';

// Fetch notifications
const response = await api.get('/notifications');
const { notifications, unread_count } = response.data;

// Mark as read
await api.post(`/notifications/${notificationId}/read`);

// Mark all as read
await api.post('/notifications/read-all');

// Send test notification
await api.post('/notifications/send');
```

## Scheduling (Production Setup)

### Laravel Task Scheduler

Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Send daily reading reminders at 9 AM
    $schedule->command('notifications:send --type=read')
             ->dailyAt('09:00');
    
    // Send explore books every Monday at 10 AM
    $schedule->command('notifications:send --type=explore')
             ->weekly()
             ->mondays()
             ->at('10:00');
    
    // Send connect reminder every Friday at 6 PM
    $schedule->command('notifications:send --type=connect')
             ->weekly()
             ->fridays()
             ->at('18:00');
}
```

### Cron Setup
Add to server crontab:
```bash
* * * * * cd /path/to/verso-backend && php artisan schedule:run >> /dev/null 2>&1
```

## Testing

### Manual Testing Steps

1. **Backend:**
   ```bash
   # Send test notifications
   php artisan notifications:send
   
   # Verify in database
   php artisan tinker
   >>> Notification::all()
   >>> Notification::where('is_read', false)->count()
   ```

2. **Frontend:**
   - Login to application
   - Check bell icon has badge with count
   - Click bell to open panel
   - Verify notifications appear
   - Click notification to mark as read
   - Verify badge count decreases
   - Click "Mark all read" button
   - Verify all notifications marked

3. **API Testing (Postman/Curl):**
   ```bash
   # Get notifications
   curl -H "Authorization: Bearer {token}" \
        http://localhost:8000/api/notifications
   
   # Mark as read
   curl -X POST \
        -H "Authorization: Bearer {token}" \
        http://localhost:8000/api/notifications/1/read
   ```

## Customization

### Adding New Notification Types

1. **Backend - Add to command:**
   ```php
   // In SendScheduledNotifications.php
   $notificationTypes = [
       'new_type' => [
           'type' => 'new_type',
           'title' => '🎉 New Title',
           'message' => 'Your custom message here',
       ],
   ];
   ```

2. **Frontend - Add icon mapping:**
   ```vue
   <!-- In NotificationPanel.vue -->
   <YourIcon v-else-if="notification.type === 'new_type'" class="w-5 h-5" />
   ```

3. **Frontend - Add styling:**
   ```vue
   <div :class="{
     'bg-orange-100 text-orange-600': notification.type === 'new_type'
   }">
   ```

## Performance Considerations

- Notifications are limited to 50 per user (via API)
- Auto-refresh interval: 30 seconds (configurable)
- Click-outside handler properly cleaned up on unmount
- Efficient database indexing on `user_id` and `is_read`
- Timestamps enable time-based filtering

## Security

- All endpoints require authentication (`auth:sanctum`)
- Users can only access their own notifications
- Foreign key constraints ensure data integrity
- SQL injection protection via Eloquent ORM

## Future Enhancements

Potential improvements:
- Real-time notifications via WebSockets/Pusher
- Email/SMS notification delivery
- User notification preferences
- Notification categories/filtering
- Push notifications for mobile apps
- Notification history archive
- Advanced scheduling rules
- A/B testing for notification content
- Analytics dashboard for engagement tracking
