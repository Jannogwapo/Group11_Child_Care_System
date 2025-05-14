<?php

namespace App\Traits;

use App\Notifications\CaseNotification;

trait Notifiable
{
    public function notifyCaseUpdate($title, $message, $type, $data = [])
    {
        // Notify the assigned user
        if ($this->user) {
            $this->user->notify(new CaseNotification($title, $message, $type, $data));
        }

        // Notify admins
        $admins = \App\Models\User::where('role_id', 1)->get(); // Assuming role_id 1 is admin
        foreach ($admins as $admin) {
            $admin->notify(new CaseNotification($title, $message, $type, $data));
        }
    }

    public function notifyHearingUpdate($title, $message, $type, $data = [])
    {
        // Notify the assigned user
        if ($this->user) {
            $this->user->notify(new CaseNotification($title, $message, $type, $data));
        }

        // Notify the judge
        if ($this->judge) {
            $this->judge->notify(new CaseNotification($title, $message, $type, $data));
        }

        // Notify admins
        $admins = \App\Models\User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new CaseNotification($title, $message, $type, $data));
        }
    }
}
