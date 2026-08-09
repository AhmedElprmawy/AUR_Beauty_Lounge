@extends('admin.layouts.admin')

@section('title', 'تعديل صورة')

@section('page-title', '✏️ تعديل صورة المعرض')

@section('content')

<style>
    /* نفس الـ Styles من صفحة الـ Create */
    .form-container { max-width: 700px; margin: 0 auto; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; font-size: 14px; }
    .form-group input, .form-group select { width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-dark); color: var(--text-main); font-family: 'Cairo', sans-serif; font-size: 15px; transition: 0.3s; }
    .form-group input:focus, .form-group select:focus { border-color: var(--primary-gold); outline: none; box-shadow: 0 0 20px rgba(212, 175, 55, 0.1); }
    .form-group input[type="file"] { padding: 10px; background: var(--surface-dark); }
    .form-group input[type="file"]::-webkit-file-upload-button { background: var(--primary-gold); color: #000; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-family: 'Cairo', sans-serif; font-weight: 600; margin-left: 10px; transition: 0.3s; }
    .form-group input[type="file"]::-webkit-file-upload-button:hover { background: var(--dark-gold); }
    .image-preview { margin-top: 10px; max-width: 200px; border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; }
    .image-preview img { width: 100%; height: auto; display: block; }
    .image-preview .label { font-size: 11px; color: var(--text-muted); text-align: center; padding: 4px; background: var(--bg-dark); }
    .checkbox-group { display: flex; align-items: center; gap: 10px; padding-top: 10px; }
    .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--primary-gold); }
    .checkbox-group label { margin: 0; font-size: 15px; color: var(--text-main); cursor: pointer; }
    .helper-text { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    .btn-submit { padding: 14px 24px; background: var(--primary-gold); color: #000; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Cairo', sans-serif; transition: 0.3s; }
    .btn-submit:hover { background: var(--dark-gold); transform: translateY(-2px); }
    .btn-cancel { padding: 14px 24px; background: var(--surface-light); color: var(--text-main); border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Cairo', sans-serif; text-decoration: none; display: inline-block; transition: 0.3s; }
    .btn-cancel:hover { background: var(--border-color); }
    .btn-danger { padding: 14px 24px; background: var(--danger-color); color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Cairo', sans-serif; transition: 0.3s; text-decoration: none; display: inline-block; }
    .btn-danger:hover { background: #cc0000; transform: translateY(-2px); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .info-box { background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 8px; padding: 15px 20px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .info-box .label { color: var(--text-muted); font-size: 13px; }
    .info-box .value { font-weight: 600; color: var(--primary-gold); }
    .status-badge { padding: 4px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; }
    .status-active { background: rgba(76, 175, 80, 0.2); color: var(--success-color); }
    .status-inactive { background: rgba(255, 77, 77, 0.2); color: var(--danger-color); }
    @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
</style>

<div class="form-container">

    <div class="info-box">
        <div>
            <span class="label">🆔 رقم الصورة:</span>
            <span class="value">#{{ $gallery->id }}</span>
        </div>
        <div>
            <span class="label">📅 تاريخ الإنشاء:</span>
            <span class="value">{{ $gallery->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div>
            <span class="label">📊 الحالة:</span>
            <span class="status-badge {{ $gallery->is_active ? 'status-active' : 'status-inactive' }}">
                {{ $gallery->is_active ? '✅ نشط' : '❌ غير نشط' }}
            </span>
        </div>
    </div>

    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label>العنوان (عربي)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $gallery->title_ar) }}" placeholder="مثال: إطلالة ذهبية فاخرة">
            </div>
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en', $gallery->title_en) }}" placeholder="Example: Luxury Golden Look">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>التصنيف *</label>
                <select name="category" required>
                    <option value="bridal" {{ old('category', $gallery->category) == 'bridal' ? 'selected' : '' }}>👰 عرائس (Bridal)</option>
                    <option value="makeup" {{ old('category', $gallery->category) == 'makeup' ? 'selected' : '' }}>💄 مكياج (Makeup)</option>
                    <option value="fashion" {{ old('category', $gallery->category) == 'fashion' ? 'selected' : '' }}>👗 أزياء (Fashion)</option>
                    <option value="hair" {{ old('category', $gallery->category) == 'hair' ? 'selected' : '' }}>✂️ شعر (Hair)</option>
                    <option value="skin" {{ old('category', $gallery->category) == 'skin' ? 'selected' : '' }}>✨ بشرة (Skin)</option>
                    <option value="all" {{ old('category', $gallery->category) == 'all' ? 'selected' : '' }}>📁 الكل (All)</option>
                </select>
                @error('category')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>الترتيب</label>
                <input type="number" name="order" value="{{ old('order', $gallery->order) }}" placeholder="0">
                <div class="helper-text">رقم أصغر = ظهور أولاً</div>
            </div>
        </div>

        {{-- ✅ رفع الصورة --}}
        <div class="form-group">
            <label>📸 الصورة</label>
            @if($gallery->image)
                <div class="image-preview">
                    <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title_ar }}">
                    <div class="label">الصورة الحالية</div>
                </div>
            @endif
            <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
            <div class="helper-text">اتركه فارغاً للاحتفاظ بالصورة الحالية</div>
            <div id="imagePreview" class="image-preview" style="display: none;">
                <img id="previewImg" src="" alt="معاينة الصورة الجديدة">
            </div>
            @error('image')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>التسمية (Caption)</label>
            <input type="text" name="caption" value="{{ old('caption', $gallery->caption) }}" placeholder="وصف قصير للصورة">
        </div>

        <div class="form-row">
            <div class="checkbox-group">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
                <label for="is_featured">⭐ مميزة</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                <label for="is_active">✅ نشط</label>
            </div>
        </div>

        <div style="display:flex; gap:14px; margin-top:24px; flex-wrap:wrap;">
            <button type="submit" class="btn-submit">💾 حفظ التغييرات</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn-cancel">❌ إلغاء</a>
            <button type="button" class="btn-danger" onclick="confirmDelete({{ $gallery->id }})">🗑️ حذف الصورة</button>
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
                preview.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    function confirmDelete(id) {
        if (confirm('⚠️ هل أنت متأكد من حذف هذه الصورة؟')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/gallery/${id}`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

@endsection