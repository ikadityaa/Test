<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QR Check-in</title>
    <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white shadow rounded p-6">
        <h1 class="text-xl font-bold mb-4">{{ $qrSession->title }}</h1>
        <p class="text-sm text-gray-600 mb-2">Course: {{ $qrSession->course->name }}</p>
        @guest
            <div class="mb-4 p-3 bg-yellow-50 text-yellow-800 rounded">
                Please <a class="underline" href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}">login</a> to check in.
            </div>
        @else
            <form id="checkin-form" method="POST" action="{{ route('qr.checkin.post', ['session' => $qrSession->session_code]) }}" class="space-y-3">
                @csrf
                <button type="submit" class="w-full py-2 px-4 bg-green-600 text-white rounded">Confirm Check-in</button>
            </form>
            <div id="result" class="hidden mt-4 p-3 rounded"></div>
            <script>
                const form = document.getElementById('checkin-form');
                const result = document.getElementById('result');
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const resp = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    });
                    const data = await resp.json();
                    result.classList.remove('hidden');
                    if (data.success) {
                        result.className = 'mt-4 p-3 rounded bg-green-100 text-green-800';
                        result.textContent = data.message;
                    } else {
                        result.className = 'mt-4 p-3 rounded bg-red-100 text-red-800';
                        result.textContent = data.message || 'Check-in failed';
                    }
                });
            </script>
        @endguest
    </div>
</body>
</html>