<?php

namespace App\Http\Controllers;

use App\Models\QrSession;
use App\Models\AttendanceRecord;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function generateQr($sessionId)
    {
        $qrSession = QrSession::with('course')->findOrFail($sessionId);
        
        // Check if user has permission to view this QR session
        if (!Auth::user()->isAdminRole() && !$qrSession->course->students->contains(Auth::id())) {
            abort(403, 'Unauthorized access.');
        }

        $qrCodeUrl = $qrSession->getQrCodeUrl();
        
        $qrCode = QrCode::size(300)
                       ->format('png')
                       ->generate($qrCodeUrl);

        return response()->json([
            'qr_code' => 'data:image/png;base64,' . base64_encode($qrCode),
            'session' => $qrSession,
            'url' => $qrCodeUrl
        ]);
    }

    public function showQrPage($sessionId)
    {
        $qrSession = QrSession::with('course')->findOrFail($sessionId);
        
        // Check if user has permission to view this QR session
        if (!Auth::user()->isAdminRole() && !$qrSession->course->students->contains(Auth::id())) {
            abort(403, 'Unauthorized access.');
        }

        return view('qr.show', compact('qrSession'));
    }

    public function checkIn(Request $request, $sessionCode)
    {
        $qrSession = QrSession::where('session_code', $sessionCode)
                             ->with('course')
                             ->first();

        if (!$qrSession) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR session.'
            ], 404);
        }

        if (!$qrSession->isCurrentlyActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This QR session is not currently active.'
            ], 400);
        }

        $user = Auth::user();

        // Check if user is enrolled in the course
        if (!$qrSession->course->students->contains($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this course.'
            ], 403);
        }

        // Check if user has already checked in for this session
        $existingRecord = AttendanceRecord::where('user_id', $user->id)
                                         ->where('qr_session_id', $qrSession->id)
                                         ->first();

        if ($existingRecord) {
            return response()->json([
                'success' => false,
                'message' => 'You have already checked in for this session.'
            ], 400);
        }

        // Check if user has reached max attendance limit
        if (!$qrSession->course->canStudentAttend($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the maximum attendance limit for this course.'
            ], 400);
        }

        // Create attendance record
        AttendanceRecord::create([
            'user_id' => $user->id,
            'course_id' => $qrSession->course_id,
            'qr_session_id' => $qrSession->id,
            'check_in_time' => now(),
            'check_in_method' => 'qr',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'course_name' => $qrSession->course->name,
            'session_title' => $qrSession->title,
            'check_in_time' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function showCheckInPage($sessionCode)
    {
        $qrSession = QrSession::where('session_code', $sessionCode)
                             ->with('course')
                             ->first();

        if (!$qrSession) {
            abort(404, 'QR session not found.');
        }

        if (!$qrSession->isCurrentlyActive()) {
            return view('qr.expired', compact('qrSession'));
        }

        return view('qr.checkin', compact('qrSession'));
    }

    public function getAttendanceStats($sessionId)
    {
        $qrSession = QrSession::with(['course', 'attendanceRecords.user'])->findOrFail($sessionId);
        
        // Check if user has permission
        if (!Auth::user()->isAdminRole() && !$qrSession->course->students->contains(Auth::id())) {
            abort(403, 'Unauthorized access.');
        }

        $stats = [
            'total_enrolled' => $qrSession->course->students->count(),
            'total_attended' => $qrSession->attendanceRecords->count(),
            'attendance_percentage' => $qrSession->course->students->count() > 0 
                ? round(($qrSession->attendanceRecords->count() / $qrSession->course->students->count()) * 100, 2)
                : 0,
            'attendance_list' => $qrSession->attendanceRecords->map(function($record) {
                return [
                    'student_name' => $record->user->name,
                    'student_id' => $record->user->student_id,
                    'check_in_time' => $record->check_in_time->format('Y-m-d H:i:s'),
                ];
            })
        ];

        return response()->json($stats);
    }
}
