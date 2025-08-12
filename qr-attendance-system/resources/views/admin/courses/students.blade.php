<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Students</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $course->name }} - Students</h1>
    <form method="POST" action="{{ route('admin.courses.enroll-student', $course) }}" class="mb-4 flex gap-2">
      @csrf
      <select name="student_id" class="border rounded p-2 w-full">
        @foreach($availableStudents as $s)
          <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
        @endforeach
      </select>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Enroll</button>
    </form>
    <div class="space-y-2">
      @foreach($enrolledStudents as $s)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $s->name }} ({{ $s->email }})</span>
          <form method="POST" action="{{ route('admin.courses.remove-student', $course) }}">
            @csrf
            <input type="hidden" name="student_id" value="{{ $s->id }}" />
            <button class="px-3 py-1 bg-red-600 text-white rounded">Remove</button>
          </form>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $enrolledStudents->links() }}</div>
  </div>
</body>
</html>