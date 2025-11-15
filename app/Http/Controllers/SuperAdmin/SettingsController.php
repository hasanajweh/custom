<?php
// app/Http/Controllers/SuperAdmin/SettingsController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Get all settings as key-value pairs
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // If no settings exist, use defaults
        if (empty($settings)) {
            $settings = $this->getDefaultSettings();
        }

        return view('superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'default_storage_limit' => 'required|integer|min:1',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'support_email' => 'nullable|email',
            'from_email' => 'nullable|email',
            'allow_registration' => 'boolean',
            'maintenance_mode' => 'boolean',
        ]);

        // Update or create each setting
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Handle checkboxes that might not be sent if unchecked
        if (!$request->has('allow_registration')) {
            Setting::updateOrCreate(['key' => 'allow_registration'], ['value' => '0']);
        }

        if (!$request->has('maintenance_mode')) {
            Setting::updateOrCreate(['key' => 'maintenance_mode'], ['value' => '0']);
        }

        return redirect()->route('superadmin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    private function getDefaultSettings()
    {
        return [
            'site_name' => 'aJw',
            'default_storage_limit' => 10,
            'currency_symbol' => '$',
            'tax_rate' => 0,
            'support_email' => '',
            'from_email' => '',
            'allow_registration' => 1,
            'maintenance_mode' => 0,
        ];
    }
}
