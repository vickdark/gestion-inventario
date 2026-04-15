<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            if ($setting->type === 'file') {
                if ($request->hasFile($setting->key)) {
                    // Eliminar logo anterior si existe
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    $path = $request->file($setting->key)->store('settings', 'public');
                    $setting->update(['value' => $path]);
                }
            } else {
                if ($request->has($setting->key)) {
                    $setting->update(['value' => $request->get($setting->key)]);
                }
            }
        }

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
