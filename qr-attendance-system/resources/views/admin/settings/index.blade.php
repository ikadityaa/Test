<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}" />
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Global Settings</h1>
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            <div class="flex items-center gap-3">
                <input type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" {{ $enabled ? 'checked' : '' }} />
                <label for="whatsapp_enabled" class="font-medium">Enable WhatsApp Notifications</label>
            </div>
            <div>
                <label class="block font-medium mb-1">Provider</label>
                <select name="whatsapp_provider" class="border rounded p-2 w-full">
                    <option value="meta" {{ $provider==='meta' ? 'selected' : '' }}>Meta WhatsApp Cloud API</option>
                    <option value="twilio" {{ $provider==='twilio' ? 'selected' : '' }}>Twilio WhatsApp</option>
                </select>
            </div>
            <div class="p-4 border rounded">
                <h2 class="font-semibold mb-2">Meta (Cloud API)</h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Access Token</label>
                        <input type="text" name="meta_token" value="{{ $meta['token'] ?? '' }}" class="border rounded p-2 w-full" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Phone Number ID</label>
                        <input type="text" name="meta_phone_number_id" value="{{ $meta['phone_number_id'] ?? '' }}" class="border rounded p-2 w-full" />
                    </div>
                </div>
            </div>
            <div class="p-4 border rounded">
                <h2 class="font-semibold mb-2">Twilio</h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm mb-1">SID</label>
                        <input type="text" name="twilio_sid" value="{{ $twilio['sid'] ?? '' }}" class="border rounded p-2 w-full" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Token</label>
                        <input type="text" name="twilio_token" value="{{ $twilio['token'] ?? '' }}" class="border rounded p-2 w-full" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1">From (e.g., whatsapp:+14155238886)</label>
                        <input type="text" name="twilio_from" value="{{ $twilio['from'] ?? '' }}" class="border rounded p-2 w-full" />
                    </div>
                </div>
            </div>
            <div>
                <label class="block font-medium mb-1">Summary Recipients (comma-separated E.164 phone numbers)</label>
                <input type="text" name="summary_recipients" value="{{ is_array($summaryRecipients) ? implode(',', $summaryRecipients) : $summaryRecipients }}" class="border rounded p-2 w-full" />
            </div>
            <div>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save Settings</button>
            </div>
        </form>
    </div>
</body>
</html>