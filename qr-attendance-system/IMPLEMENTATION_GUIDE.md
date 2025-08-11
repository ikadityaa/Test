# QR Attendance System - Implementation Guide

## Table of Contents
1. [System Requirements](#system-requirements)
2. [Installation Process](#installation-process)
3. [Configuration](#configuration)
4. [Database Setup](#database-setup)
5. [User Management](#user-management)
6. [Course Management](#course-management)
7. [QR Code Usage](#qr-code-usage)
8. [Attendance Tracking](#attendance-tracking)
9. [Troubleshooting](#troubleshooting)
10. [Maintenance](#maintenance)

## System Requirements

### Server Requirements
- **PHP**: Version 8.2 or higher
- **Web Server**: Apache or Nginx
- **Database**: MySQL 5.7+ or SQLite 3
- **Extensions**: 
  - BCMath PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - GD PHP Extension (for QR codes)

### Client Requirements
- **Web Browser**: Chrome, Firefox, Safari, or Edge (latest versions)
- **Mobile Device**: For QR code scanning (optional)

## Installation Process

### Step 1: Download and Extract
1. Download the QR Attendance System files
2. Extract to your web server directory (e.g., `/var/www/html/` or `htdocs/`)
3. Ensure the directory is accessible via your web server

### Step 2: Set Permissions
Set the following directory permissions:
```bash
chmod -R 755 /path/to/qr-attendance-system
chmod -R 777 /path/to/qr-attendance-system/storage
chmod -R 777 /path/to/qr-attendance-system/bootstrap/cache
chmod 666 /path/to/qr-attendance-system/.env
```

### Step 3: Access Installation Wizard
1. Open your web browser
2. Navigate to: `http://your-domain.com/install`
3. Follow the installation wizard

### Step 4: Complete Installation
The installation wizard will:
- Check system requirements
- Verify directory permissions
- Configure database connection
- Set up initial data
- Create default admin account

## Configuration

### Environment Variables
The system uses a `.env` file for configuration. Key settings include:

```env
APP_NAME="QR Attendance System"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=qr_attendance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Database Configuration

#### MySQL Setup
1. Create a MySQL database:
```sql
CREATE DATABASE qr_attendance;
CREATE USER 'qr_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON qr_attendance.* TO 'qr_user'@'localhost';
FLUSH PRIVILEGES;
```

2. Update `.env` file with database credentials

#### SQLite Setup (Recommended for small installations)
1. Ensure SQLite is enabled in PHP
2. The system will automatically create the database file

## Database Setup

### Automatic Setup
The installation wizard automatically:
- Runs database migrations
- Creates necessary tables
- Seeds initial data
- Creates default roles and admin user

### Manual Setup (if needed)
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear caches
php artisan config:clear
php artisan cache:clear
```

### Default Data
The system creates:
- **Roles**: super_admin, admin, user
- **Super Admin**: admin@qrattendance.com / password
- **Sample Courses**: (if using sample data)

## User Management

### User Roles

#### Super Admin
- **Email**: admin@qrattendance.com
- **Password**: password (change immediately)
- **Permissions**: Full system access

#### Admin
- **Permissions**: Course management, student enrollment
- **Access**: Assigned courses only

#### Student (User)
- **Permissions**: View courses, check-in via QR
- **Access**: Enrolled courses only

### Creating New Users

#### Via Registration (Students)
1. Students can register at: `/register`
2. They are automatically assigned the "user" role
3. Admins can enroll them in courses

#### Via Admin Panel (Admins/Super Admins)
1. Login as Super Admin
2. Navigate to: Admin → Users
3. Click "Add New User"
4. Fill in user details and assign role

### User Profile Management
Users can update their profiles:
- Personal information
- Contact details
- Password changes

## Course Management

### Creating Courses

#### Step 1: Access Course Management
1. Login as Admin or Super Admin
2. Navigate to: Admin → Courses
3. Click "Create New Course"

#### Step 2: Course Details
Fill in the following information:
- **Course Name**: e.g., "Introduction to Computer Science"
- **Course Code**: e.g., "CS101"
- **Description**: Course description
- **Maximum Attendance**: 0 for unlimited, or set a limit
- **Admin**: Course administrator

#### Step 3: Save Course
Click "Create Course" to save

### Managing Students

#### Enrolling Students
1. Go to Course → Students
2. Select students from available list
3. Click "Enroll Student"

#### Removing Students
1. Go to Course → Students
2. Find student in enrolled list
3. Click "Remove" button

### Course Settings
- **Active/Inactive**: Toggle course availability
- **Attendance Limits**: Set maximum attendances per student
- **Admin Assignment**: Change course administrator

## QR Code Usage

### Creating QR Sessions

#### Step 1: Access QR Sessions
1. Go to Course → QR Sessions
2. Click "Create New Session"

#### Step 2: Session Configuration
- **Title**: Session name (e.g., "Week 1 Lecture")
- **Description**: Optional session description
- **Start Time**: When QR code becomes active
- **End Time**: When QR code expires

#### Step 3: Generate QR Code
1. Click "Create Session"
2. System generates unique QR code
3. Display QR code to students

### QR Code Display Options

#### Option 1: Project on Screen
- Display QR code on projector/screen
- Students scan with mobile devices

#### Option 2: Print QR Code
- Print QR code on paper
- Post in classroom

#### Option 3: Share Digitally
- Share QR code image via email/LMS
- Students scan from their devices

### Student Check-in Process

#### Step 1: Scan QR Code
1. Student opens camera app or QR scanner
2. Points camera at QR code
3. Taps notification to open check-in page

#### Step 2: Verify Check-in
1. System validates student enrollment
2. Checks attendance limits
3. Prevents duplicate check-ins
4. Records attendance

#### Step 3: Confirmation
- Student receives success message
- Attendance is recorded immediately

## Attendance Tracking

### Viewing Attendance Reports

#### Course-Level Reports
1. Go to Course → Attendance
2. View attendance statistics:
   - Total students enrolled
   - Total attendance records
   - Average attendance per student

#### Session-Level Reports
1. Go to QR Session → Statistics
2. View session details:
   - Students who attended
   - Check-in times
   - Attendance percentage

#### Student-Level Reports
1. Go to Student → Attendance
2. View personal attendance history:
   - All attended sessions
   - Check-in times
   - Course-wise breakdown

### Exporting Data

#### Export Options
- **CSV Format**: For spreadsheet analysis
- **PDF Reports**: For printing/sharing
- **Date Range**: Filter by specific periods

#### Export Process
1. Navigate to desired report
2. Click "Export" button
3. Select format and date range
4. Download file

### Attendance Analytics

#### Key Metrics
- **Attendance Rate**: Percentage of enrolled students
- **Session Participation**: Per-session attendance
- **Student Performance**: Individual attendance patterns
- **Course Comparison**: Attendance across courses

## Troubleshooting

### Common Issues

#### Installation Problems

**Issue**: "Permission denied" errors
**Solution**: 
```bash
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

**Issue**: Database connection failed
**Solution**:
1. Verify database credentials in `.env`
2. Ensure database server is running
3. Check database user permissions

**Issue**: QR codes not generating
**Solution**:
1. Verify GD extension is installed
2. Check storage directory permissions
3. Clear application cache

#### QR Code Issues

**Issue**: QR code not scanning
**Solution**:
1. Ensure QR code is clearly displayed
2. Check lighting conditions
3. Verify QR code is active (within time limits)

**Issue**: Check-in fails
**Solution**:
1. Verify student is enrolled in course
2. Check attendance limits
3. Ensure session is active
4. Check for duplicate check-ins

#### Performance Issues

**Issue**: Slow page loading
**Solution**:
1. Clear application cache
2. Optimize database queries
3. Check server resources

**Issue**: High memory usage
**Solution**:
1. Increase PHP memory limit
2. Optimize image processing
3. Implement caching

### Error Logs
Check error logs for detailed information:
- **Laravel Logs**: `storage/logs/laravel.log`
- **Web Server Logs**: Apache/Nginx error logs
- **PHP Logs**: PHP error log

## Maintenance

### Regular Maintenance Tasks

#### Daily
- Monitor error logs
- Check system performance
- Verify backup completion

#### Weekly
- Review attendance reports
- Clean up old QR sessions
- Update user accounts

#### Monthly
- Database optimization
- Security updates
- Performance monitoring

### Backup Procedures

#### Database Backup
```bash
# MySQL
mysqldump -u username -p qr_attendance > backup.sql

# SQLite
cp database/database.sqlite backup.sqlite
```

#### File Backup
```bash
# Application files
tar -czf qr_attendance_backup.tar.gz /path/to/qr-attendance-system

# Exclude unnecessary directories
tar -czf qr_attendance_backup.tar.gz --exclude=storage/logs --exclude=storage/framework/cache /path/to/qr-attendance-system
```

### Security Best Practices

#### Password Security
- Enforce strong passwords
- Regular password changes
- Multi-factor authentication (if available)

#### Access Control
- Limit admin access
- Regular user account review
- Monitor login attempts

#### Data Protection
- Regular backups
- Encrypted data transmission
- Secure file permissions

### Updates and Upgrades

#### Laravel Framework Updates
1. Check compatibility
2. Backup current installation
3. Update dependencies
4. Test functionality
5. Deploy updates

#### Application Updates
1. Download new version
2. Backup current installation
3. Replace files (preserve customizations)
4. Run migrations
5. Clear caches
6. Test functionality

## Support and Resources

### Documentation
- **User Manual**: System usage guide
- **Admin Guide**: Administrative functions
- **Technical Documentation**: Developer resources

### Support Channels
- **Email Support**: support@qrattendance.com
- **Documentation**: Online help system
- **Community Forum**: User discussions

### Training Resources
- **Video Tutorials**: Step-by-step guides
- **Webinars**: Live training sessions
- **User Workshops**: Hands-on training

This implementation guide provides comprehensive instructions for setting up, configuring, and maintaining the QR Attendance System. For additional support, refer to the documentation or contact the support team.