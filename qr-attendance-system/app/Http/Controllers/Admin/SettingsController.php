<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    public function index()
    {
        $enabled = (bool) Setting::get('whatsapp.enabled', false);
        $provider = Setting::get('whatsapp.provider', 'meta');
        $meta = Setting::get('whatsapp.meta', []);
        $twilio = Setting::get('whatsapp.twilio', []);
        $summaryRecipients = Setting::get('whatsapp.summary_recipients', '');

        return view('admin.settings.index', compact('enabled', 'provider', 'meta', 'twilio', 'summaryRecipients'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'whatsapp_enabled' => 'sometimes|boolean',
            'whatsapp_provider' => 'required|in:meta,twilio',
            'meta_token' => 'nullable|string',
            'meta_phone_number_id' => 'nullable|string',
            'twilio_sid' => 'nullable|string',
            'twilio_token' => 'nullable|string',
            'twilio_from' => 'nullable|string',
            'summary_recipients' => 'nullable|string',
        ]);

        Setting::set('whatsapp.enabled', (bool) ($data['whatsapp_enabled'] ?? false));
        Setting::set('whatsapp.provider', $data['whatsapp_provider']);
        Setting::set('whatsapp.meta', [
            'token' => $data['meta_token'] ?? null,
            'phone_number_id' => $data['meta_phone_number_id'] ?? null,
        ]);
        Setting::set('whatsapp.twilio', [
            'sid' => $data['twilio_sid'] ?? null,
            'token' => $data['twilio_token'] ?? null,
            'from' => $data['twilio_from'] ?? null,
        ]);
        Setting::set('whatsapp.summary_recipients', $data['summary_recipients'] ?? '');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}