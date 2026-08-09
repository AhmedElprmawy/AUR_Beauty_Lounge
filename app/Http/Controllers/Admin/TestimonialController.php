<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
    'client_name' => 'required|string|max:255',
    'role_ar' => 'nullable|string|max:255',  // ✅ اختياري
    'role_en' => 'nullable|string|max:255',
    'content_ar' => 'required|string',
    'content_en' => 'nullable|string',
    'rating' => 'nullable|integer|min:1|max:5',
    'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    'is_active' => 'nullable|boolean'
]);

        // ✅ معالجة is_active
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['rating'] = $request->rating ?? 5;
        $validated['role_ar'] = $request->role_ar ?? 'عميل';

        // ✅ رفع الصورة
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = public_path('images/testimonials');
            
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            $image->move($path, $filename);
            $validated['avatar'] = 'images/testimonials/' . $filename;
        }

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', '✅ تم إضافة الرأي بنجاح');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'role_ar' => 'required|string|max:255',  // ✅ مطلوب
            'role_en' => 'nullable|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['rating'] = $request->rating ?? 5;

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar && File::exists(public_path($testimonial->avatar))) {
                File::delete(public_path($testimonial->avatar));
            }
            
            $image = $request->file('avatar');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = public_path('images/testimonials');
            
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            $image->move($path, $filename);
            $validated['avatar'] = 'images/testimonials/' . $filename;
        }

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', '✅ تم تحديث الرأي بنجاح');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar && File::exists(public_path($testimonial->avatar))) {
            File::delete(public_path($testimonial->avatar));
        }

        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')
            ->with('success', '✅ تم حذف الرأي بنجاح');
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => !$testimonial->is_active]);
        return response()->json(['success' => true]);
    }
}