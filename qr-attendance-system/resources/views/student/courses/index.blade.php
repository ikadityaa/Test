<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Courses</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Courses</h1>
    <div class="space-y-2">
      @foreach($enrolledCourses as $course)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $course->name }}</span>
          <a class="px-3 py-1 bg-blue-600 text-white rounded" href="{{ route('student.courses.show', $course) }}">View</a>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $enrolledCourses->links() }}</div>
  </div>
</body>
</html>