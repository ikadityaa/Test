<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Users</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Users</h1>
    <a class="px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.users.create') }}">Create User</a>
    <div class="mt-4 space-y-2">
      @foreach($users as $u)
        <div class="p-3 bg-white rounded shadow flex justify-between">
          <span>{{ $u->name }} ({{ $u->email }}) - {{ $u->role->name }}</span>
          <div class="space-x-2">
            <a class="px-3 py-1 bg-gray-800 text-white rounded" href="{{ route('admin.users.show', $u->id) }}">View</a>
            <a class="px-3 py-1 bg-yellow-600 text-white rounded" href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
  </div>
</body>
</html>