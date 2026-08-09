@extends('admin.layouts.admin')

@section('title', 'إضافة تحول جديد')

@section('page-title', '➕ إضافة تحول قبل وبعد')

@section('content')

<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Cairo', sans-serif;
        font-size: 15px;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: var(--primary-gold);
        outline: none;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
    }

    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: var(--text-muted);
        font-size: 14px;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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
        padding: 14px 40px;
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: var(--dark-gold);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .btn-cancel {
        padding: 14px 30px;
        background: var(--surface-light);
        color: var(--text-main);
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-cancel:hover {
        background: var(--border-color);
    }

    .info-box {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.15);
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .info-box .label {
        color: var(--text-muted);
        font-size: 13px;
    }

    .info-box .value {
        font-weight: 600;
        color: var(--primary-gold);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .info-box {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="form-container">

    <div class="info-box">
        <div>
            <span class="label">📌 إضافة تحول جديد</span>
            <span class="value">قم بتحميل صور قبل وبعد</span>
        </div>
    </div>

    <form action="{{ route('admin.transformations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- العنوان -->
        <div class="form-row">
            <div class="form-group">
                <label>العنوان (عربي) *</label>
                <input type="text" name="title_ar" value="{{ old('title_ar') }}" required placeholder="مثال: صبغة ذهبية + كيراتين">
                @error('title_ar')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Example: Golden Dye + Keratin">
                @error('title_en')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- الفئة -->
        <div class="form-group">
            <label>الفئة *</label>
            <select name="category" required>
                <option value="">اختر الفئة</option>
                <option value="makeup" {{ old('category') == 'makeup' ? 'selected' : '' }}>💄 مكياج</option>
                <option value="hair" {{ old('category') == 'hair' ? 'selected' : '' }}>✂️ شعر</option>
                <option value="skincare" {{ old('category') == 'skincare' ? 'selected' : '' }}>✨ عناية بالبشرة</option>
            </select>
            @error('category')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- صور قبل وبعد -->
        <div class="form-row">
            <div class="form-group">
                <label>📸 صورة "قبل" *</label>
                <input type="file" name="before_image" accept="image/*" onchange="previewImage(event, 'beforePreview')" required>
                <div class="helper-text">الصيغ: JPG, PNG, GIF, WEBP - الحد الأقصى: 2MB</div>
                <div id="beforePreview" class="image-preview">
                    <img id="beforeImg" src="" alt="معاينة قبل">
                </div>
                @error('before_image')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>📸 صورة "بعد" *</label>
                <input type="file" name="after_image" accept="image/*" onchange="previewImage(event, 'afterPreview')" required>
                <div class="helper-text">الصيغ: JPG, PNG, GIF, WEBP - الحد الأقصى: 2MB</div>
                <div id="afterPreview" class="image-preview">
                    <img id="afterImg" src="" alt="معاينة بعد">
                </div>
                @error('after_image')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- الوصف -->
      <div class="form-group">
    <label>الوصف (عربي)</label>
    <textarea name="description_ar" rows="3" placeholder="وصف التحول بالعربية...">{{ old('description_ar', 'تحول مذهل في المظهر') }}</textarea>
    @error('description_ar')
        <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label>الوصف (إنجليزي)</label>
    <textarea name="description_en" rows="3" placeholder="Description in English...">{{ old('description_en', 'Amazing transformation') }}</textarea>
    @error('description_en')
        <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
    @enderror
</div>

        <!-- الترتيب والتفعيل -->
        <div class="form-row">
            <div class="form-group">
                <label>📊 الترتيب</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" placeholder="0">
                <div class="helper-text">رقم أصغر = ظهور أولاً</div>
                @error('order')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="checkbox-group" style="padding-top: 30px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active">✅ نشط (يظهر في الموقع)</label>
            </div>
        </div>

        <!-- الأزرار -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ التحول</button>
            <a href="{{ route('admin.transformations.index') }}" class="btn-cancel">❌ إلغاء</a>
        </div>
    </form>
</div>

<script>
    // ===== معاينة الصورة =====
    function previewImage(event, previewId) {
        const preview = document.getElementById(previewId);
        const img = preview.querySelector('img');

        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.add('show');
            };
            reader.readAsDataURL(event.target.files[0]);
        } else {
            img.src = '';
            preview.classList.remove('show');
        }
    }
</script>

@endsection