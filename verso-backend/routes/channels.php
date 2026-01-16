<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

// Allow any authenticated user to join community channels
Broadcast::channel('community.{groupId}', function (User $user, $groupId) {
    return true; // Add logic here if you want to restrict to group members only
});