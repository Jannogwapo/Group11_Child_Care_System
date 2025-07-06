<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InHouseClientsExport;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function report(Request $request)
    {

        if (!Gate::allows('isAdmin')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }


        // Get the "as of" filter from the request, default to current month

        $asOf = $request->input('as_of', now()->format('Y-m'));
        [$year, $month] = explode('-', $asOf);

        // Get the end date of the selected month
        $endOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');

        // Get gender filter from request
        $gender = $request->input('gender');

        // Build query for clients admitted on or before the end of the selected month
        $query = Client::where('location_id', 1)
            ->whereDate('clientdateofadmission', '<=', $endOfMonth);

        // Apply gender filter if specified
        if ($gender === 'male') {
            $query->where(function($q) {
                $q->where('clientgender', 1)
                  ->orWhereRaw('LOWER(clientgender) = ?', ['male']);
            });
        } elseif ($gender === 'female') {
            $query->where(function($q) {
                $q->where('clientgender', 2)
                  ->orWhereRaw('LOWER(clientgender) = ?', ['female']);
            });
        }

        $inHouseClients = $query->get();

        return view('admin.report', compact('inHouseClients', 'asOf', 'gender'));
    }


    /**
     * Download the in-house clients report for the selected month, with proper filtering and format.
     */
    public function downloadInHouse(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $format = $request->input('format', 'excel');
        $asOf = $request->input('as_of', now()->format('Y-m'));
        [$year, $month] = explode('-', $asOf);
        $gender = $request->input('gender');

        $endOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');

        $query = \App\Models\Client::where('location_id', 1)
            ->whereDate('clientdateofadmission', '<=', $endOfMonth);

        if ($gender === 'male') {
            $query->where(function($q) {
                $q->where('clientgender', 1)
                  ->orWhereRaw('LOWER(clientgender) = ?', ['male']);
            });
        } elseif ($gender === 'female') {
            $query->where(function($q) {
                $q->where('clientgender', 2)
                  ->orWhereRaw('LOWER(clientgender) = ?', ['female']);
            });
        }
        $inHouseClients = $query->get();

        // Excel Export
        if ($format === 'excel') {
            return Excel::download(new InHouseClientsExport($inHouseClients), 'in_house_clients.xlsx');
        }

        // PDF Export
        if ($format === 'pdf') {
            $html = view('admin.exports.in_house_clients', ['clients' => $inHouseClients])->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);

            // Output as a real PDF file
            return response($mpdf->Output('in_house_clients.pdf', 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename=\"in_house_clients.pdf\"');
        }

        // Word Export
        if ($format === 'word') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
            $headers = ['Client Name', 'Gender', 'Age', 'Case', 'Student', 'Pwd', 'Admission Date'];
            $table->addRow();
            foreach ($headers as $header) {
                $table->addCell(2000)->addText($header, ['bold' => true]);
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
                $table->addCell(2000)->addText($client->clientLastName . ', ' . $client->clientFirstName);
                $table->addCell(2000)->addText($gender);
                $table->addCell(2000)->addText($age);
                $table->addCell(2000)->addText($client->case->case_name ?? 'No Case');
                $table->addCell(2000)->addText($student);
                $table->addCell(2000)->addText($pwd);
                $table->addCell(2000)->addText($client->clientdateofadmission);
            }
            $tempFile = tempnam(sys_get_temp_dir(), 'word');
            $phpWord->save($tempFile, 'Word2007');
            return response()->download($tempFile, 'in_house_clients.docx')->deleteFileAfterSend(true);
        }

        // Default fallback
        return back()->with('error', 'Invalid format selected.');
    }
}