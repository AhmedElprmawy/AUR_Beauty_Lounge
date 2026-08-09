@extends('admin.layouts.admin')

@section('title', 'إضافة صورة')

@section('page-title', '➕ إضافة صورة إلى المعرض')

@section('content')

<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        color: var(--text-muted);
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Cairo', sans-serif;
        font-size: 15px;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--primary-gold);
        outline: none;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
    }

    .form-group input[type="file"] {
        padding: 10px;
        background: var(--surface-dark);
    }

    .form-group input[type="file"]::-webkit-file-upload-button {
        background: var(--primary-gold);
        color: #000;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        margin-left: 10px;
        transition: 0.3s;
    }

    .form-group input[type="file"]::-webkit-file-upload-button:hover {
        background: var(--dark-gold);
    }

    .image-preview {
        margin-top: 10px;
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        display: none;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .image-preview.show {
        display: block;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: var(--primary-gold);
    }

    .checkbox-group label {
        margin: 0;
        font-size: 15px;
        color: var(--text-main);
        cursor: pointer;
    }

    .helper-text {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .btn-submit {
        padding: 14px 24px;
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: var(--dark-gold);
        transform: translateY(-2px);
    }

    .btn-cancel {
        padding: 14px 24px;
        background: var(--surface-light);
        color: var(--text-main);
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-cancel:hover {
        background: var(--border-color);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-container">
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>العنوان (عربي)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar') }}" placeholder="مثال: إطلالة ذهبية فاخرة">
            </div>
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Example: Luxury Golden Look">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>التصنيف *</label>
                <select name="category" required>
                    <option value="">اختر التصنيف</option>
                    <option value="bridal" {{ old('category') == 'bridal' ? 'selected' : '' }}>👰 عرائس (Bridal)</option>
                    <option value="makeup" {{ old('category') == 'makeup' ? 'selected' : '' }}>💄 مكياج (Makeup)</option>
                    <option value="fashion" {{ old('category') == 'fashion' ? 'selected' : '' }}>👗 أزياء (Fashion)</option>
                    <option value="hair" {{ old('category') == 'hair' ? 'selected' : '' }}>✂️ شعر (Hair)</option>
                    <option value="skin" {{ old('category') == 'skin' ? 'selected' : '' }}>✨ بشرة (Skin)</option>
                    <option value="all" {{ old('category') == 'all' ? 'selected' : '' }}>📁 الكل (All)</option>
                </select>
                @error('category')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>الترتيب</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" placeholder="0">
                <div class="helper-text">رقم أصغر = ظهور أولاً</div>
            </div>
        </div>

        {{-- ✅ رفع الصورة --}}
        <div class="form-group">
            <label>📸 الصورة *</label>
            <input type="file" name="image" accept="image/*" onchange="previewImage(event)" required>
            <div class="helper-text">الصيغ المدعومة: JPG, PNG, GIF, WEBP - الحد الأقصى: 2MB</div>
            <div id="imagePreview" class="image-preview">
                <img id="previewImg" src="" alt="معاينة الصورة">
            </div>
            @error('image')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>التسمية (Caption)</label>
            <input type="text" name="caption" value="{{ old('caption') }}" placeholder="وصف قصير للصورة">
        </div>

        <div class="form-row">
            <div class="checkbox-group">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <label for="is_featured">⭐ مميزة</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active">✅ نشط</label>
            </div>
        </div>

        <div style="display:flex; gap:14px; margin-top:24px; flex-wrap:wrap;">
            <button type="submit" class="btn-submit">💾 حفظ الصورة</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn-cancel">❌ إلغاء</a>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');
        
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.add('show');
            };
            reader.readAsDataURL(event.target.files[0]);
        } else {
            preview.classList.remove('show');
        }
    }
</script>

@endsection