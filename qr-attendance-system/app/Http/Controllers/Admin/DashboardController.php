<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\QrSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,super_admin');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // Super admin sees all data
            $totalCourses = Course::count();
            $totalStudents = User::whereHas('role', function($query) {
                $query->where('name', 'user');
            })->count();
            $totalAdmins = User::whereHas('role', function($query) {
                $query->whereIn('name', ['admin', 'super_admin']);
            })->count();
            $totalAttendanceRecords = AttendanceRecord::count();
            
            $recentCourses = Course::with('admin')->latest()->take(5)->get();
            $recentSessions = QrSession::with('course')->latest()->take(5)->get();
            
        } else {
            // Regular admin sees only their courses
            $adminCourses = $user->administeredCourses;
            $totalCourses = $adminCourses->count();
            $totalStudents = $adminCourses->sum(function($course) {
                return $course->students->count();
            });
            $totalAttendanceRecords = $adminCourses->sum(function($course) {
                return $course->attendanceRecords->count();
            });
            
            $recentCourses = $adminCourses->take(5);
            $recentSessions = QrSession::whereIn('course_id', $adminCourses->pluck('id'))
                                     ->with('course')
                                     ->latest()
                                     ->take(5)
                                     ->get();
        }

        // Get attendance statistics for the last 7 days
        $attendanceStats = $this->getAttendanceStats($user);

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalStudents', 
            'totalAttendanceRecords',
            'recentCourses',
            'recentSessions',
            'attendanceStats'
        ));
    }

    private function getAttendanceStats($user)
    {
        $query = AttendanceRecord::query();
        
        if (!$user->isSuperAdmin()) {
            $courseIds = $user->administeredCourses->pluck('id');
            $query->whereIn('course_id', $courseIds);
        }

        $last7Days = $query->where('check_in_time', '>=', now()->subDays(7))
                           ->count();

        $last30Days = $query->where('check_in_time', '>=', now()->subDays(30))
                            ->count();

        return [
            'last_7_days' => $last7Days,
            'last_30_days' => $last30Days,
        ];
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address']));

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
}
