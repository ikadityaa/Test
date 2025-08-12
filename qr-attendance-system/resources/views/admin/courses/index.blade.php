<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Courses</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Courses</h1>
    <a class="px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.courses.create') }}">Create Course</a>
    <div class="mt-4 space-y-2">
      @foreach($courses as $course)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $course->name }}</span>
          <div class="space-x-2">
            <a class="px-3 py-1 bg-gray-800 text-white rounded" href="{{ route('admin.courses.show', $course) }}">View</a>
            <a class="px-3 py-1 bg-yellow-600 text-white rounded" href="{{ route('admin.courses.edit', $course) }}">Edit</a>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $courses->links() }}</div>
  </div>
</body>
</html>