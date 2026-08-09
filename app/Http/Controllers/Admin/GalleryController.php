<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('order')->get();
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'category' => 'required|in:bridal,makeup,fashion,hair,skin,all',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['order'] = $request->order ?? 0;

        // ✅ رفع الصورة إلى public/images/gallery/
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = public_path('images/gallery');
            
            // إنشاء المجلد إذا لم يكن موجوداً
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            $image->move($path, $filename);
            $validated['image'] = 'images/gallery/' . $filename;
        }

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', '✅ تم إضافة الصورة بنجاح');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'category' => 'required|in:bridal,makeup,fashion,hair,skin,all',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['order'] = $request->order ?? 0;

        // ✅ تحديث الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($gallery->image && File::exists(public_path($gallery->image))) {
                File::delete(public_path($gallery->image));
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = public_path('images/gallery');
            
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            $image->move($path, $filename);
            $validated['image'] = 'images/gallery/' . $filename;
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', '✅ تم تحديث الصورة بنجاح');
    }

    public function destroy(Gallery $gallery)
    {
        // ✅ حذف الصورة
        if ($gallery->image && File::exists(public_path($gallery->image))) {
            File::delete(public_path($gallery->image));
        }

        $gallery->delete();
        return redirect()->route('admin.gallery.index')
            ->with('success', '✅ تم حذف الصورة بنجاح');
    }

    public function toggleStatus(Gallery $gallery)
    {
        $gallery->update(['is_active' => !$gallery->is_active]);
        return response()->json(['success' => true]);
    }

    public function toggleFeatured(Gallery $gallery)
    {
        $gallery->update(['is_featured' => !$gallery->is_featured]);
        return response()->json(['success' => true]);
    }
}