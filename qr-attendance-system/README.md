# QR Attendance System

A comprehensive web application built with Laravel for tracking student attendance using QR codes. Designed specifically for tutoring services and educational institutions.

## 🚀 Features

### 🔐 Role-Based Access Control
- **Super Admin**: Full system access and user management
- **Admin**: Course management and student enrollment
- **Student**: QR code check-in and attendance viewing

### 📱 QR Code Attendance
- Generate unique QR codes for each session
- Time-limited QR sessions with automatic expiration
- Mobile-friendly check-in interface
- Duplicate check-in prevention

### 📊 Course Management
- Create and manage courses with attendance limits
- Enroll and manage students
- Track attendance per course
- Generate attendance reports

### 📈 Analytics & Reporting
- Real-time attendance statistics
- Course-wise attendance reports
- Student performance tracking
- Export functionality (CSV/PDF)

### 🛠️ Easy Installation
- User-friendly installation wizard
- Automatic system requirements check
- Database setup and configuration
- One-click installation process

## 📋 Requirements

### Server Requirements
- PHP 8.1 or higher
- Web server (Apache/Nginx)
- MySQL 5.7+ or SQLite 3
- Required PHP extensions:
  - BCMath, Ctype, JSON, Mbstring
  - OpenSSL, PDO, Tokenizer, XML, GD

### Client Requirements
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Mobile device for QR scanning (optional)

## 🚀 Quick Start

### 1. Installation

#### Option A: Using Installation Wizard (Recommended)
1. Upload files to your web server
2. Set proper permissions:
   ```bash
   chmod -R 755 /path/to/qr-attendance-system
   chmod -R 777 storage/
   chmod -R 777 bootstrap/cache/
   chmod 666 .env
   ```
3. Navigate to `http://your-domain.com/install`
4. Follow the installation wizard

#### Option B: Manual Installation
1. Clone or download the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy `.env.example` to `.env` and configure
4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```

### 2. Default Login
- **Email**: admin@qrattendance.com
- **Password**: password
- **⚠️ Important**: Change the default password immediately

## 📖 Usage Guide

### For Super Admins

#### Managing Users
1. Login as Super Admin
2. Navigate to Admin → Users
3. Create new admin or student accounts
4. Assign appropriate roles

#### System Overview
- View system-wide statistics
- Monitor all courses and users
- Access global settings

### For Admins

#### Creating Courses
1. Login as Admin
2. Go to Admin → Courses
3. Click "Create New Course"
4. Fill in course details and assign students

#### Managing QR Sessions
1. Select a course
2. Go to QR Sessions
3. Create new session with time limits
4. Display QR code to students

#### Viewing Reports
- Course attendance reports
- Student enrollment lists
- Session statistics

### For Students

#### Checking In
1. Scan QR code with mobile device
2. Verify enrollment and session validity
3. Receive confirmation of check-in

#### Viewing Attendance
- Personal attendance history
- Course-wise breakdown
- Active session information

## 🗄️ Database Schema

### Core Tables
- `users` - User accounts and profiles
- `roles` - User role definitions
- `courses` - Course information
- `qr_sessions` - QR code sessions
- `attendance_records` - Attendance data
- `course_user` - Student-course relationships

### Key Relationships
- Users belong to roles
- Courses have admins and students
- QR sessions belong to courses
- Attendance records link users, courses, and sessions

## 🔧 Configuration

### Environment Variables
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

### Database Setup

#### MySQL
```sql
CREATE DATABASE qr_attendance;
CREATE USER 'qr_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON qr_attendance.* TO 'qr_user'@'localhost';
FLUSH PRIVILEGES;
```

#### SQLite (Recommended for small installations)
- No additional setup required
- Database file created automatically

## 🛡️ Security Features

### Authentication & Authorization
- Secure password hashing
- Role-based access control
- Session security
- CSRF protection

### QR Code Security
- Unique session codes
- Time-limited validity
- Course-specific access
- Duplicate check-in prevention

### Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- Secure file uploads

## 📊 API Endpoints

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

## 🔧 Development

### Project Structure
```
qr-attendance-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Student/        # Student controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── QrController.php
│   ├── Models/             # Eloquent models
│   └── Http/Middleware/    # Custom middleware
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   └── views/             # Blade templates
├── routes/
│   ├── web.php            # Web routes
│   └── auth.php           # Auth routes
└── storage/               # Application storage
```

### Key Components

#### Models
- `User` - User management and relationships
- `Role` - Role definitions and permissions
- `Course` - Course management and enrollment
- `QrSession` - QR code session management
- `AttendanceRecord` - Attendance tracking

#### Controllers
- `InstallController` - Installation wizard
- `QrController` - QR code generation and check-in
- `Admin\*` - Admin panel functionality
- `Student\*` - Student interface
- `Auth\*` - Authentication handling

#### Middleware
- `CheckRole` - Role-based access control

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Disable debug mode: `APP_DEBUG=false`
3. Configure web server (Apache/Nginx)
4. Set up SSL certificate
5. Configure database for production
6. Set up regular backups

### Performance Optimization
- Enable route caching: `php artisan route:cache`
- Enable config caching: `php artisan config:cache`
- Enable view caching: `php artisan view:cache`
- Optimize database queries
- Implement caching strategies

## 🛠️ Maintenance

### Regular Tasks
- Monitor error logs
- Backup database regularly
- Update dependencies
- Review security settings
- Clean up old QR sessions

### Backup Procedures
```bash
# Database backup
mysqldump -u username -p qr_attendance > backup.sql

# File backup
tar -czf qr_attendance_backup.tar.gz /path/to/qr-attendance-system
```

## 📚 Documentation

- [Architecture Documentation](ARCHITECTURE.md)
- [Implementation Guide](IMPLEMENTATION_GUIDE.md)
- [API Documentation](API.md)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Getting Help
- Check the [documentation](IMPLEMENTATION_GUIDE.md)
- Review [troubleshooting guide](IMPLEMENTATION_GUIDE.md#troubleshooting)
- Search existing issues

### Reporting Issues
- Use the issue tracker
- Provide detailed error information
- Include system specifications

### Feature Requests
- Submit feature requests via issues
- Describe the use case clearly
- Consider implementation complexity

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/)
- QR code generation with [Simple QR Code](https://github.com/SimpleSoftwareIO/simple-qrcode)
- UI components with [Tailwind CSS](https://tailwindcss.com/)

## 📈 Roadmap

### Planned Features
- [ ] Mobile app for QR scanning
- [ ] Advanced analytics dashboard
- [ ] Integration with LMS systems
- [ ] Multi-language support
- [ ] Advanced reporting features
- [ ] API for third-party integrations

### Version History
- **v1.0.0** - Initial release with core functionality
- **v1.1.0** - Enhanced reporting and analytics
- **v1.2.0** - Mobile optimization and API improvements

---

**QR Attendance System** - Making attendance tracking simple and efficient for educational institutions.
