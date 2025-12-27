<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceController extends Controller
{
    // Show list of courses for dosen (simple implementation)
    public function index()
    {
        // List active attendance sessions / courses
        $sessions = DB::table('attendance_sessions')->select('id', 'course_id', 'token', 'starts_at')->get();
        return view('dosen.absensi', compact('sessions'));
    }

    // Show attendance list for a course
    public function show($sessionId)
    {
        $session = DB::table('attendance_sessions')->where('id', $sessionId)->first();
        if (! $session) {
            abort(404);
        }

        $rows = DB::table('attendances')
            ->where('attendance_session_id', $sessionId)
            ->leftJoin('users', 'attendances.user_id', '=', 'users.id')
            ->select('attendances.*', 'users.name', 'users.email')
            ->get();

        return view('dosen.absensi_list', ['session' => $session, 'rows' => $rows]);
    }

    // Export to xlsx
    public function exportXlsx($sessionId)
    {
        $session = DB::table('attendance_sessions')->where('id', $sessionId)->first();
        if (! $session) abort(404);

        $rows = DB::table('attendances')
            ->where('attendance_session_id', $sessionId)
            ->leftJoin('users', 'attendances.user_id', '=', 'users.id')
            ->select('users.id as nim', 'users.name', 'attendances.attended_at')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Waktu');

        $r = 2;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $r, $row->nim ?? '');
            $sheet->setCellValue('B' . $r, $row->name ?? '');
            $sheet->setCellValue('C' . $r, $row->attended_at ?? '');
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
