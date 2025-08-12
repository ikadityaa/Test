<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Profile</h1>
    <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-3">
      @csrf
      <input class="border rounded p-2 w-full" name="name" value="{{ $user->name }}" />
      <input class="border rounded p-2 w-full" name="email" value="{{ $user->email }}" />
      <input class="border rounded p-2 w-full" name="student_id" value="{{ $user->student_id }}" />
      <input class="border rounded p-2 w-full" name="phone" value="{{ $user->phone }}" />
      <textarea class="border rounded p-2 w-full" name="address">{{ $user->address }}</textarea>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
    </form>
  </div>
</body>
</html>