<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('pages.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                if ($setting->type === 'file' && $request->hasFile($key)) {
                    // Handle image upload
                    $result = $this->imageService->processAndStore($request->file($key));
                    if ($result['success']) {
                        // Delete old file if exists
                        if ($setting->value) {
                            $this->imageService->deleteImage($setting->value);
                        }
                        $setting->update(['value' => $result['processed_path']]);
                    }
                } else {
                    $setting->update(['value' => $value]);
                }
            }
        }

        return redirect()->back()->with(['message' => 'Settings updated successfully', 'alert' => 'alert-success']);
    }
}
