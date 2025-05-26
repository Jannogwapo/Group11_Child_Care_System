<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function report()
{
    // Adjust the condition to match your "in-house" logic (e.g., status = 'in_house')
    $inHouseClients = Client::where('location_id', 1)->get();

    return view('admin.report', compact('inHouseClients'));
}

public function downloadInHouse()
{
    $inHouseClients = Client::where('location_id', 1)->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="In House Clients.csv"',
    ];

    $callback = function() use ($inHouseClients) {
        $handle = fopen('php://output', 'w');
        // CSV header
        fputcsv($handle, ['Client Name', 'Admission Date']);
        // CSV rows
        foreach ($inHouseClients as $client) {
            fputcsv($handle, [
                $client->clientLastName . ', ' . $client->clientFirstName,
                $client->clientdateofadmission,
            ]);
        }
        fclose($handle);
    };

    return Response::stream($callback, 200, $headers);
}
}



