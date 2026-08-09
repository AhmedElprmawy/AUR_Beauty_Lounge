<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BridalPackage;
use Illuminate\Http\Request;

class BridalPackageController extends Controller
{
    public function index()
    {
        $packages = BridalPackage::orderBy('order')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'tier' => 'required|in:silver,gold,platinum',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'features_ar' => 'required|array|min:1',
            'features_en' => 'nullable|array',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_popular' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['features_ar'] = array_filter($validated['features_ar']);
        
        // ✅ معالجة الـ Checkbox بشكل صحيح
        $validated['is_popular'] = $request->has('is_popular') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? 0;

        BridalPackage::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', '✅ تم إضافة الباقة بنجاح');
    }

    public function edit(BridalPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, BridalPackage $package)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'tier' => 'required|in:silver,gold,platinum',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'features_ar' => 'required|array|min:1',
            'features_en' => 'nullable|array',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_popular' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['features_ar'] = array_filter($validated['features_ar']);
        
        // ✅ معالجة الـ Checkbox بشكل صحيح
        $validated['is_popular'] = $request->has('is_popular') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? 0;

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', '✅ تم تحديث الباقة بنجاح');
    }

    public function destroy(BridalPackage $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', '✅ تم حذف الباقة بنجاح');
    }
}