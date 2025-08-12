<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QR Sessions</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $course->name }} - QR Sessions</h1>
    <a class="px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.courses.qr-sessions.create', $course) }}">Create Session</a>
    <form method="POST" action="{{ route('admin.courses.send-reminder', $course) }}" class="mt-4 flex gap-2">
      @csrf
      <input class="border rounded p-2 w-full" name="message" placeholder="Reminder message" />
      <button class="px-3 py-2 bg-green-600 text-white rounded">Send Reminder to Students</button>
    </form>
    <div class="mt-4 space-y-2">
      @foreach($qrSessions as $s)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $s->title }} ({{ $s->start_time->format('Y-m-d H:i') }} - {{ $s->end_time->format('Y-m-d H:i') }})</span>
          <div class="space-x-2">
            <a class="px-3 py-1 bg-gray-800 text-white rounded" href="{{ route('qr.show', $s->id) }}">Show QR</a>
            <a class="px-3 py-1 bg-indigo-600 text-white rounded" href="{{ route('qr.stats', $s->id) }}">Stats (JSON)</a>
            <form class="inline" method="POST" action="{{ route('admin.courses.qr-sessions.send-summary', [$course, 'session' => $s->id]) }}">
              @csrf
              <button class="px-3 py-1 bg-purple-600 text-white rounded">Send Summary</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $qrSessions->links() }}</div>
  </div>
</body>
</html>