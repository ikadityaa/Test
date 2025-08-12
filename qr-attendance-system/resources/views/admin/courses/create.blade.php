<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Course</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create Course</h1>
    <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-3">
      @csrf
      <input class="border rounded p-2 w-full" name="name" placeholder="Name" />
      <textarea class="border rounded p-2 w-full" name="description" placeholder="Description"></textarea>
      <input class="border rounded p-2 w-full" name="code" placeholder="Code" />
      <input class="border rounded p-2 w-full" name="max_attendance" type="number" placeholder="Max Attendance (0 = unlimited)" />
      <select class="border rounded p-2 w-full" name="admin_id">
        @foreach($admins as $admin)
          <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
        @endforeach
      </select>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
    </form>
  </div>
</body>
</html>