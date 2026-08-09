@extends('admin.layouts.admin')

@section('title', 'إضافة فيديو')

@section('page-title', '➕ إضافة فيديو جديد')

@section('content')

<style>
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; color: var(--text-muted); font-weight: 600; margin-bottom: 8px; font-size: 14px; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 12px 16px; border-radius: 8px;
        border: 1px solid var(--border-color); background: var(--bg-dark);
        color: var(--text-main); font-family: 'Cairo', sans-serif; font-size: 15px;
        transition: 0.3s;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        border-color: var(--primary-gold); outline: none; box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
    }
    .form-group textarea { min-height: 80px; resize: vertical; }
    .form-group input[type="file"] { padding: 10px; background: var(--surface-dark); }
    .form-group input[type="file"]::-webkit-file-upload-button {
        background: var(--primary-gold); color: #000; border: none;
        padding: 8px 16px; border-radius: 6px; cursor: pointer;
        font-family: 'Cairo', sans-serif; font-weight: 600; margin-left: 10px;
        transition: 0.3s;
    }
    .form-group input[type="file"]::-webkit-file-upload-button:hover { background: var(--dark-gold); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .video-preview { margin-top: 10px; max-width: 300px; border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; display: none; text-align: center; padding: 10px; background: #000; }
    .video-preview video { width: 100%; border-radius: 8px; }
    .video-preview.show { display: block; }
    .checkbox-group { display: flex; align-items: center; gap: 10px; padding-top: 10px; }
    .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--primary-gold); }
    .checkbox-group label { margin: 0; font-size: 15px; color: var(--text-main); cursor: pointer; }
    .helper-text { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    .btn-submit { padding: 14px 40px; background: var(--primary-gold); color: #000; border: none; border-radius: 8px; font-size: 18px; font-weight: 800; cursor: pointer; font-family: 'Cairo', sans-serif; transition: 0.3s; }
    .btn-submit:hover { background: var(--dark-gold); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3); }
    .btn-cancel { padding: 14px 30px; background: var(--surface-light); color: var(--text-main); border: none; border-radius: 8px; font-size: 16px; cursor: pointer; font-family: 'Cairo', sans-serif; text-decoration: none; display: inline-block; transition: 0.3s; }
    .btn-cancel:hover { background: var(--border-color); }
</style>

<div class="form-container">
    <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>العنوان (عربي) *</label>
                <input type="text" name="title_ar" value="{{ old('title_ar') }}" required placeholder="مثال: طريقة فرد الشعر">
                @error('title_ar') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Example: Hair Straightening">
                @error('title_en') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>الوصف (عربي)</label>
            <textarea name="description_ar" rows="3" placeholder="وصف الفيديو...">{{ old('description_ar') }}</textarea>
            @error('description_ar') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>الوصف (إنجليزي)</label>
            <textarea name="description_en" rows="3" placeholder="Description...">{{ old('description_en') }}</textarea>
            @error('description_en') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>🎥 فيديو *</label>
            <input type="file" name="video" accept="video/*" onchange="previewVideo(event, 'videoPreview')" required>
            <div class="helper-text">الصيغ: MP4, MOV, AVI, WEBM - الحد الأقصى: 20MB</div>
            <div id="videoPreview" class="video-preview">
                <video id="videoPlayer" controls>
                    <source src="" type="video/mp4">
                    متصفحك لا يدعم تشغيل الفيديو.
                </video>
            </div>
            @error('video') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="checkbox-group" style="padding-top: 10px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active">✅ نشط (يظهر في الموقع)</label>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ الفيديو</button>
            <a href="{{ route('admin.videos.index') }}" class="btn-cancel">❌ إلغاء</a>
        </div>
    </form>
</div>

<script>
    function previewVideo(event, previewId) {
        const preview = document.getElementById(previewId);
        const video = preview.querySelector('video');
        if (event.target.files && event.target.files[0]) {
            const file = event.target.files[0];
            const url = URL.createObjectURL(file);
            video.src = url;
            preview.classList.add('show');
        } else {
            video.src = '';
            preview.classList.remove('show');
        }
    }
</script>
@endsection