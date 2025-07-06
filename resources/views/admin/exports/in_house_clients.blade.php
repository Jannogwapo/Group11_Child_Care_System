<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Case</th>
            <th>Student</th>
            <th>Pwd</th>
            <th>Admission Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->clientLastName }}, {{ $client->clientFirstName }}</td>
            <td>{{ $client->gender->gender_name ?? 'Not specified' }}</td>
            <td>
                @if($client->clientBirthdate)
                    {{ \Carbon\Carbon::parse($client->clientBirthdate)->age }}
                @else
                    Unknown
                @endif
            </td>
            <td>{{ $client->case->case_name ?? 'No Case' }}</td>
            <td>{{ ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No' }}</td>
            <td>{{ ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No' }}</td>
            <td>{{ \Carbon\Carbon::parse($client->clientdateofadmission)->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table> 