<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InHouseClientsExport implements FromCollection, WithHeadings
{
    protected $clients;

    public function __construct($clients)
    {
        $this->clients = $clients;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Sort the clients alphabetically by last name, then first name (same as report view)
        $sortedClients = $this->clients->sortBy([
            ['clientLastName', 'asc'],
            ['clientFirstName', 'asc'],
        ])->values();

        return $sortedClients->map(function ($client) {
            return [
                'Client Name' => $client->clientLastName . ', ' . $client->clientFirstName,
                'Gender' => $client->gender->gender_name ?? 'Not specified',
                'Age' => $client->clientBirthdate ? \Carbon\Carbon::parse($client->clientBirthdate)->age : 'Unknown',
                'Case' => $client->case->case_name ?? 'No Case',
                'Student' => ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No',
                'Admission Date' => $client->clientdateofadmission,
                'PWD Status' => ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No',
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Client Name',
            'Gender', 
            'Age',
            'Case',
            'Student',
            'Admission Date',
            'PWD Status'
        ];
    }
}
