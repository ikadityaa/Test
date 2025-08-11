<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QrSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:user');
    }

    public function index()
    {
        $user = Auth::user();
        
        $enrolledCourses = $user->courses()->with('admin')->get();
        $recentAttendance = $user->attendanceRecords()
                                 ->with(['course', 'qrSession'])
                                 ->latest()
                                 ->take(5)
                                 ->get();

        $activeSessions = collect();
        foreach ($enrolledCourses as $course) {
            $activeSessions = $activeSessions->merge(
                $course->activeQrSessions()->where('is_active', true)->get()
            );
        }

        $stats = [
            'total_courses' => $enrolledCourses->count(),
            'total_attendance' => $user->attendanceRecords->count(),
            'active_sessions' => $activeSessions->count(),
        ];

        return view('student.dashboard', compact('enrolledCourses', 'recentAttendance', 'activeSessions', 'stats'));
    }

    public function courses()
    {
        $user = Auth::user();
        $enrolledCourses = $user->courses()->with('admin')->paginate(10);

        return view('student.courses.index', compact('enrolledCourses'));
    }

    public function courseDetails(Course $course)
    {
        $user = Auth::user();
        
        // Check if user is enrolled in this course
        if (!$user->courses->contains($course->id)) {
            abort(403, 'You are not enrolled in this course.');
        }

        $course->load(['admin', 'qrSessions', 'attendanceRecords']);
        
        $userAttendanceCount = $course->getStudentAttendanceCount($user->id);
        $canAttend = $course->canStudentAttend($user->id);
        
        $recentSessions = $course->qrSessions()->latest()->take(10)->get();
        $userAttendanceRecords = $course->attendanceRecords()
                                       ->where('user_id', $user->id)
                                       ->with('qrSession')
                                       ->latest()
                                       ->get();

        return view('student.courses.show', compact(
            'course', 
            'userAttendanceCount', 
            'canAttend', 
            'recentSessions', 
            'userAttendanceRecords'
        ));
    }

    public function attendance()
    {
        $user = Auth::user();
        
        $attendanceRecords = $user->attendanceRecords()
                                 ->with(['course', 'qrSession'])
                                 ->latest()
                                 ->paginate(15);

        $stats = [
            'total_attendance' => $user->attendanceRecords->count(),
            'total_courses' => $user->courses->count(),
        ];

        return view('student.attendance.index', compact('attendanceRecords', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'student_id' => 'nullable|string|max:50|unique:users,student_id,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['name', 'email', 'student_id', 'phone', 'address']));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update(['password' => bcrypt($request->password)]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    public function activeSessions()
    {
        $user = Auth::user();
        
        $activeSessions = collect();
        foreach ($user->courses as $course) {
            $activeSessions = $activeSessions->merge(
                $course->activeQrSessions()->where('is_active', true)->get()
            );
        }

        return view('student.active-sessions', compact('activeSessions'));
    }
}
