<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create User</title>
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create User</h1>
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-3">
      @csrf
      <input class="border rounded p-2 w-full" name="name" placeholder="Name" />
      <input class="border rounded p-2 w-full" name="email" placeholder="Email" />
      <input class="border rounded p-2 w-full" type="password" name="password" placeholder="Password" />
      <select class="border rounded p-2 w-full" name="role_id">
        @foreach($roles as $role)
          <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
      </select>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
    </form>
  </div>
</body>
</html>