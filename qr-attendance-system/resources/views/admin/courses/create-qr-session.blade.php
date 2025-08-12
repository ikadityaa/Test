<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create QR Session</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create QR Session - {{ $course->name }}</h1>
    <form method="POST" action="{{ route('admin.courses.qr-sessions.store', $course) }}" class="space-y-3">
      @csrf
      <input class="border rounded p-2 w-full" name="title" placeholder="Title" />
      <textarea class="border rounded p-2 w-full" name="description" placeholder="Description"></textarea>
      <input class="border rounded p-2 w-full" name="start_time" type="datetime-local" />
      <input class="border rounded p-2 w-full" name="end_time" type="datetime-local" />
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
    </form>
  </div>
</body>
</html>