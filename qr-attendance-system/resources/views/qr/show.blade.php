<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QR Code</title>
    <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white shadow rounded p-6 text-center">
        <h1 class="text-xl font-bold mb-4">{{ $qrSession->title }}</h1>
        <button id="load-qr" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded">Load QR</button>
        <div>
            <img id="qr-img" class="mx-auto" alt="QR Code" />
        </div>
    </div>
    <script>
        document.getElementById('load-qr').addEventListener('click', async () => {
            const resp = await fetch('{{ route('qr.generate', $qrSession->id) }}');
            const data = await resp.json();
            document.getElementById('qr-img').src = data.qr_code;
        });
    </script>
</body>
</html>