<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Active Sessions</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Active QR Sessions</h1>
    <div class="space-y-2">
      @forelse($activeSessions as $s)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <div>
            <div class="font-medium">{{ $s->title }}</div>
            <div class="text-sm text-gray-600">Course: {{ $s->course->name ?? '' }}</div>
          </div>
          <a class="px-3 py-1 bg-blue-600 text-white rounded" href="{{ route('qr.checkin', ['session' => $s->session_code]) }}">Check-in</a>
        </div>
      @empty
        <div class="p-3 bg-white rounded shadow">No active sessions right now.</div>
      @endforelse
    </div>
  </div>
</body>
</html>