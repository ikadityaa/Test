<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->name }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 bg-white rounded shadow">Enrolled Courses: {{ $stats['total_courses'] }}</div>
      <div class="p-4 bg-white rounded shadow">Total Attendance: {{ $stats['total_attendance'] }}</div>
      <div class="p-4 bg-white rounded shadow">Active Sessions: {{ $stats['active_sessions'] }}</div>
    </div>
  </div>
</body>
</html>