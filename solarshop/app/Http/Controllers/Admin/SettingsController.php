<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    use UploadsFiles;

    private const GROUPS = [
        'general' => ['site_name', 'site_tagline', 'logo'],
        'contact' => ['phone_1', 'phone_2', 'email', 'address_1', 'address_2', 'whatsapp_number'],
        'social' => ['facebook', 'instagram', 'youtube', 'linkedin', 'tiktok'],
        'seo' => ['meta_title', 'meta_description'],
        'misc' => ['footer_copyright', 'delivery_note', 'map_embed'],
    ];

    public function index(): View
    {
        return view('admin.settings.index', ['groups' => self::GROUPS]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:500'],
            'phone_1' => ['nullable', 'string', 'max:50'],
            'phone_2' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_1' => ['nullable', 'string', 'max:500'],
            'address_2' => ['nullable', 'string', 'max:500'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'url', 'max:500'],
            'instagram' => ['nullable', 'url', 'max:500'],
            'youtube' => ['nullable', 'url', 'max:500'],
            'linkedin' => ['nullable', 'url', 'max:500'],
            'tiktok' => ['nullable', 'url', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'footer_copyright' => ['nullable', 'string', 'max:500'],
            'delivery_note' => ['nullable', 'string', 'max:500'],
            'map_embed' => ['nullable', 'string', 'max:4000'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,webp,jpg|max:2048']);
            Setting::set('logo', $this->storeUpload($request->file('logo'), 'settings'));
        }

        return redirect()->route('admin.settings.index')->with('success', 'Site settings saved successfully.');
    }
}
