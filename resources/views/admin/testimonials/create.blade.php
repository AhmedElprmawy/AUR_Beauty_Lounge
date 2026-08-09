@extends('admin.layouts.admin')

@section('title', 'إضافة رأي عميل')

@section('page-title', '➕ إضافة رأي جديد')

@section('content')

<style>
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; }
    .form-group input, .form-group textarea, .form-group select { 
        width: 100%; 
        padding: 12px 14px; 
        border-radius: 10px; 
        border: 1px solid var(--border-color); 
        background: var(--bg-dark); 
        color: var(--text-main); 
        font-family: 'Cairo', sans-serif;
    }
    .form-group input:focus, .form-group textarea:focus {
        border-color: var(--primary-gold);
        outline: none;
    }
    .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .btn-submit, .btn-cancel { border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Cairo', sans-serif; }
    .btn-submit { padding: 14px 24px; background: var(--primary-gold); color: #000; transition: 0.3s; }
    .btn-submit:hover { background: var(--dark-gold); transform: translateY(-2px); }
    .btn-cancel { padding: 14px 24px; background: var(--surface-light); color: var(--text-main); text-decoration: none; display: inline-block; transition: 0.3s; }
    .btn-cancel:hover { background: var(--border-color); }
    .checkbox-group { display: flex; align-items: center; gap: 10px; padding-top: 32px; }
    .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--primary-gold); }
    .checkbox-group label { margin: 0; cursor: pointer; }
    .error-text { color: var(--danger-color); font-size: 13px; margin-top: 5px; display: none; }
    .error-text.show { display: block; }
    .input-error { border-color: var(--danger-color) !important; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .checkbox-group { padding-top: 0; }
    }
</style>

<div class="form-container">

    <!-- عرض الأخطاء من Laravel -->
    @if ($errors->any())
        <div style="background: rgba(255, 77, 77, 0.15); color: var(--danger-color); padding: 15px 20px; border-radius: 8px; border-right: 4px solid var(--danger-color); margin-bottom: 20px;">
            <strong>⚠️ يرجى تصحيح الأخطاء التالية:</strong>
            <ul style="margin-top: 8px; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

  <div class="form-row">
    <div class="form-group">
        <label>اسم العميل *</label>
        <input type="text" name="client_name" value="{{ old('client_name') }}" required placeholder="مثال: ياسمين محمد">
        @error('client_name')
            <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        {{-- ✅ تأكد أن name="role_ar" --}}
        <label>الصفة (عربي) *</label>
        <input type="text" name="role_ar" value="{{ old('role_ar') }}" required placeholder="مثال: عروس - باقة بلاتين">
        @error('role_ar')
            <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>
</div>

        <div class="form-group">
            <label>📌 صفة العميل (إنجليزي)</label>
            <input type="text" name="client_role_en" value="{{ old('client_role_en') }}" placeholder="مثال: Bride - Platinum Package">
            @error('client_role_en')
                <div class="error-text show">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>💬 النص (عربي) *</label>
            <textarea name="content_ar" rows="4" required placeholder="اكتب نص الرأي بالعربية...">{{ old('content_ar') }}</textarea>
            @error('content_ar')
                <div class="error-text show">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>💬 النص (إنجليزي)</label>
            <textarea name="content_en" rows="4" placeholder="Write the testimonial in English...">{{ old('content_en') }}</textarea>
            @error('content_en')
                <div class="error-text show">{{ $message }}</div>
            @enderror
        </div>

       <div class="form-row">
    <div class="form-group">
        <label>التقييم (1-5)</label>
        <select name="rating">
            <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>★★★★★ (5)</option>
            <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>★★★★☆ (4)</option>
            <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>★★★☆☆ (3)</option>
            <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>★★☆☆☆ (2)</option>
            <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
        </select>
    </div>
    <div class="checkbox-group" style="padding-top: 30px;">
        {{-- ✅ إضافة value="1" --}}
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
        <label for="is_active">✅ نشط (يظهر في الموقع)</label>
    </div>
</div>

        <div class="form-group">
        <label>🖼️ الصورة الشخصية</label>
        <input type="file" name="avatar" accept="image/*" onchange="previewImage(event)">
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">اختياري - الصيغ: JPG, PNG - الحد الأقصى: 2MB</div>
        <div id="imagePreview" style="margin-top: 10px; max-width: 100px; border-radius: 50%; overflow: hidden; border: 2px solid var(--primary-gold); display: none;">
            <img id="previewImg" src="" alt="معاينة" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        @error('avatar')
            <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>
</form>

        <div style="display:flex; gap:14px; flex-wrap:wrap; margin-top:24px;">
            <button type="submit" class="btn-submit">✅ حفظ الرأي</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn-cancel">❌ إلغاء</a>
        </div>
    </form>
</div>

<script>
    // تحسين تجربة المستخدم
    document.addEventListener('DOMContentLoaded', function() {
        // إزالة رسائل الخطأ عند الكتابة
        document.querySelectorAll('input, textarea').forEach(function(input) {
            input.addEventListener('input', function() {
                this.classList.remove('input-error');
                const error = this.closest('.form-group').querySelector('.error-text');
                if (error) {
                    error.classList.remove('show');
                }
            });
        });
    });
</script>

@endsection