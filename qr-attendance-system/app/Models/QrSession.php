<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'created_by',
        'session_code',
        'title',
        'description',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model and generate session code.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($qrSession) {
            if (empty($qrSession->session_code)) {
                $qrSession->session_code = Str::random(16);
            }
        });
    }

    /**
     * Get the course for this QR session.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who created this QR session.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the attendance records for this QR session.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Check if the QR session is currently active.
     */
    public function isCurrentlyActive()
    {
        $now = now();
        return $this->is_active && 
               $this->start_time <= $now && 
               $this->end_time >= $now;
    }

    /**
     * Get the QR code URL for this session.
     */
    public function getQrCodeUrl()
    {
        return route('qr.checkin', ['session' => $this->session_code]);
    }

    /**
     * Get the number of students who attended this session.
     */
    public function getAttendanceCount()
    {
        return $this->attendanceRecords()->count();
    }
}
