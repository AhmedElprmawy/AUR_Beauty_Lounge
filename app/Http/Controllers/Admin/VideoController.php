<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('created_at', 'desc')->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:1048576',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
            $validated['video'] = $videoPath;
        }

        Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', '✅ تم إضافة الفيديو بنجاح');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:1048576',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        if ($request->hasFile('video')) {
            if ($video->video) {
                Storage::disk('public')->delete($video->video);
            }
            $videoPath = $request->file('video')->store('videos', 'public');
            $validated['video'] = $videoPath;
        }

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', '✅ تم تحديث الفيديو بنجاح');
    }

    public function destroy(Video $video)
    {
        if ($video->video) {
            Storage::disk('public')->delete($video->video);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', '✅ تم حذف الفيديو بنجاح');
    }

    public function toggleStatus(Request $request, Video $video)
    {
        $video->update(['is_active' => !$video->is_active]);
        return response()->json(['success' => true]);
    }
}