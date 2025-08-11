<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,super_admin');
    }

    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = AttendanceRecord::with(['user', 'course', 'qrSession']);

        // Filter by course if admin (not super admin)
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            $adminCourses = Course::where('admin_id', $user->id)->pluck('id');
            $query->whereIn('course_id', $adminCourses);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $attendanceRecords = $query->orderBy('check_in_time', 'desc')->paginate(20);
        $courses = Course::when($user->isAdmin() && !$user->isSuperAdmin(), function ($q) use ($user) {
            return $q->where('admin_id', $user->id);
        })->get();

        return view('admin.attendance.index', compact('attendanceRecords', 'courses'));
    }

    /**
     * Export attendance records.
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = AttendanceRecord::with(['user', 'course', 'qrSession']);

        // Filter by course if admin (not super admin)
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            $adminCourses = Course::where('admin_id', $user->id)->pluck('id');
            $query->whereIn('course_id', $adminCourses);
        }

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $attendanceRecords = $query->orderBy('check_in_time', 'desc')->get();

        // Generate CSV
        $filename = 'attendance_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendanceRecords) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Student Name',
                'Student ID',
                'Course',
                'Session Title',
                'Check-in Time',
                'Check-in Method',
                'Notes'
            ]);

            // CSV data
            foreach ($attendanceRecords as $record) {
                fputcsv($file, [
                    $record->user->name,
                    $record->user->student_id,
                    $record->course->name,
                    $record->qrSession->title,
                    $record->check_in_time->format('Y-m-d H:i:s'),
                    $record->check_in_method,
                    $record->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
