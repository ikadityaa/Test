<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Course Attendance</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $course->name }} - Attendance</h1>
    <div class="mb-4">Total Students: {{ $stats['total_students'] }} | Total Attendance: {{ $stats['total_attendance'] }}</div>
    <div class="space-y-2">
      @foreach($attendanceRecords as $rec)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $rec->user->name }} - {{ $rec->qrSession->title }}</span>
          <span class="text-gray-600">{{ $rec->check_in_time->format('Y-m-d H:i') }}</span>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $attendanceRecords->links() }}</div>
  </div>
</body>
</html>