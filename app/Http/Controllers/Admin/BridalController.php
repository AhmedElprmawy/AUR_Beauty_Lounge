<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bridal;
use Illuminate\Http\Request;

class BridalController extends Controller
{
    public function index()
    {
        $bridal = Bridal::first();
        return view('admin.bridal.index', compact('bridal'));
    }

    public function create()
    {
        return view('admin.bridal.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'small_image' => 'nullable|string|max:255',
            'stats_number' => 'required|string|max:50',
            'stats_label_ar' => 'required|string|max:255',
            'stats_label_en' => 'nullable|string|max:255',
            'features_ar' => 'required|array|min:1',
            'features_en' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['features_ar'] = array_filter($validated['features_ar']);
        $validated['features_en'] = $validated['features_en'] ? array_filter($validated['features_en']) : [];
        $validated['is_active'] = $request->has('is_active');

        Bridal::create($validated);

        return redirect()->route('admin.bridal.index')
            ->with('success', 'تم إنشاء قسم العرائس بنجاح');
    }

    public function edit(Bridal $bridal)
    {
        return view('admin.bridal.edit', compact('bridal'));
    }

    public function update(Request $request, Bridal $bridal)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'small_image' => 'nullable|string|max:255',
            'stats_number' => 'required|string|max:50',
            'stats_label_ar' => 'required|string|max:255',
            'stats_label_en' => 'nullable|string|max:255',
            'features_ar' => 'required|array|min:1',
            'features_en' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['features_ar'] = array_filter($validated['features_ar']);
        $validated['features_en'] = $validated['features_en'] ? array_filter($validated['features_en']) : [];
        $validated['features_en'] = $validated['features_en'] ? array_filter($validated['features_en']) : [];
        $validated['is_active'] = $request->has('is_active');

        $bridal->update($validated);
        return redirect()->route('admin.bridal.index')
            ->with('success', 'تم تحديث قسم العرائس بنجاح');
    }

    public function destroy(Bridal $bridal)
    {
        $bridal->delete();

        return redirect()->route('admin.bridal.index')
            ->with('success', 'تم حذف قسم العرائس بنجاح');
    }

    public function toggleStatus(Bridal $bridal)
    {
        $bridal->update(['is_active' => !$bridal->is_active]);
        return response()->json(['success' => true]);
    }
}
