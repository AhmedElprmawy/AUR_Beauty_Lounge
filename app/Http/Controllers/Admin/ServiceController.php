<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'features_ar' => 'required|array|min:1',
            'features_en' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['features_ar'] = array_filter($validated['features_ar']);
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->order ?? 0;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . rand(1000,9999) . '.' . $extension;
            $imagePath = $file->storeAs('services', $filename, 'public');
            $validated['image_path'] = $imagePath;
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', '✅ تم إضافة الخدمة بنجاح');
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'features_ar' => 'required|array|min:1',
            'features_en' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['features_ar'] = array_filter($validated['features_ar']);
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? 0;

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . rand(1000,9999) . '.' . $extension;
            $imagePath = $file->storeAs('services', $filename, 'public');
            $validated['image_path'] = $imagePath;
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', '✅ تم تحديث الخدمة بنجاح');
    }

    public function destroy(Service $service)
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }
        
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', '✅ تم حذف الخدمة بنجاح');
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return response()->json(['success' => true]);
    }
}