<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'student_id',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the courses that the user is enrolled in.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
                    ->withPivot('enrollment_date', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Get the courses that the user administers.
     */
    public function administeredCourses()
    {
        return $this->hasMany(Course::class, 'admin_id');
    }

    /**
     * Get the attendance records for the user.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get the QR sessions created by the user.
     */
    public function qrSessions()
    {
        return $this->hasMany(QrSession::class, 'created_by');
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin()
    {
        return $this->role->name === 'super_admin';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    /**
     * Check if user is student.
     */
    public function isStudent()
    {
        return $this->role->name === 'user';
    }

    /**
     * Check if user has any admin role.
     */
    public function isAdminRole()
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }
}
