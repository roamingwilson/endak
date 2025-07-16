<?php

namespace App\Http\Controllers\Admin;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(){
        $settings = Settings::first();

        return view('admin.settings.index' , compact('settings'));
    }

    public function edit($setting)
    {
        $settings = Settings::findOrFail($setting);
        return view('admin.settings.edit', compact('settings'));
    }
    public function update(Request $request, $setting)
    {
        $settings = Settings::findOrFail($setting);
        $data = $request->except(['_token']);
        // Update the WhatsApp offer template
        if ($request->filled('whatsapp_offer_template')) {
            $data['whatsapp_offer_template'] = $request->whatsapp_offer_template;
        }
        $settings->update($data);
        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('logo')) {
            return;
        } else {
            $file = $request->file('logo');
            $path = $file->store('settings', [
                'disk' => 'public',
            ]);
            return $path;
        }
    }
}
