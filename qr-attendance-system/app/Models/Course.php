<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'max_attendance',
        'admin_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the admin who manages this course.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the students enrolled in this course.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
                    ->withPivot('enrollment_date', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Get the QR sessions for this course.
     */
    public function qrSessions()
    {
        return $this->hasMany(QrSession::class);
    }

    /**
     * Get the attendance records for this course.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get active QR sessions for this course.
     */
    public function activeQrSessions()
    {
        return $this->qrSessions()->where('is_active', true);
    }

    /**
     * Get the total attendance count for a specific student.
     */
    public function getStudentAttendanceCount($userId)
    {
        return $this->attendanceRecords()
                    ->where('user_id', $userId)
                    ->count();
    }

    /**
     * Check if a student can attend (hasn't reached max attendance).
     */
    public function canStudentAttend($userId)
    {
        if ($this->max_attendance <= 0) {
            return true; // No limit
        }
        
        $currentAttendance = $this->getStudentAttendanceCount($userId);
        return $currentAttendance < $this->max_attendance;
    }
}
