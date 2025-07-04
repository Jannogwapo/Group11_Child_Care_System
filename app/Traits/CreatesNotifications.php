<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\SystemNotification;

trait CreatesNotifications
{
    protected function createNotification(User $user, string $title, string $message, string $link = null, string $type = null)
    {
        $user->notify(new SystemNotification($title, $message, $link, $type));
    }

    protected function notifyAdmins(string $title, string $message, string $link = null, string $type = null)
    {
        $admins = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            $this->createNotification($admin, $title, $message, $link, $type);
        }
    }
}
