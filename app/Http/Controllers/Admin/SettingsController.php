<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $logo = Setting::get('company_logo');
        
        return view('admin.settings', compact('logo'));
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $file = $request->file('logo');
        
        // Delete old logo if exists
        $oldLogo = Setting::get('company_logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        // Store new logo
        $path = $file->store('logos', 'public');
        
        Setting::set('company_logo', $path);

        return back()->with('success', 'Logo uploaded successfully.');
    }

    public function deleteLogo()
    {
        $logo = Setting::get('company_logo');
        
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }

        Setting::set('company_logo', null);

        return back()->with('success', 'Logo deleted successfully.');
    }
}
