@extends('admin.layouts.admin')

@section('title', 'إضافة خدمة جديدة')

@section('page-title', 'إضافة خدمة جديدة')

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
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--primary-gold);
        outline: none;
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
    }

    .btn-cancel:hover {
        background: var(--border-color);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-container">
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>الاسم (عربي) *</label>
                <input type="text" name="title_ar" required value="{{ old('title_ar') }}">
            </div>
            <div class="form-group">
                <label>الاسم (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>التصنيف *</label>
                <input type="text" name="label" required value="{{ old('label') }}" placeholder="مثال: Hair, Makeup">
            </div>
            <div class="form-group">
                <label>الأيقونة *</label>
                <input type="text" name="icon" required value="{{ old('icon') }}" placeholder="مثال: ✂, 💄">
            </div>
        </div>

        <div class="form-group">
            <label>صورة الخدمة</label>
            <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" />
            <div class="helper-text">الصيغ المدعومة: JPG, JPEG, PNG, WEBP - الحد الأقصى: 5MB</div>
        </div>

        <div class="form-group">
            <label>المميزات (عربي) *</label>
            <div class="features-container" id="featuresContainer">
                <div id="featuresList">
                    @if(old('features_ar'))
                        @foreach(old('features_ar') as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_ar[]" value="{{ $feature }}" required>
                                <button type="button" class="remove-feature">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_ar[]" required placeholder="مثال: قص وتسريح">
                            <button type="button" class="remove-feature">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature()">➕ إضافة مميزة</button>
            </div>
        </div>

        <div class="form-group">
            <label>المميزات (إنجليزي)</label>
            <div class="features-container">
                <div id="featuresEnList">
                    @if(old('features_en'))
                        @foreach(old('features_en') as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_en[]" value="{{ $feature }}">
                                <button type="button" class="remove-feature">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_en[]" placeholder="مثال: Cut & Styling">
                            <button type="button" class="remove-feature">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeatureEn()">➕ إضافة مميزة</button>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>الترتيب</label>
                <input type="number" name="order" value="{{ old('order', 0) }}">
            </div>
            <div class="form-group" style="display: flex; align-items: center; gap: 10px; padding-top: 30px;">
                <input type="checkbox" name="is_active" id="is_active" checked>
                <label for="is_active" style="margin: 0;">نشط</label>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn-submit">💾 حفظ الخدمة</button>
            <a href="{{ route('admin.services.index') }}" class="btn-cancel">إلغاء</a>
        </div>
    </form>
</div>

<script>
    function addFeature() {
        const container = document.getElementById('featuresList');
        const div = document.createElement('div');
        div.className = 'feature-item';
        div.innerHTML = `
            <input type="text" name="features_ar[]" required placeholder="مميزة جديدة">
            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
        `;
        container.appendChild(div);
    }

    function addFeatureEn() {
        const container = document.getElementById('featuresEnList');
        const div = document.createElement('div');
        div.className = 'feature-item';
        div.innerHTML = `
            <input type="text" name="features_en[]" placeholder="New feature">
            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
        `;
        container.appendChild(div);
    }

    // حذف المميزات الموجودة
    document.querySelectorAll('.remove-feature').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });
</script>

@endsection