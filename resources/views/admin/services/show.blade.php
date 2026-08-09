@extends('admin.layouts.admin')

@section('title', 'عرض الخدمة')

@section('page-title', '👁️ عرض الخدمة')

@section('content')

<style>
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        max-width: 900px;
        margin: 0 auto;
    }

    .details-card {
        background: var(--surface-dark);
        padding: 24px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .details-card .card-title {
        color: var(--primary-gold);
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 15px 0;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
    }

    .details-card .field {
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .details-card .field:last-child {
        border-bottom: none;
    }

    .details-card .label {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .details-card .value {
        font-size: 16px;
        font-weight: 600;
        margin-top: 4px;
    }

    .details-card .value .gold {
        color: var(--primary-gold);
    }

    .details-card .value .icon-display {
        font-size: 48px;
        display: block;
        text-align: center;
        padding: 10px 0;
    }

    .details-card img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 10px;
    }

    .feature-tag {
        display: inline-block;
        background: var(--bg-dark);
        padding: 4px 14px;
        border-radius: 15px;
        font-size: 14px;
        color: var(--text-main);
        margin: 4px 6px 4px 0;
        border: 1px solid var(--border-color);
    }

    .feature-tag .check {
        color: var(--primary-gold);
        margin-left: 4px;
    }

    .status-badge {
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 14px;
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

    .actions-bar {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .btn-edit {
        padding: 12px 30px;
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-edit:hover {
        background: var(--dark-gold);
        transform: translateY(-2px);
    }

    .btn-back {
        padding: 12px 30px;
        background: var(--surface-light);
        color: var(--text-main);
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-back:hover {
        background: var(--border-color);
    }

    .btn-delete {
        padding: 12px 30px;
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background: #cc0000;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="details-grid">

    <!-- بطاقة المعلومات الأساسية -->
    <div class="details-card">
        <div class="card-title">📋 معلومات الخدمة</div>

        <div class="field">
            <div class="label">🆔 رقم الخدمة</div>
            <div class="value gold">#{{ $service->id }}</div>
        </div>

        <div class="field">
            <div class="label">📌 الأيقونة</div>
            <div class="icon-display">{{ $service->icon }}</div>
        </div>

        <div class="field">
            <div class="label">📛 الاسم (عربي)</div>
            <div class="value">{{ $service->title_ar }}</div>
        </div>

        <div class="field">
            <div class="label">📛 الاسم (إنجليزي)</div>
            <div class="value">{{ $service->title_en ?? '—' }}</div>
        </div>

        <div class="field">
            <div class="label">🏷️ التصنيف</div>
            <div class="value"><span class="feature-tag">{{ $service->label }}</span></div>
        </div>

        <div class="field">
            <div class="label">📊 الترتيب</div>
            <div class="value">{{ $service->order }}</div>
        </div>

        <div class="field">
            <div class="label">📊 الحالة</div>
            <div class="value">
                <span class="status-badge {{ $service->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $service->is_active ? '✅ نشط' : '❌ غير نشط' }}
                </span>
            </div>
        </div>
    </div>

    <!-- بطاقة المحتوى الإضافي -->
    <div class="details-card">
        <div class="card-title">📝 المحتوى</div>

        <div class="field">
            <div class="label">🖼️ الصورة</div>
            <div class="value">
                @if($service->image)
                    <img src="{{ $service->image }}" alt="{{ $service->title_ar }}">
                @else
                    <span style="color: var(--text-muted);">لا توجد صورة</span>
                @endif
            </div>
        </div>

        <div class="field">
            <div class="label">✨ المميزات (عربي)</div>
            <div class="value">
                @if($service->features_ar && count($service->features_ar) > 0)
                    @foreach($service->features_ar as $feature)
                        <span class="feature-tag"><span class="check">✔</span> {{ $feature }}</span>
                    @endforeach
                @else
                    <span style="color: var(--text-muted);">لا توجد مميزات</span>
                @endif
            </div>
        </div>

        <div class="field">
            <div class="label">✨ المميزات (إنجليزي)</div>
            <div class="value">
                @if($service->features_en && count($service->features_en) > 0)
                    @foreach($service->features_en as $feature)
                        <span class="feature-tag"><span class="check">✔</span> {{ $feature }}</span>
                    @endforeach
                @else
                    <span style="color: var(--text-muted);">No features</span>
                @endif
            </div>
        </div>

        <div class="field">
            <div class="label">📅 تاريخ الإنشاء</div>
            <div class="value">{{ $service->created_at->format('Y-m-d H:i') }}</div>
        </div>

        <div class="field">
            <div class="label">🔄 آخر تحديث</div>
            <div class="value">{{ $service->updated_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>

</div>

<!-- الأزرار -->
<div class="actions-bar">
    <a href="{{ route('admin.services.index') }}" class="btn-back">⬅ العودة للقائمة</a>
    <a href="{{ route('admin.services.edit', $service) }}" class="btn-edit">✏️ تعديل الخدمة</a>
    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete" onclick="return confirm('⚠️ هل أنت متأكد من حذف هذه الخدمة؟')">🗑️ حذف الخدمة</button>
    </form>
</div>

@endsection