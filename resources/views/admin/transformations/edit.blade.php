@extends('admin.layouts.admin')

@section('title', 'تعديل تحول')

@section('page-title', '✏️ تعديل تحول قبل وبعد')

@section('content')

<style>
    /* نفس الـ Styles بتاع صفحة الـ Create */
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
    .image-preview { margin-top: 10px; max-width: 200px; border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; }
    .image-preview img { width: 100%; height: auto; display: block; }
    .image-preview .label { font-size: 11px; color: var(--text-muted); text-align: center; padding: 4px; background: var(--bg-dark); }
    .checkbox-group { display: flex; align-items: center; gap: 10px; padding-top: 10px; }
    .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--primary-gold); }
    .checkbox-group label { margin: 0; font-size: 15px; color: var(--text-main); cursor: pointer; }
    .helper-text { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    .btn-submit { padding: 14px 40px; background: var(--primary-gold); color: #000; border: none; border-radius: 8px; font-size: 18px; font-weight: 800; cursor: pointer; font-family: 'Cairo', sans-serif; transition: 0.3s; }
    .btn-submit:hover { background: var(--dark-gold); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3); }
    .btn-cancel { padding: 14px 30px; background: var(--surface-light); color: var(--text-main); border: none; border-radius: 8px; font-size: 16px; cursor: pointer; font-family: 'Cairo', sans-serif; text-decoration: none; display: inline-block; transition: 0.3s; }
    .btn-cancel:hover { background: var(--border-color); }
    .btn-danger { padding: 14px 30px; background: var(--danger-color); color: #fff; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; font-family: 'Cairo', sans-serif; transition: 0.3s; text-decoration: none; display: inline-block; }
    .btn-danger:hover { background: #cc0000; transform: translateY(-2px); }
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
            <span class="label">🆔 رقم التحول:</span>
            <span class="value">#{{ $transformation->id }}</span>
        </div>
        <div>
            <span class="label">📅 تاريخ الإنشاء:</span>
            <span class="value">{{ $transformation->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div>
            <span class="label">📊 الحالة:</span>
            <span class="status-badge {{ $transformation->is_active ? 'status-active' : 'status-inactive' }}">
                {{ $transformation->is_active ? '✅ نشط' : '❌ غير نشط' }}
            </span>
        </div>
    </div>

    <form action="{{ route('admin.transformations.update', $transformation) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- العنوان -->
        <div class="form-row">
            <div class="form-group">
                <label>العنوان (عربي) *</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $transformation->title_ar) }}" required placeholder="مثال: صبغة ذهبية + كيراتين">
                @error('title_ar')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en', $transformation->title_en) }}" placeholder="Example: Golden Dye + Keratin">
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
                <option value="makeup" {{ old('category', $transformation->category) == 'makeup' ? 'selected' : '' }}>💄 مكياج</option>
                <option value="hair" {{ old('category', $transformation->category) == 'hair' ? 'selected' : '' }}>✂️ شعر</option>
                <option value="skincare" {{ old('category', $transformation->category) == 'skincare' ? 'selected' : '' }}>✨ عناية بالبشرة</option>
            </select>
            @error('category')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- صور قبل وبعد -->
        <div class="form-row">
            <div class="form-group">
                <label>📸 صورة "قبل"</label>
                @if($transformation->before_image)
                    <div class="image-preview">
                        <img src="{{ asset('storage/' . $transformation->before_image) }}" alt="قبل">
                        <div class="label">الصورة الحالية</div>
                    </div>
                @endif
                <input type="file" name="before_image" accept="image/*" onchange="previewImage(event, 'beforePreview')">
                <div class="helper-text">اتركه فارغاً للاحتفاظ بالصورة الحالية</div>
                <div id="beforePreview" class="image-preview" style="display: none;">
                    <img id="beforeImg" src="" alt="معاينة قبل">
                </div>
                @error('before_image')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>📸 صورة "بعد"</label>
                @if($transformation->after_image)
                    <div class="image-preview">
                        <img src="{{ asset('storage/' . $transformation->after_image) }}" alt="بعد">
                        <div class="label">الصورة الحالية</div>
                    </div>
                @endif
                <input type="file" name="after_image" accept="image/*" onchange="previewImage(event, 'afterPreview')">
                <div class="helper-text">اتركه فارغاً للاحتفاظ بالصورة الحالية</div>
                <div id="afterPreview" class="image-preview" style="display: none;">
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
            <textarea name="description_ar" rows="3" placeholder="وصف التحول بالعربية...">{{ old('description_ar', $transformation->description_ar) }}</textarea>
            @error('description_ar')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>الوصف (إنجليزي)</label>
            <textarea name="description_en" rows="3" placeholder="Description in English...">{{ old('description_en', $transformation->description_en) }}</textarea>
            @error('description_en')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- الترتيب والتفعيل -->
        <div class="form-row">
            <div class="form-group">
                <label>📊 الترتيب</label>
                <input type="number" name="order" value="{{ old('order', $transformation->order) }}" placeholder="0">
                <div class="helper-text">رقم أصغر = ظهور أولاً</div>
                @error('order')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="checkbox-group" style="padding-top: 30px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $transformation->is_active) ? 'checked' : '' }}>
                <label for="is_active">✅ نشط (يظهر في الموقع)</label>
            </div>
        </div>

        <!-- الأزرار -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ التغييرات</button>
            <a href="{{ route('admin.transformations.index') }}" class="btn-cancel">❌ إلغاء</a>
            <button type="button" class="btn-danger" onclick="confirmDelete({{ $transformation->id }})">🗑️ حذف التحول</button>
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
                preview.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        } else {
            img.src = '';
            preview.style.display = 'none';
        }
    }

    // ===== حذف التحول =====
    function confirmDelete(id) {
        if (confirm('⚠️ هل أنت متأكد من حذف هذا التحول؟ لا يمكن التراجع عن هذا الإجراء!')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/transformations/${id}`;

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