<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Role;
use App\Models\QrSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,super_admin');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $courses = Course::with(['admin', 'students'])->latest()->paginate(10);
        } else {
            $courses = $user->administeredCourses()->with(['admin', 'students'])->latest()->paginate(10);
        }

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $admins = User::whereHas('role', function($query) {
            $query->whereIn('name', ['admin', 'super_admin']);
        })->get();

        return view('admin.courses.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:50|unique:courses',
            'max_attendance' => 'nullable|integer|min:0',
            'admin_id' => 'required|exists:users,id',
        ]);

        $course = Course::create($request->all());

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $this->checkCourseAccess($course);

        $course->load(['admin', 'students', 'qrSessions', 'attendanceRecords.user']);
        
        $recentSessions = $course->qrSessions()->latest()->take(5)->get();
        $recentAttendance = $course->attendanceRecords()->with('user')->latest()->take(10)->get();

        return view('admin.courses.show', compact('course', 'recentSessions', 'recentAttendance'));
    }

    public function edit(Course $course)
    {
        $this->checkCourseAccess($course);

        $admins = User::whereHas('role', function($query) {
            $query->whereIn('name', ['admin', 'super_admin']);
        })->get();

        return view('admin.courses.edit', compact('course', 'admins'));
    }

    public function update(Request $request, Course $course)
    {
        $this->checkCourseAccess($course);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'max_attendance' => 'nullable|integer|min:0',
            'admin_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $this->checkCourseAccess($course);

        $course->delete();

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course deleted successfully.');
    }

    public function students(Course $course)
    {
        $this->checkCourseAccess($course);

        $enrolledStudents = $course->students()->paginate(15);
        $availableStudents = User::whereHas('role', function($query) {
            $query->where('name', 'user');
        })->whereDoesntHave('courses', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })->get();

        return view('admin.courses.students', compact('course', 'enrolledStudents', 'availableStudents'));
    }

    public function enrollStudent(Request $request, Course $course)
    {
        $this->checkCourseAccess($course);

        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::find($request->student_id);
        
        if ($student->role->name !== 'user') {
            return back()->with('error', 'Only students can be enrolled in courses.');
        }

        if ($course->students->contains($student->id)) {
            return back()->with('error', 'Student is already enrolled in this course.');
        }

        $course->students()->attach($student->id, [
            'enrollment_date' => now(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Student enrolled successfully.');
    }

    public function removeStudent(Request $request, Course $course)
    {
        $this->checkCourseAccess($course);

        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $course->students()->detach($request->student_id);

        return back()->with('success', 'Student removed from course successfully.');
    }

    public function qrSessions(Course $course)
    {
        $this->checkCourseAccess($course);

        $qrSessions = $course->qrSessions()->with('creator')->latest()->paginate(10);

        return view('admin.courses.qr-sessions', compact('course', 'qrSessions'));
    }

    public function sendReminder(Request $request, Course $course, WhatsAppService $whatsapp)
    {
        $this->checkCourseAccess($course);

        $message = $request->input('message', 'Reminder: QR attendance session is starting soon.');

        $sent = 0;
        foreach ($course->students as $student) {
            if (!empty($student->phone)) {
                $sent += $whatsapp->sendMessage($this->normalizePhone($student->phone), $message) ? 1 : 0;
            }
        }

        return back()->with('success', "Sent reminders to {$sent} students.");
    }

    public function sendSessionSummary(Request $request, Course $course, QrSession $session, WhatsAppService $whatsapp)
    {
        $this->checkCourseAccess($course);

        if ($session->course_id !== $course->id) {
            abort(404);
        }

        $count = $session->attendanceRecords()->count();
        $msg = sprintf('Attendance summary for %s - %s: %d check-ins.', $course->name, $session->title, $count);

        $recipients = $whatsapp->getSummaryRecipients();
        $sent = 0;
        foreach ($recipients as $phone) {
            $sent += $whatsapp->sendMessage($phone, $msg) ? 1 : 0;
        }

        return back()->with('success', "Summary sent to {$sent} recipients.");
    }

    private function normalizePhone(string $raw): string
    {
        $digits = preg_replace('/[^0-9]/', '', $raw) ?? '';
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (!str_starts_with($digits, '+')) {
            $digits = '+' . $digits;
        }
        return $digits;
    }

    public function createQrSession(Course $course)
    {
        $this->checkCourseAccess($course);

        return view('admin.courses.create-qr-session', compact('course'));
    }

    public function storeQrSession(Request $request, Course $course)
    {
        $this->checkCourseAccess($course);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $qrSession = QrSession::create([
            'course_id' => $course->id,
            'created_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => true,
        ]);

        return redirect()->route('admin.courses.qr-sessions', $course)
                        ->with('success', 'QR Session created successfully.');
    }

    public function attendance(Course $course)
    {
        $this->checkCourseAccess($course);

        $attendanceRecords = $course->attendanceRecords()
                                   ->with(['user', 'qrSession'])
                                   ->latest()
                                   ->paginate(15);

        $stats = [
            'total_students' => $course->students->count(),
            'total_attendance' => $course->attendanceRecords->count(),
            'average_attendance' => $course->students->count() > 0 
                ? round($course->attendanceRecords->count() / $course->students->count(), 2)
                : 0,
        ];

        return view('admin.courses.attendance', compact('course', 'attendanceRecords', 'stats'));
    }

    private function checkCourseAccess(Course $course)
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && $course->admin_id !== $user->id) {
            abort(403, 'You do not have permission to access this course.');
        }
    }
}
