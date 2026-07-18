<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Banner;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = $request->except(['_token', '_method']);

        foreach ($fields as $key => $value) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::set($key, $path);
            } else {
                Setting::set($key, $value);
            }
        }

        return back()->with('success', 'Store settings updated successfully.');
    }

    public function banners()
    {
        $banners = Banner::orderBy('order', 'ASC')->get();

        return view('admin.settings.banners', compact('banners'));
    }

    public function createBanner()
    {
        return view('admin.settings.banner_create');
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'status' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'link' => $request->link,
            'button_text' => $request->button_text,
            'order' => $request->order ?? 0,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Homepage banner created. Saved path: '.$banner->image_path);
    }

    public function editBanner($id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin.settings.banner_edit', compact('banner'));
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'status' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link,
            'button_text' => $request->button_text,
            'order' => $request->order ?? 0,
            'status' => $request->boolean('status'),
        ];

        $imageMsg = '';
        if ($request->hasFile('image')) {
            $banner->deleteImageFile();
            $data['image_path'] = $request->file('image')->store('banners', 'public');
            $imageMsg = ' Image updated in SQL: '.$data['image_path'];
        }

        $banner->update($data);

        return redirect()->route('admin.banners.edit', $banner->id)
            ->with('success', 'Homepage banner updated successfully.'.$imageMsg);
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->deleteImageFile();
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Homepage banner deleted successfully.');
    }
}
