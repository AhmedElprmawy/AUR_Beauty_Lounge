<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransformationController extends Controller
{
    public function index()
    {
        $transformations = Transformation::orderBy('order')->get();
        return view('admin.transformations.index', compact('transformations'));
    }

    public function create()
    {
        return view('admin.transformations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'category' => 'required|in:makeup,hair,skincare',
            'before_image' => 'required|image|mimes:jpeg,png,jpg,gif,webm|max:2048',
            'after_image' => 'required|image|mimes:jpeg,png,jpg,gif,webm|max:2048',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:20480', // ✅ تمت إضافة الفيديو
            'order' => 'nullable|integer'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? 0;

        if (empty($validated['description_ar'])) {
            $validated['description_ar'] = 'تحول مذهل في المظهر';
        }

        if ($request->hasFile('before_image')) {
            $beforePath = $request->file('before_image')->store('transformations/before', 'public');
            $validated['before_image'] = $beforePath;
        }

        if ($request->hasFile('after_image')) {
            $afterPath = $request->file('after_image')->store('transformations/after', 'public');
            $validated['after_image'] = $afterPath;
        }

        // ✅ إنشاء السجل في الداتابيز
        $transformation = Transformation::create($validated);

        // ✅ إضافة معالجة الفيديو بعد الإنشاء
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('transformations/videos', 'public');
            $transformation->update(['video' => $videoPath]);
        }

        return redirect()->route('admin.transformations.index')
            ->with('success', '✅ تم إضافة التحول بنجاح');
    }

    public function edit(Transformation $transformation)
    {
        return view('admin.transformations.edit', compact('transformation'));
    }

    public function update(Request $request, Transformation $transformation)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'category' => 'required|in:makeup,hair,skincare',
            'before_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webm|max:2048',
            'after_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webm|max:2048',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:20480', // ✅ تمت إضافة الفيديو
            'order' => 'nullable|integer'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? 0;

        // ✅ تحديث صورة "قبل"
        if ($request->hasFile('before_image')) {
            if ($transformation->before_image) {
                Storage::disk('public')->delete($transformation->before_image);
            }
            $beforePath = $request->file('before_image')->store('transformations/before', 'public');
            $validated['before_image'] = $beforePath;
        }

        // ✅ تحديث صورة "بعد"
        if ($request->hasFile('after_image')) {
            if ($transformation->after_image) {
                Storage::disk('public')->delete($transformation->after_image);
            }
            $afterPath = $request->file('after_image')->store('transformations/after', 'public');
            $validated['after_image'] = $afterPath;
        }

        // ✅ تحديث الفيديو
        if ($request->hasFile('video')) {
            // حذف الفيديو القديم إذا وجد
            if ($transformation->video) {
                Storage::disk('public')->delete($transformation->video);
            }
            $videoPath = $request->file('video')->store('transformations/videos', 'public');
            $validated['video'] = $videoPath;
        }

        $transformation->update($validated);

        return redirect()->route('admin.transformations.index')
            ->with('success', '✅ تم تحديث التحول بنجاح');
    }

    public function destroy(Transformation $transformation)
    {
        // ✅ حذف الصور والفيديو
        if ($transformation->before_image) {
            Storage::disk('public')->delete($transformation->before_image);
        }
        if ($transformation->after_image) {
            Storage::disk('public')->delete($transformation->after_image);
        }
        if ($transformation->video) {
            Storage::disk('public')->delete($transformation->video);
        }

        $transformation->delete();
        return redirect()->route('admin.transformations.index')
            ->with('success', '✅ تم حذف التحول بنجاح');
    }

    public function toggleStatus(Transformation $transformation)
    {
        $transformation->update(['is_active' => !$transformation->is_active]);
        return response()->json(['success' => true]);
    }
}