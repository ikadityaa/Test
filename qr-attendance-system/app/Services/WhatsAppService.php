<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function isEnabled(): bool
    {
        return (bool) (Setting::get('whatsapp.enabled', false));
    }

    public function sendMessage(string $toPhoneE164, string $message): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $provider = Setting::get('whatsapp.provider', 'meta');
        if ($provider === 'twilio') {
            return $this->sendViaTwilio($toPhoneE164, $message);
        }

        return $this->sendViaMeta($toPhoneE164, $message);
    }

    private function sendViaMeta(string $toE164, string $message): bool
    {
        $config = Setting::get('whatsapp.meta', []);
        $token = $config['token'] ?? env('WHATSAPP_META_TOKEN');
        $fromPhoneId = $config['phone_number_id'] ?? env('WHATSAPP_META_PHONE_NUMBER_ID');

        if (!$token || !$fromPhoneId) {
            return false;
        }

        $url = "https://graph.facebook.com/v19.0/{$fromPhoneId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $toE164,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($url, $payload);

        return $response->successful();
    }

    private function sendViaTwilio(string $toE164, string $message): bool
    {
        $config = Setting::get('whatsapp.twilio', []);
        $sid = $config['sid'] ?? env('TWILIO_SID');
        $token = $config['token'] ?? env('TWILIO_TOKEN');
        $from = $config['from'] ?? env('TWILIO_WHATSAPP_FROM');

        if (!$sid || !$token || !$from) {
            return false;
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post($url, [
                'From' => $from, // e.g., 'whatsapp:+14155238886'
                'To' => 'whatsapp:' . $toE164,
                'Body' => $message,
            ]);

        return $response->successful();
    }

    public function getSummaryRecipients(): array
    {
        $raw = Setting::get('whatsapp.summary_recipients', '');
        if (is_array($raw)) {
            return $raw;
        }
        return collect(explode(',', (string) $raw))
            ->map(fn ($p) => trim($p))
            ->filter()
            ->values()
            ->all();
    }
}