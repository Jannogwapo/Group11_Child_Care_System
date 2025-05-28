<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    

    public function report()
    {
        if (!Gate::allows('It')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        $inHouseClients = Client::where('location_id', 1)->get();
        return view('admin.report', compact('inHouseClients'));
    }

    public function downloadInHouse(Request $request)
    {
        $format = $request->input('format', 'csv');
        $inHouseClients = Client::where('location_id', 1)->get();

        // CSV Export
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="In House Clients.csv"',
            ];
            $callback = function() use ($inHouseClients) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Client Name', 'Gender', 'Age', 'Case', 'Student', 'Pwd', 'Admission Date']);
                foreach ($inHouseClients as $client) {
                    $age = $client->clientBirthdate ? \Carbon\Carbon::parse($client->clientBirthdate)->age : 'Unknown';
                    $gender = isset($client->gender) && isset($client->gender->gender_name)
                        ? $client->gender->gender_name
                        : (($client->clientgender == 1 || strtolower($client->clientgender) == 'male') ? 'Male'
                        : (($client->clientgender == 2 || strtolower($client->clientgender) == 'female') ? 'Female' : 'Not specified'));
                    $student = ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No';
                    $pwd = ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No';
                    fputcsv($handle, [
                        $client->clientLastName . ', ' . $client->clientFirstName,
                        $gender,
                        $age,
                        $client->case->case_name ?? 'No Case',
                        $student,
                        $pwd,
                        $client->clientdateofadmission,
                    ]);
                }
                fclose($handle);
            };
            return Response::stream($callback, 200, $headers);
        }

        // Excel Export (HTML table, will open in Excel)
        if ($format === 'excel') {
            $html = '<h2>In House Clients Report</h2>
            <table border="1" cellpadding="5" cellspacing="0">
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
                <tbody>';
            foreach ($inHouseClients as $client) {
                $age = $client->clientBirthdate ? \Carbon\Carbon::parse($client->clientBirthdate)->age : 'Unknown';
                $gender = isset($client->gender) && isset($client->gender->gender_name)
                    ? $client->gender->gender_name
                    : (($client->clientgender == 1 || strtolower($client->clientgender) == 'male') ? 'Male'
                    : (($client->clientgender == 2 || strtolower($client->clientgender) == 'female') ? 'Female' : 'Not specified'));
                $student = ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No';
                $pwd = ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No';

                $html .= '<tr>
                    <td>' . $client->clientLastName . ', ' . $client->clientFirstName . '</td>
                    <td>' . $gender . '</td>
                    <td>' . $age . '</td>
                    <td>' . ($client->case->case_name ?? 'No Case') . '</td>
                    <td>' . $student . '</td>
                    <td>' . $pwd . '</td>
                    <td>' . $client->clientdateofadmission . '</td>
                </tr>';
            }
            $html .= '</tbody></table>';

            // Set the correct headers for Excel to open the HTML as a spreadsheet
            return response($html, 200)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="In House Clients.xls"');
        }

        // PDF Export
        if ($format === 'pdf') {
            $html = '<h2>In House Clients Report</h2>
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
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
                <tbody>';
            foreach ($inHouseClients as $client) {
                $age = $client->clientBirthdate ? \Carbon\Carbon::parse($client->clientBirthdate)->age : 'Unknown';
                $gender = isset($client->gender) && isset($client->gender->gender_name)
                    ? $client->gender->gender_name
                    : (($client->clientgender == 1 || strtolower($client->clientgender) == 'male') ? 'Male'
                    : (($client->clientgender == 2 || strtolower($client->clientgender) == 'female') ? 'Female' : 'Not specified'));
                $student = ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No';
                $pwd = ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No';

                $html .= '<tr>
                    <td>' . $client->clientLastName . ', ' . $client->clientFirstName . '</td>
                    <td>' . $gender . '</td>
                    <td>' . $age . '</td>
                    <td>' . ($client->case->case_name ?? 'No Case') . '</td>
                    <td>' . $student . '</td>
                    <td>' . $pwd . '</td>
                    <td>' . $client->clientdateofadmission . '</td>
                </tr>';
            }
            $html .= '</tbody></table>';

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output('In House Clients.pdf', 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="In House Clients.pdf"');
        }

        // Word Export
        if ($format === 'word') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '999999',
                'width' => 100 * 50,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT
            ]);
            $headers = ['Client Name', 'Gender', 'Age', 'Case', 'Student', 'Pwd', 'Admission Date'];
            $table->addRow();
            foreach ($headers as $header) {
                $table->addCell()->addText($header, ['bold' => true]);
            }
            foreach ($inHouseClients as $client) {
                $age = $client->clientBirthdate ? \Carbon\Carbon::parse($client->clientBirthdate)->age : 'Unknown';
                $gender = isset($client->gender) && isset($client->gender->gender_name)
                    ? $client->gender->gender_name
                    : (($client->clientgender == 1 || strtolower($client->clientgender) == 'male') ? 'Male'
                    : (($client->clientgender == 2 || strtolower($client->clientgender) == 'female') ? 'Female' : 'Not specified'));
                $student = ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No';
                $pwd = ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No';
                $table->addRow();
                $table->addCell()->addText($client->clientLastName . ', ' . $client->clientFirstName);
                $table->addCell()->addText($gender);
                $table->addCell()->addText($age);
                $table->addCell()->addText($client->case->case_name ?? 'No Case');
                $table->addCell()->addText($student);
                $table->addCell()->addText($pwd);
                $table->addCell()->addText($client->clientdateofadmission);
            }
            $tempFile = tempnam(sys_get_temp_dir(), 'word');
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);
            return response()->download($tempFile, 'In House Clients.docx')->deleteFileAfterSend(true);
        }

        // Default: fallback to CSV
        return redirect()->back()->with('error', 'Invalid format selected');
    }
}