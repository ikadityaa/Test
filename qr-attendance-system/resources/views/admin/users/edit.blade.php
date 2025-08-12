<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit User</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-3">
      @csrf
      @method('PUT')
      <input class="border rounded p-2 w-full" name="name" value="{{ $user->name }}" />
      <input class="border rounded p-2 w-full" name="email" value="{{ $user->email }}" />
      <select class="border rounded p-2 w-full" name="role_id">
        @foreach($roles as $role)
          <option value="{{ $role->id }}" {{ $user->role_id==$role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
      </select>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
    </form>
  </div>
</body>
</html>