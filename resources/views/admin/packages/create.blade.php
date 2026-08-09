@extends('admin.layouts.admin')

@section('title', 'إضافة باقة عرائس')

@section('page-title', '➕ إضافة باقة عرائس جديدة')

@section('content')

<style>
    .form-container {
        max-width: 900px;
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

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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
        margin-top: 10px;
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

    .tier-badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .tier-silver {
        background: rgba(192, 192, 192, 0.2);
        color: #C0C0C0;
    }

    .tier-gold {
        background: rgba(212, 175, 55, 0.2);
        color: var(--primary-gold);
    }

    .tier-platinum {
        background: rgba(176, 176, 200, 0.2);
        color: #B0B0C8;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-row-3 {
            grid-template-columns: 1fr;
        }
        .info-box {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="form-container">
    <form action="{{ route('admin.packages.store') }}" method="POST">
        @csrf

        <!-- معلومات الباقة -->
        <div class="info-box">
            <div>
                <span class="label">📌 إضافة باقة جديدة</span>
                <span class="value">املأ جميع الحقول المطلوبة</span>
            </div>
        </div>

        <!-- الاسم -->
        <div class="form-row">
            <div class="form-group">
                <label>اسم الباقة (عربي) *</label>
                <input type="text" name="name_ar" value="{{ old('name_ar') }}" required placeholder="مثال: عروس ذهبية">
                @error('name_ar')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>اسم الباقة (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en') }}" placeholder="مثال: Gold Bride">
                @error('name_en')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- التصنيف والسعر -->
        <div class="form-row">
            <div class="form-group">
                <label>التصنيف *</label>
                <select name="tier" required>
                    <option value="silver" {{ old('tier') == 'silver' ? 'selected' : '' }}>🥈 فضية (Silver)</option>
                    <option value="gold" {{ old('tier') == 'gold' ? 'selected' : '' }}>🥇 ذهبية (Gold)</option>
                    <option value="platinum" {{ old('tier') == 'platinum' ? 'selected' : '' }}>💎 بلاتينية (Platinum)</option>
                </select>
                @error('tier')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>السعر *</label>
                <input type="number" name="price" min="0" step="0.01" value="{{ old('price') }}" required placeholder="مثال: 5000">
                @error('price')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- العملة والخيارات -->
        <div class="form-row">
            <div class="form-group">
                <label>العملة *</label>
                <input type="text" name="currency" value="{{ old('currency', 'SAR') }}" required placeholder="مثال: SAR, ج.م">
                @error('currency')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div style="display: flex; gap: 20px; align-items: center; padding-top: 20px;">
                <div class="checkbox-group" style="padding-top: 0;">
                    <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                    <label for="is_popular">⭐ مميزة (الأكثر طلباً)</label>
                </div>
                <div class="checkbox-group" style="padding-top: 0;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active">✅ نشط</label>
                </div>
            </div>
        </div>

        <!-- الوصف -->
        <div class="form-group">
            <label>وصف الباقة (عربي)</label>
            <textarea name="description_ar" rows="3" placeholder="وصف الباقة بالعربية...">{{ old('description_ar') }}</textarea>
            @error('description_ar')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>وصف الباقة (إنجليزي)</label>
            <textarea name="description_en" rows="3" placeholder="Description in English...">{{ old('description_en') }}</textarea>
            @error('description_en')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- المميزات (عربي) -->
        <div class="form-group">
            <label>المميزات (عربي) *</label>
            <div class="features-container">
                <div id="featuresArList">
                    @if(old('features_ar') && count(old('features_ar')) > 0)
                        @foreach(old('features_ar') as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_ar[]" value="{{ $feature }}" placeholder="ميزة جديدة" required>
                                <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_ar[]" placeholder="مثال: ميكاب سواريه" required>
                            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature('featuresArList', 'features_ar[]', 'ميزة جديدة')">➕ إضافة مميزة</button>
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
                    @if(old('features_en') && count(old('features_en')) > 0)
                        @foreach(old('features_en') as $feature)
                            <div class="feature-item">
                                <input type="text" name="features_en[]" value="{{ $feature }}" placeholder="New feature">
                                <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                            </div>
                        @endforeach
                    @else
                        <div class="feature-item">
                            <input type="text" name="features_en[]" placeholder="Example: Evening Makeup">
                            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-feature" onclick="addFeature('featuresEnList', 'features_en[]', 'New feature')">➕ إضافة مميزة</button>
            </div>
        </div>

        <!-- الترتيب -->
        <div class="form-group">
            <label>📊 الترتيب</label>
            <input type="number" name="order" value="{{ old('order', 0) }}" placeholder="0">
            <div class="helper-text">رقم أصغر = ظهور أولاً</div>
            @error('order')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- الأزرار -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ الباقة</button>
            <a href="{{ route('admin.packages.index') }}" class="btn-cancel">❌ إلغاء</a>
        </div>
    </form>
</div>

<script>
    // ===== إضافة مميزة جديدة =====
    function addFeature(containerId, name, placeholder) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'feature-item';
        div.innerHTML = `
            <input type="text" name="${name}" placeholder="${placeholder}">
            <button type="button" class="remove-feature" onclick="this.parentElement.remove()">✕</button>
        `;
        container.appendChild(div);
    }
</script>

@endsection