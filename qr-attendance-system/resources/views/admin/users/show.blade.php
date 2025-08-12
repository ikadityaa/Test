<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-2">{{ $user->name }}</h1>
    <p class="text-gray-600 mb-4">{{ $user->email }} | Role: {{ $user->role->name }}</p>
    <h2 class="font-semibold mb-2">Courses</h2>
    <ul class="list-disc ml-6">
      @foreach($user->courses as $c)
        <li>{{ $c->name }}</li>
      @endforeach
    </ul>
  </div>
</body>
</html>