<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    // Show list of courses for dosen (simple implementation)
    public function index()
    {
        // Views fetch sessions via axios on the client side now.
        return view('dosen.absensi');
    }

    // Show all sessions created by the authenticated dosen (QR list)
    public function sessionList()
    {
        return view('dosen.session_list');
    }

    // Show attendance list for a course
    public function show($sessionId)
    {
        // Client will fetch attendance list via axios.
        return view('dosen.absensi_list');
    }

    // Export to xlsx
    public function exportXlsx($sessionId)
    {
        $token = session('api_token');
        $resp = Http::withToken($token)->get(url('/api/admin/sessions/' . $sessionId . '/attendances'));
        if (! $resp->successful()) abort(404);
        $data = $resp->json('data');
        $rows = $data['rows'] ?? [];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Waktu');

        $r = 2;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $r, $row['nim'] ?? '');
            $sheet->setCellValue('B' . $r, $row['name'] ?? '');
            $sheet->setCellValue('C' . $r, $row['attended_at'] ?? '');
            $r++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'absensi_session_' . $sessionId . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        exit;
    }
}
