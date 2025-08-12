<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Course</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-2">{{ $course->name }}</h1>
    <p class="text-gray-600 mb-4">Max Attendance: {{ $course->max_attendance ?? 'Unlimited' }} | Your Attendance: {{ $userAttendanceCount }}</p>
    @if(!$canAttend)
      <div class="mb-4 p-3 bg-yellow-50 text-yellow-800 rounded">You have reached the maximum attendance limit for this course.</div>
    @endif
    <h2 class="font-semibold mb-2">Recent Sessions</h2>
    <div class="space-y-2">
      @foreach($recentSessions as $s)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $s->title }} ({{ $s->start_time->format('Y-m-d H:i') }})</span>
          <a class="px-3 py-1 bg-blue-600 text-white rounded" href="{{ route('qr.checkin', ['session' => $s->session_code]) }}">Check-in</a>
        </div>
      @endforeach
    </div>
    <h2 class="font-semibold mt-6 mb-2">Your Attendance</h2>
    <div class="space-y-2">
      @foreach($userAttendanceRecords as $r)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $r->qrSession->title }}</span>
          <span class="text-gray-600">{{ $r->check_in_time->format('Y-m-d H:i') }}</span>
        </div>
      @endforeach
    </div>
  </div>
</body>
</html>