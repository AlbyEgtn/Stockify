<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Ambil row setting pertama
     */
    private function getSettingRow()
    {
        return Setting::first() ?? Setting::create([
            'system_name' => 'Stockify'
        ]);
    }

    /**
     * Halaman pengaturan
     */
    public function index()
    {
        $setting = $this->getSettingRow();

        return view('admin.settings.index', [
            'system_name' => Setting::getValue('system_name', 'Stockify'),
            'system_logo' => Setting::getValue('logo')
        ]);
    }

    /**
     * Update pengaturan
     */
    public function update(Request $request)
    {
        $request->validate([
            'system_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
        ]);

        $setting = $this->getSettingRow();

        // Update nama sistem
        $setting->system_name = $request->system_name;

        // Jika centang hapus logo
        if ($request->remove_logo) {

            if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
                Storage::delete('public/' . $setting->logo);
            }

            $setting->logo = null;
        }
        // Jika upload logo baru
        if ($request->hasFile('logo')) {

            // Hapus logo lama jika ada
            if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
                Storage::delete('public/' . $setting->logo);
            }

            $path = $request->file('logo')
                            ->store('logos', 'public');

            $setting->logo = $path;
        }

        $setting->save();

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}