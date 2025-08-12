<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Course</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-2">{{ $course->name }}</h1>
    <p class="text-gray-600 mb-4">Code: {{ $course->code }} | Max Attendance: {{ $course->max_attendance ?? 'Unlimited' }}</p>
    <div class="flex gap-2 mb-4">
      <a class="px-3 py-2 bg-gray-800 text-white rounded" href="{{ route('admin.courses.students', $course) }}">Students</a>
      <a class="px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.courses.qr-sessions', $course) }}">QR Sessions</a>
      <a class="px-3 py-2 bg-indigo-600 text-white rounded" href="{{ route('admin.courses.attendance', $course) }}">Attendance</a>
    </div>
    <h2 class="font-semibold mb-2">Recent Attendance</h2>
    <div class="space-y-2">
      @foreach($recentAttendance as $r)
        <div class="p-3 bg-white rounded shadow">{{ $r->user->name }} - {{ $r->check_in_time->format('Y-m-d H:i') }}</div>
      @endforeach
    </div>
  </div>
</body>
</html>