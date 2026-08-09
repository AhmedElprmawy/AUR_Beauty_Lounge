@extends('admin.layouts.admin')

@section('title', 'تعديل الخدمة')

@section('page-title', '✏️ تعديل الخدمة')

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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .features-container {
        background: var(--bg-dark);
        padding: 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .feature-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .feature-item input {
        flex: 1;
    }

    .feature-item .remove-feature {
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0 12px;
        cursor: pointer;
        font-size: 16px;
        transition: 0.3s;
    }

    .feature-item .remove-feature:hover {
        background: #cc0000;
    }

    .btn-add-feature {
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-add-feature:hover {
        background: var(--dark-gold);
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

    .btn-danger {
        padding: 14px 30px;
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-danger:hover {
        background: #cc0000;
        transform: translateY(-2px);
    }

    .image-preview {
        margin-top: 10px;
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .image-preview img {
        width: 100%;
        height: auto;
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

    .status-badge {
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    .status-active {
        background: rgba(76, 175, 80, 0.2);
        color: var(--success-color);
    }

    .status-inactive {
        background: rgba(255, 77, 77, 0.2);
        color: var(--danger-color);
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

    <!-- معلومات الخدمة -->
    <div class="info-box">
        <div>
            <span class="label">🆔 رقم الخدمة:</span>
            <span class="value">#{{ $service->id }}</span>
        </div>
        <div>
            <span class="label">📅 تاريخ الإنشاء:</span>
            <span class="value">{{ $service->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div>
            <span class="label">📊 الحالة:</span>
            <span class="status-badge {{ $service->is_active ? 'status-active' : 'status-inactive' }}">
                {{ $service->is_active ? '✅ نشط' : '❌ غير نشط' }}
            </span>
        </div>
    </div>

    <!-- ===== نموذج التعديل (تم إصلاحه) ===== -->
    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- العنوان -->
        <div class="form-row">
            <div class="form-group">
                <label>الاسم (عربي) *</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $service->title_ar) }}" required placeholder="مثال: الشعر">
                @error('title_ar')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>الاسم (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en', $service->title_en) }}" placeholder="مثال: Hair">
                @error('title_en')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- التصنيف والأيقونة -->
        <div class="form-row">
            <div class="form-group">
                <label>التصنيف *</label>
                <input type="text" name="label" value="{{ old('label', $service->label) }}" required placeholder="مثال: Hair, Makeup">
                <div class="helper-text">يستخدم للتصنيف والفلترة</div>
                @error('label')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>الأيقونة *</label>
                <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" required placeholder="مثال: ✂, 💄">
                <div class="helper-text">استخدم إيموجي أو رمز مناسب</div>
                @error('icon')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- ===== صورة الخدمة ===== -->
        <div class="form-group">
            <label>🖼️ صورة الخدمة</label>
            
            @if($service->image_path)
                <div class="image-preview">
                    <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title_ar }}">
                    <div style="margin-top: 8px;">
                        <small style="color: var(--text-muted);">الصورة الحالية</small>
                    </div>
                </div>
            @elseif($service->image)
                <div class="image-preview">
                    <img src="{{ $service->image }}" alt="{{ $service->title_ar }}">
                    <div style="margin-top: 8px;">
                        <small style="color: var(--text-muted);">الصورة الحالية (رابط خارجي)</small>
                    </div>
                </div>
            @endif
            
            <input type="file" name="image" accept="image/*" onchange="previewImage(event)" style="margin-top: 10px;">
            <div class="helper-text">الصيغ المدعومة: JPG, JPEG, PNG, WEBP - الحد الأقصى: 5MB</div>
            <div id="imagePreview" class="image-preview" style="display: none;">
                <img id="previewImg" src="" alt="معاينة الصورة الجديدة">
            </div>
        </div>

        <!-- المميزات (عربي) -->
        <div class="form-group">
            <label>المميزات (عربي) *</label>
            <div class="features-container">
                <div id="featuresList">
                    @if($service->features_ar && count($service->features_ar) > 0)
                        @foreach($service->features_ar as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_ar[]" value="{{ $feature }}" placeholder="مميزة جديدة">
                                <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_ar[]" placeholder="مثال: قص وتسريح">
                            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature('featuresList', 'features_ar[]')">➕ إضافة مميزة</button>
            </div>
            @error('features_ar')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- المميزات (إنجليزي) -->
        <div class="form-group">
            <label>المميزات (إنجليزي)</label>
            <div class="features-container">
                <div id="featuresEnList">
                    @if($service->features_en && count($service->features_en) > 0)
                        @foreach($service->features_en as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_en[]" value="{{ $feature }}" placeholder="New feature">
                                <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_en[]" placeholder="Example: Cut & Styling">
                            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature('featuresEnList', 'features_en[]')">➕ إضافة مميزة</button>
            </div>
        </div>

        <!-- الترتيب والتفعيل -->
        <div class="form-row">
            <div class="form-group">
                <label>📊 الترتيب</label>
                <input type="number" name="order" value="{{ old('order', $service->order) }}" placeholder="0">
                <div class="helper-text">رقم أصغر = ظهور أولاً</div>
                @error('order')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="checkbox-group" style="padding-top: 30px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                <label for="is_active">✅ الخدمة نشطة (تظهر في الموقع)</label>
                @error('is_active')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- الأزرار -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ التغييرات</button>
            <a href="{{ route('admin.services.index') }}" class="btn-cancel">❌ إلغاء</a>
            <button type="button" class="btn-danger" onclick="confirmDelete({{ $service->id }})">🗑️ حذف الخدمة</button>
        </div>

    </form>
</div>

<script>
    // ===== إضافة مميزة جديدة =====
    function addFeature(containerId, name) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'feature-item';
        div.innerHTML = `
            <input type="text" name="${name}" placeholder="مميزة جديدة">
            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
        `;
        container.appendChild(div);
    }

    // ===== حذف الخدمة =====
    function confirmDelete(id) {
        if (confirm('⚠️ هل أنت متأكد من حذف هذه الخدمة؟ لا يمكن التراجع عن هذا الإجراء!')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/services/${id}`;
            
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

    // ===== Toast function =====
    function showToast(msg) {
        const toast = document.getElementById('toast');
        toast.textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // ===== معاينة الصورة =====
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
</script>

@endsection