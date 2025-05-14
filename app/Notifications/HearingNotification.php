<?php

namespace App\Notifications;

use App\Models\Hearing;

class HearingNotification extends CaseNotification
{
    public function __construct(Hearing $hearing, $type = 'hearing')
    {
        $title = 'Hearing Update';
        $message = "Hearing scheduled for {$hearing->client->name} on " .
                  $hearing->date->format('F j, Y') .
                  " at {$hearing->time}";

        parent::__construct($title, $message, $type, [
            'hearing_id' => $hearing->id,
            'client_id' => $hearing->client_id,
            'date' => $hearing->date,
            'time' => $hearing->time
        ]);
    }
}
