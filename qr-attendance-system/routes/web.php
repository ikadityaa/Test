<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Installation routes
Route::group(['prefix' => 'install', 'middleware' => 'web'], function () {
    Route::get('/', [App\Http\Controllers\Install\InstallController::class, 'index'])->name('install.index');
    Route::post('/', [App\Http\Controllers\Install\InstallController::class, 'index'])->name('install.process');
    Route::get('/requirements', [App\Http\Controllers\Install\InstallController::class, 'checkRequirements'])->name('install.requirements');
    Route::get('/delete', [App\Http\Controllers\Install\InstallController::class, 'deleteInstallDir'])->name('install.delete');
});

// Public routes (no authentication required)
Route::get('/', function () {
    // Check if system is installed
    if (file_exists(storage_path('installed'))) {
        // If user is authenticated, redirect based on role
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }
        // If not authenticated, show welcome page with login/register links
        return view('welcome');
    } else {
        // If not installed, redirect to installation
        return redirect()->route('install.index');
    }
})->name('home');

// Guest routes (no authentication required)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authentication routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Admin routes
    Route::middleware('role:admin,super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/change-password', [AdminDashboardController::class, 'changePassword'])->name('change-password');
        
        // Courses
        Route::resource('courses', AdminCourseController::class);
        Route::get('/courses/{course}/students', [AdminCourseController::class, 'students'])->name('courses.students');
        Route::post('/courses/{course}/enroll-student', [AdminCourseController::class, 'enrollStudent'])->name('courses.enroll-student');
        Route::post('/courses/{course}/remove-student', [AdminCourseController::class, 'removeStudent'])->name('courses.remove-student');
        Route::get('/courses/{course}/qr-sessions', [AdminCourseController::class, 'qrSessions'])->name('courses.qr-sessions');
        Route::get('/courses/{course}/qr-sessions/create', [AdminCourseController::class, 'createQrSession'])->name('courses.qr-sessions.create');
        Route::post('/courses/{course}/qr-sessions', [AdminCourseController::class, 'storeQrSession'])->name('courses.qr-sessions.store');
        Route::post('/courses/{course}/qr-sessions/{session}/send-summary', [AdminCourseController::class, 'sendSessionSummary'])->name('courses.qr-sessions.send-summary');
        Route::post('/courses/{course}/send-reminder', [AdminCourseController::class, 'sendReminder'])->name('courses.send-reminder');
        Route::get('/courses/{course}/attendance', [AdminCourseController::class, 'attendance'])->name('courses.attendance');
        
        // Users (Super Admin only)
        Route::middleware('role:super_admin')->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
            Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        });
        
        // Attendance
        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/export', [AdminAttendanceController::class, 'export'])->name('attendance.export');
    });

    // Student routes
    Route::middleware('role:user')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [StudentDashboardController::class, 'courses'])->name('courses.index');
        Route::get('/courses/{course}', [StudentDashboardController::class, 'courseDetails'])->name('courses.show');
        Route::get('/attendance', [StudentDashboardController::class, 'attendance'])->name('attendance.index');
        Route::get('/active-sessions', [StudentDashboardController::class, 'activeSessions'])->name('active-sessions');
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/change-password', [StudentDashboardController::class, 'changePassword'])->name('change-password');
    });

    // QR routes
    Route::prefix('qr')->name('qr.')->group(function () {
        Route::get('/generate/{sessionId}', [QrController::class, 'generateQr'])->name('generate');
        Route::get('/show/{sessionId}', [QrController::class, 'showQrPage'])->name('show');
        Route::get('/stats/{sessionId}', [QrController::class, 'getAttendanceStats'])->name('stats');
    });
});

// Public QR check-in route (GET visible; POST requires auth)
Route::get('/qr/checkin/{session}', [QrController::class, 'showCheckInPage'])->name('qr.checkin');
Route::middleware('auth')->post('/qr/checkin/{session}', [QrController::class, 'checkIn'])->name('qr.checkin.post');
