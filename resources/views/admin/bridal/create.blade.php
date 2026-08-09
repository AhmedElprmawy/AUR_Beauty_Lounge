@extends('admin.layouts.admin')

@section('title', 'إضافة قسم العرائس')

@section('page-title', '➕ إضافة قسم العرائس')

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
    .form-group textarea:focus {
        border-color: var(--primary-gold);
        outline: none;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
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
        padding: 10px 0;
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
    }

    .helper-text {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-container">
    <form action="{{ route('admin.bridal.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>العنوان الرئيسي (عربي) *</label>
                <input type="text" name="title_ar" value="{{ old('title_ar') }}" placeholder="مثال: تجهيز العرائس">
                <div class="helper-text">يظهر في العنوان الكبير</div>
            </div>
            <div class="form-group">
                <label>العنوان الرئيسي (إنجليزي)</label>
                <input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="مثال: Bridal Preparation">
            </div>
        </div>

        <div class="form-group">
            <label>الوصف (عربي)</label>
            <textarea name="description_ar" rows="4" placeholder="وصف القسم...">{{ old('description_ar') }}</textarea>
        </div>

        <div class="form-group">
            <label>الوصف (إنجليزي)</label>
            <textarea name="description_en" rows="4" placeholder="Description...">{{ old('description_en') }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>الصورة الرئيسية (رابط)</label>
                <input type="text" name="image" value="{{ old('image') }}" placeholder="https://example.com/image.jpg">
                <div class="helper-text">الصورة الكبيرة التي تظهر في القسم</div>
            </div>
            <div class="form-group">
                <label>الصورة الصغيرة (رابط)</label>
                <input type="text" name="small_image" value="{{ old('small_image') }}" placeholder="https://example.com/small.jpg">
                <div class="helper-text">الصورة العائمة التي تظهر في الزاوية</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>رقم الإحصائية</label>
                <input type="text" name="stats_number" value="{{ old('stats_number', '1500+') }}" placeholder="مثال: 1500+">
                <div class="helper-text">الرقم الذي يظهر في البطاقة العائمة</div>
            </div>
            <div class="form-group">
                <label>تسمية الإحصائية (عربي)</label>
                <input type="text" name="stats_label_ar" value="{{ old('stats_label_ar', 'عروس جميلة') }}" placeholder="مثال: عروس جميلة">
            </div>
        </div>

        <div class="form-group">
            <label>تسمية الإحصائية (إنجليزي)</label>
            <input type="text" name="stats_label_en" value="{{ old('stats_label_en', 'Beautiful Brides') }}" placeholder="مثال: Beautiful Brides">
        </div>

        <div class="form-group">
            <label>المميزات (عربي)</label>
            <div class="features-container">
                <div id="featuresList">
                    @if(old('features_ar'))
                        @foreach(old('features_ar') as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_ar[]" value="{{ $feature }}" placeholder="مميزة جديدة">
                                <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_ar[]" placeholder="مثال: فريق متخصص للعرائس">
                            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature('featuresList', 'features_ar[]')">➕ إضافة مميزة</button>
            </div>
            <div class="helper-text">المميزات التي تظهر كنقاط في القسم</div>
        </div>

        <div class="form-group">
            <label>المميزات (إنجليزي)</label>
            <div class="features-container">
                <div id="featuresEnList">
                    @if(old('features_en'))
                        @foreach(old('features_en') as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_en[]" value="{{ $feature }}" placeholder="New feature">
                                <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_en[]" placeholder="Example: Specialized Bridal Team">
                            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature('featuresEnList', 'features_en[]')">➕ إضافة مميزة</button>
            </div>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="is_active" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
            <label for="is_active">✅ القسم نشط (يظهر في الموقع)</label>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ القسم</button>
            <a href="{{ route('admin.bridal.index') }}" class="btn-cancel">إلغاء</a>
        </div>
    </form>
</div>

<script>
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

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (this.querySelector('.btn-delete')) {
                if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>

@endsection
