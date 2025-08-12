<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Course</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Course</h1>
    <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-3">
      @csrf
      @method('PUT')
      <input class="border rounded p-2 w-full" name="name" value="{{ $course->name }}" />
      <textarea class="border rounded p-2 w-full" name="description">{{ $course->description }}</textarea>
      <input class="border rounded p-2 w-full" name="code" value="{{ $course->code }}" />
      <input class="border rounded p-2 w-full" name="max_attendance" type="number" value="{{ $course->max_attendance }}" />
      <select class="border rounded p-2 w-full" name="admin_id">
        @foreach($admins as $admin)
          <option value="{{ $admin->id }}" {{ $course->admin_id==$admin->id ? 'selected' : '' }}>{{ $admin->name }} ({{ $admin->email }})</option>
        @endforeach
      </select>
      <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $course->is_active ? 'checked' : '' }} /> Active</label>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
    </form>
  </div>
</body>
</html>