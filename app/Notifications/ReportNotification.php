<?php

namespace App\Notifications;

use App\Models\Client;

class ReportNotification extends CaseNotification
{
    public function __construct(Client $client, $reportType, $type = 'report')
    {
        $title = 'New Case Report';
        $message = "A new {$reportType} report has been submitted for {$client->name}";

        parent::__construct($title, $message, $type, [
            'client_id' => $client->id,
            'report_type' => $reportType
        ]);
    }
}
 