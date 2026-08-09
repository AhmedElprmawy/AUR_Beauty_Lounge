@extends('admin.layouts.admin')

@section('title', 'لوحة التحكم')

@section('page-title', '📊 لوحة التحكم')

@section('content')

<style>
    /* ===== كاردات الإحصائيات ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--surface-dark);
        padding: 24px 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        text-align: center;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gold);
        opacity: 0;
        transition: 0.4s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-gold);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card .icon {
        font-size: 32px;
        display: block;
        margin-bottom: 8px;
    }

    .stat-card .number {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-gold);
        line-height: 1.2;
    }

    .stat-card .label {
        color: var(--text-muted);
        font-size: 14px;
        margin-top: 6px;
    }

    .stat-card .link {
        display: inline-block;
        margin-top: 10px;
        color: var(--primary-gold);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        opacity: 0.7;
        transition: 0.3s;
    }

    .stat-card .link:hover {
        opacity: 1;
    }

    /* ===== أزرار الإجراءات السريعة ===== */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 30px;
    }

    .quick-action {
        background: var(--surface-dark);
        padding: 18px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        text-align: center;
        text-decoration: none;
        color: var(--text-main);
        transition: all 0.3s ease;
    }

    .quick-action:hover {
        border-color: var(--primary-gold);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .quick-action .icon {
        font-size: 28px;
        display: block;
        margin-bottom: 6px;
    }

    .quick-action .name {
        font-size: 13px;
        font-weight: 600;
    }

    .quick-action .sub {
        font-size: 11px;
        color: var(--text-muted);
    }

    /* ===== قسم التحديثات ===== */
    .updates-section {
        background: var(--surface-dark);
        padding: 24px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-top: 20px;
    }

    .updates-section .title {
        color: var(--primary-gold);
        font-size: 18px;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .update-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .update-item:last-child {
        border-bottom: none;
    }

    .update-item .message {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-main);
        font-size: 14px;
    }

    .update-item .message .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary-gold);
        flex-shrink: 0;
    }

    .update-item .time {
        color: var(--text-muted);
        font-size: 12px;
        white-space: nowrap;
    }

    .update-item .badge-new {
        background: var(--primary-gold);
        color: #000;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
    }

    /* ===== Quick Stats Row ===== */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .quick-stat {
        background: var(--surface-dark);
        padding: 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: 0.3s;
    }

    .quick-stat:hover {
        border-color: var(--primary-gold);
    }

    .quick-stat .icon {
        font-size: 28px;
        width: 50px;
        height: 50px;
        background: var(--bg-dark);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-stat .info .number {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary-gold);
    }

    .quick-stat .info .label {
        font-size: 12px;
        color: var(--text-muted);
    }

    /* ===== Empty State ===== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }

    .empty-state .icon {
        font-size: 48px;
        display: block;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .quick-stats {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>


<!-- ===== الإحصائيات الرئيسية ===== -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="icon">📋</span>
        <div class="number">{{ $stats['services'] ?? 0 }}</div>
        <div class="label">الخدمات</div>
        <a href="{{ route('admin.services.index') }}" class="link">إدارة الخدمات →</a>
    </div>

    <div class="stat-card">
        <span class="icon">💎</span>
        <div class="number">{{ $stats['packages'] ?? 0 }}</div>
        <div class="label">باقات العرائس</div>
        <a href="{{ route('admin.packages.index') }}" class="link">إدارة الباقات →</a>
    </div>

    <div class="stat-card">
        <span class="icon">🖼️</span>
        <div class="number">{{ $stats['gallery'] ?? 0 }}</div>
        <div class="label">صور المعرض</div>
        <a href="{{ route('admin.gallery.index') }}" class="link">إدارة المعرض →</a>
    </div>

    <div class="stat-card">
        <span class="icon">👤</span>
        <div class="number">{{ $stats['staff'] ?? 0 }}</div>
        <div class="label">فريق العمل</div>
        <a href="{{ route('admin.staff.index') }}" class="link">إدارة الفريق →</a>
    </div>

    <div class="stat-card">
        <span class="icon">💬</span>
        <div class="number">{{ $stats['testimonials'] ?? 0 }}</div>
        <div class="label">آراء العملاء</div>
        <a href="{{ route('admin.testimonials.index') }}" class="link">إدارة الآراء →</a>
    </div>

    <div class="stat-card">
        <span class="icon">🔄</span>
        <div class="number">{{ $stats['transformations'] ?? 0 }}</div>
        <div class="label">قبل وبعد</div>
        <a href="{{ route('admin.transformations.index') }}" class="link">إدارة التحولات →</a>
    </div>
</div>

<!-- ===== الإجراءات السريعة ===== -->
<div class="quick-actions">
    <a href="{{ route('admin.services.create') }}" class="quick-action">
        <span class="icon">➕</span>
        <span class="name">إضافة خدمة</span>
        <span class="sub">خدمة جديدة</span>
    </a>
    <a href="{{ route('admin.packages.create') }}" class="quick-action">
        <span class="icon">➕</span>
        <span class="name">إضافة باقة</span>
        <span class="sub">باقة عرائس</span>
    </a>
    <a href="{{ route('admin.gallery.create') }}" class="quick-action">
        <span class="icon">➕</span>
        <span class="name">إضافة صورة</span>
        <span class="sub">معرض الأعمال</span>
    </a>
    <a href="{{ route('admin.staff.create') }}" class="quick-action">
        <span class="icon">➕</span>
        <span class="name">إضافة موظف</span>
        <span class="sub">فريق العمل</span>
    </a>
    <a href="{{ route('admin.testimonials.create') }}" class="quick-action">
        <span class="icon">➕</span>
        <span class="name">إضافة رأي</span>
        <span class="sub">آراء العملاء</span>
    </a>
    <a href="{{ route('admin.transformations.create') }}" class="quick-action">
        <span class="icon">➕</span>
        <span class="name">إضافة تحول</span>
        <span class="sub">قبل وبعد</span>
    </a>
    <a href="{{ route('admin.bridal.index') }}" class="quick-action">
        <span class="icon">✏️</span>
        <span class="name">تعديل العرائس</span>
        <span class="sub">قسم العرائس</span>
    </a>
   
</div>

<!-- ===== Quick Stats ===== -->
<div class="quick-stats">
    <div class="quick-stat">
        <div class="icon">✅</div>
        <div class="info">
            <div class="number">{{ $stats['active_services'] ?? 0 }}</div>
            <div class="label">خدمات نشطة</div>
        </div>
    </div>
    <div class="quick-stat">
        <div class="icon">⭐</div>
        <div class="info">
            <div class="number">{{ $stats['popular_packages'] ?? 0 }}</div>
            <div class="label">باقات مميزة</div>
        </div>
    </div>
    <div class="quick-stat">
        <div class="icon">📸</div>
        <div class="info">
            <div class="number">{{ $stats['featured_gallery'] ?? 0 }}</div>
            <div class="label">صور مميزة</div>
        </div>
    </div>
    <div class="quick-stat">
        <div class="icon">👥</div>
        <div class="info">
            <div class="number">{{ $stats['active_staff'] ?? 0 }}</div>
            <div class="label">موظفين نشطين</div>
        </div>
    </div>
</div>

<!-- ===== آخر التحديثات ===== -->
<div class="updates-section">
    <div class="title">
        <span>🔄</span>
        آخر التحديثات
    </div>

    @if(isset($recentUpdates) && $recentUpdates->count() > 0)
        @foreach($recentUpdates as $update)
        <div class="update-item">
            <div class="message">
                <span class="dot"></span>
                <span>{{ $update->message }}</span>
                @if($update->is_new ?? false)
                    <span class="badge-new">جديد</span>
                @endif
            </div>
            <div class="time">{{ $update->created_at->diffForHumans() }}</div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="icon">📭</span>
            <p>لا توجد تحديثات حديثة</p>
        </div>
    @endif
</div>

<!-- ===== نصائح سريعة ===== -->
<div style="margin-top: 20px; background: rgba(212, 175, 55, 0.05); padding: 16px 20px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.1);">
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <span style="font-size: 20px;">💡</span>
        <span style="color: var(--text-muted); font-size: 14px;">
            <strong style="color: var(--primary-gold);">نصيحة:</strong>
            قم بتحديث محتوى الموقع بانتظام للحفاظ على تفاعل الزوار.
            أضف صوراً جديدة وخبراء جدد وآراء عملاء حديثة.
        </span>
        <a href="{{ route('admin.services.create') }}" style="background: var(--primary-gold); color: #000; padding: 6px 18px; border-radius: 20px; text-decoration: none; font-weight: 600; font-size: 13px;">
            ابدأ الآن →
        </a>
    </div>
</div>

@endsection