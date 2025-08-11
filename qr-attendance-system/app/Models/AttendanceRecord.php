<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'qr_session_id',
        'check_in_time',
        'check_in_method',
        'notes',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    /**
     * Get the user who attended.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course for this attendance record.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the QR session for this attendance record.
     */
    public function qrSession()
    {
        return $this->belongsTo(QrSession::class);
    }
}
