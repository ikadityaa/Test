<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 bg-white rounded shadow">Total Courses: {{ $totalCourses }}</div>
      <div class="p-4 bg-white rounded shadow">Total Students: {{ $totalStudents ?? 0 }}</div>
      <div class="p-4 bg-white rounded shadow">Attendance (last 7 days): {{ $attendanceStats['last_7_days'] ?? 0 }}</div>
    </div>
    <div class="mt-6">
      <a class="px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.settings.index') }}">Settings</a>
    </div>
  </div>
</body>
</html>