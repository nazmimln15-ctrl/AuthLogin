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
        $token = session('api_token');
        if (! $token && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('frontend')->plainTextToken;
            session(['api_token' => $token]);
        }
        $resp = Http::withToken($token)->get(url('/api/admin/sessions'));
        $sessions = $resp->successful() ? $resp->json('data') : collect();
        return view('dosen.absensi', compact('sessions'));
    }

    // Show all sessions created by the authenticated dosen (QR list)
    public function sessionList()
    {
        $userId = Auth::id();
        $token = session('api_token');
        $resp = Http::withToken($token)->get(url('/api/admin/sessions'), ['instructor_id' => $userId]);
        $sessions = $resp->successful() ? $resp->json('data') : collect();
        return view('dosen.session_list', ['sessions' => $sessions]);
    }

    // Show attendance list for a course
    public function show($sessionId)
    {
        $token = session('api_token');
        $resp = Http::withToken($token)->get(url('/api/admin/sessions/' . $sessionId . '/attendances'));
        if (! $resp->successful()) abort(404);
        $data = $resp->json('data');
        $session = $data['session'] ?? null;
        $rows = $data['rows'] ?? [];
        return view('dosen.absensi_list', ['session' => (object) $session, 'rows' => $rows]);
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
