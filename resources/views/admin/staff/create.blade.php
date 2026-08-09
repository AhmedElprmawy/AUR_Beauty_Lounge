@extends('admin.layouts.admin')

@section('title', 'إضافة عضو فريق')

@section('page-title', '➕ إضافة عضو للفريق')

@section('content')

<style>
    .form-container { max-width: 850px; margin: 0 auto; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; }
    .form-group input, .form-group textarea { width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-dark); color: var(--text-main); }
    .row-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .btn-submit, .btn-cancel { border: none; border-radius: 10px; font-weight: 700; cursor: pointer; }
    .btn-submit { padding: 14px 24px; background: var(--primary-gold); color: #000; }
    .btn-cancel { padding: 14px 24px; background: var(--surface-light); color: var(--text-main); text-decoration: none; display: inline-block; }
</style>

<div class="form-container">
    <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf

        <div class="row-2">
            <div class="form-group">
                <label>الاسم (عربي)</label>
                <input type="text" name="name_ar" value="{{ old('name_ar') }}" required>
            </div>
            <div class="form-group">
                <label>الاسم (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en') }}">
            </div>
        </div>

        <div class="row-2">
            <div class="form-group">
                <label>الدور (عربي)</label>
                <input type="text" name="role_ar" value="{{ old('role_ar') }}" required>
            </div>
            <div class="form-group">
                <label>الدور (إنجليزي)</label>
                <input type="text" name="role_en" value="{{ old('role_en') }}">
            </div>
        </div>

        <div class="row-2">
            <div class="form-group">
                <label>سنوات الخبرة</label>
                <input type="number" name="experience_years" min="0" value="{{ old('experience_years', 0) }}">
            </div>
            <div class="form-group" style="display:flex; align-items:center; gap:10px; padding-top: 32px;">
                <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" style="margin:0;">نشط</label>
            </div>
        </div>

        <div class="form-group">
            <label>نبذة (عربي)</label>
            <textarea name="bio_ar" rows="4">{{ old('bio_ar') }}</textarea>
        </div>

        <div class="form-group">
            <label>نبذة (إنجليزي)</label>
            <textarea name="bio_en" rows="4">{{ old('bio_en') }}</textarea>
        </div>

        <div class="row-2">
            <div class="form-group">
                <label>رابط الصورة</label>
                <input type="text" name="image" value="{{ old('image') }}">
            </div>
            <div class="form-group">
                <label>Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Twitter</label>
            <input type="text" name="twitter" value="{{ old('twitter') }}">
        </div>

        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="order" value="{{ old('order', 0) }}">
        </div>

        <div style="display:flex; gap:14px; flex-wrap:wrap; margin-top:24px;">
            <button type="submit" class="btn-submit">حفظ الموظف</button>
            <a href="{{ route('admin.staff.index') }}" class="btn-cancel">إلغاء</a>
        </div>
    </form>
</div>

@endsection
