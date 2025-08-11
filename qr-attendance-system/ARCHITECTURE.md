# QR Attendance System - Architecture Documentation

## System Overview

The QR Attendance System is a comprehensive web application built with Laravel that enables educational institutions to track student attendance using QR codes. The system supports three user roles with different levels of access and functionality.

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        QR Attendance System                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────┐    ┌─────────────────┐    ┌──────────────┐ │
│  │   Web Browser   │    │  Mobile Device  │    │ QR Scanner   │ │
│  │   (Admin/User)  │    │   (Student)     │    │   App        │ │
│  └─────────────────┘    └─────────────────┘    └──────────────┘ │
│           │                       │                      │      │
│           └───────────────────────┼──────────────────────┘      │
│                                   │                             │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    Laravel Application                      │ │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────┐ │ │
│  │  │   Controllers   │  │    Middleware   │  │    Routes    │ │ │
│  │  │                 │  │                 │  │              │ │ │
│  │  │ • InstallCtrl   │  │ • CheckRole     │  │ • Web Routes │ │ │
│  │  │ • QrController  │  │ • Auth          │  │ • API Routes │ │ │
│  │  │ • Admin/*       │  │ • Guest         │  │              │ │ │
│  │  │ • Student/*     │  │                 │  │              │ │ │
│  │  └─────────────────┘  └─────────────────┘  └──────────────┘ │ │
│  │                                                                 │ │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────┐ │ │
│  │  │     Models      │  │     Services    │  │   Helpers    │ │ │
│  │  │                 │  │                 │  │              │ │ │
│  │  │ • User          │  │ • QR Generation │  │ • Validation │ │ │
│  │  │ • Role          │  │ • Attendance    │  │ • Utilities  │ │ │
│  │  │ • Course        │  │ • Reports       │  │              │ │ │
│  │  │ • QrSession     │  │                 │  │              │ │ │
│  │  │ • Attendance    │  │                 │  │              │ │ │
│  │  └─────────────────┘  └─────────────────┘  └──────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                   │                             │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    Database Layer                           │ │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────┐ │ │
│  │  │   MySQL/SQLite  │  │   Migrations    │  │   Seeders    │ │ │
│  │  │                 │  │                 │  │              │ │ │
│  │  │ • users         │  │ • Schema        │  │ • Roles      │ │ │
│  │  │ • roles         │  │ • Relationships │  │ • Super Admin│ │ │
│  │  │ • courses       │  │ • Indexes       │  │ • Sample Data│ │ │
│  │  │ • qr_sessions   │  │                 │  │              │ │ │
│  │  │ • attendance    │  │                 │  │              │ │ │
│  │  └─────────────────┘  └─────────────────┘  └──────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## User Roles and Permissions

### 1. Super Admin
- **Full system access**
- Manage all users (admins and students)
- Create and manage all courses
- View system-wide statistics
- Manage global settings
- Access to all features

### 2. Admin
- **Course-level access**
- Manage assigned courses only
- Create QR sessions for their courses
- Enroll/remove students from courses
- View attendance reports for their courses
- Manage course settings

### 3. Student (User)
- **Limited access**
- View enrolled courses
- Check in using QR codes
- View personal attendance history
- Update profile information

## Core Components

### 1. Authentication System
- Laravel's built-in authentication
- Role-based middleware
- Session management
- Password reset functionality

### 2. QR Code Management
- QR code generation using `simple-qrcode` library
- Session-based QR codes with expiration
- Secure check-in validation
- Duplicate check-in prevention

### 3. Attendance Tracking
- Real-time attendance recording
- Maximum attendance limits per course
- Attendance history and reports
- Export functionality

### 4. Course Management
- Course creation and management
- Student enrollment system
- Course-specific settings
- Attendance limits configuration

## Database Schema

### Users Table
```sql
users (
    id, name, email, password, role_id, student_id, 
    phone, address, email_verified_at, remember_token, 
    created_at, updated_at
)
```

### Roles Table
```sql
roles (
    id, name, display_name, description, 
    created_at, updated_at
)
```

### Courses Table
```sql
courses (
    id, name, description, code, max_attendance, 
    admin_id, is_active, created_at, updated_at
)
```

### QR Sessions Table
```sql
qr_sessions (
    id, course_id, created_by, session_code, title, 
    description, start_time, end_time, is_active, 
    created_at, updated_at
)
```

### Attendance Records Table
```sql
attendance_records (
    id, user_id, course_id, qr_session_id, check_in_time, 
    check_in_method, notes, created_at, updated_at
)
```

### Course-User Pivot Table
```sql
course_user (
    id, course_id, user_id, enrollment_date, is_active, 
    created_at, updated_at
)
```

## Security Features

### 1. Authentication & Authorization
- Secure password hashing
- Role-based access control
- Session security
- CSRF protection

### 2. QR Code Security
- Unique session codes
- Time-limited validity
- Course-specific access
- Duplicate check-in prevention

### 3. Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- Secure file uploads

## API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/courses` - Course management
- `POST /admin/courses` - Create course
- `GET /admin/courses/{id}/students` - Manage students
- `GET /admin/courses/{id}/qr-sessions` - QR sessions
- `GET /admin/attendance` - Attendance reports

### Student Routes
- `GET /student/dashboard` - Student dashboard
- `GET /student/courses` - Enrolled courses
- `GET /student/attendance` - Attendance history
- `GET /student/active-sessions` - Active QR sessions

### QR Routes
- `GET /qr/generate/{sessionId}` - Generate QR code
- `GET /qr/checkin/{session}` - QR check-in page
- `POST /qr/checkin/{session}` - Process check-in
- `GET /qr/stats/{sessionId}` - Session statistics

## Installation Process

### 1. System Requirements Check
- PHP version verification
- Extension requirements
- Directory permissions

### 2. Configuration
- Database connection setup
- Application settings
- Environment variables

### 3. Database Setup
- Migration execution
- Seeder population
- Initial data creation

### 4. Finalization
- Cache clearing
- Configuration optimization
- Installation completion

## Scalability Considerations

### 1. Database Optimization
- Proper indexing
- Query optimization
- Connection pooling

### 2. Caching Strategy
- Route caching
- Configuration caching
- Query result caching

### 3. Performance Monitoring
- Database query monitoring
- Response time tracking
- Error logging

## Deployment Architecture

### Development Environment
- Local development server
- SQLite database
- Debug mode enabled

### Production Environment
- Web server (Apache/Nginx)
- MySQL database
- SSL encryption
- Error logging
- Backup systems

## Maintenance and Updates

### 1. Regular Maintenance
- Database backups
- Log rotation
- Security updates
- Performance monitoring

### 2. System Updates
- Laravel framework updates
- Package updates
- Security patches
- Feature additions

This architecture provides a robust, scalable, and secure foundation for the QR Attendance System, ensuring reliable operation and easy maintenance.