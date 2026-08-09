@extends('admin.layouts.admin')

@section('title', 'قسم العرائس')

@section('page-title', '👰 قسم العرائس')

@section('content')

<style>
    .bridal-preview {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 20px;
    }

    .bridal-preview .image-section {
        background: var(--surface-dark);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .bridal-preview .image-section img {
        width: 100%;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .bridal-preview .image-section .small-img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-gold);
    }

    .bridal-preview .content-section {
        background: var(--surface-dark);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .bridal-preview .content-section .field {
        padding: 8px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .bridal-preview .content-section .field:last-child {
        border-bottom: none;
    }

    .bridal-preview .content-section .label {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .bridal-preview .content-section .value {
        font-size: 16px;
        font-weight: 600;
        margin-top: 2px;
    }

    .bridal-preview .content-section .value .gold {
        color: var(--primary-gold);
    }

    .feature-tag {
        display: inline-block;
        background: var(--bg-dark);
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 13px;
        color: var(--text-main);
        margin: 3px 5px 3px 0;
        border: 1px solid var(--border-color);
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

    .btn-toggle {
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-toggle.active {
        background: var(--success-color);
        color: #000;
    }

    .btn-toggle.inactive {
        background: var(--danger-color);
        color: #fff;
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state .icon {
        font-size: 48px;
        display: block;
        margin-bottom: 15px;
    }

    .actions-bar {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .bridal-preview {
            grid-template-columns: 1fr;
        }
    }
</style>

@if($bridal)
    <div class="bridal-preview">
        <!-- قسم الصور -->
        <div class="image-section">
            <h4 style="color: var(--primary-gold); margin-top: 0;">📸 الصور</h4>

            <label style="color: var(--text-muted); font-size: 13px;">الصورة الرئيسية</label>
            @if($bridal->image)
                <img src="{{ $bridal->image }}" alt="الصورة الرئيسية">
            @else
                <div style="background: var(--bg-dark); padding: 40px; text-align: center; border-radius: 10px; color: var(--text-muted);">
                    لا توجد صورة رئيسية
                </div>
            @endif

            <label style="color: var(--text-muted); font-size: 13px; margin-top: 15px; display: block;">الصورة الصغيرة (العائمة)</label>
            @if($bridal->small_image)
                <img src="{{ $bridal->small_image }}" class="small-img" alt="الصورة الصغيرة">
            @else
                <div style="background: var(--bg-dark); padding: 20px; text-align: center; border-radius: 10px; color: var(--text-muted);">
                    لا توجد صورة صغيرة
                </div>
            @endif
        </div>

        <!-- قسم المحتوى -->
        <div class="content-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                <h4 style="color: var(--primary-gold); margin: 0;">📝 المحتوى</h4>
                <span class="status-badge {{ $bridal->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $bridal->is_active ? '✅ نشط' : '❌ غير نشط' }}
                </span>
            </div>

            <div class="field">
                <div class="label">العنوان (عربي)</div>
                <div class="value">{{ $bridal->title_ar ?? '—' }}</div>
            </div>

            <div class="field">
                <div class="label">العنوان (إنجليزي)</div>
                <div class="value">{{ $bridal->title_en ?? '—' }}</div>
            </div>

            <div class="field">
                <div class="label">الوصف (عربي)</div>
                <div class="value" style="font-weight: 400; line-height: 1.6;">{{ $bridal->description_ar ?? '—' }}</div>
            </div>

            <div class="field">
                <div class="label">الوصف (إنجليزي)</div>
                <div class="value" style="font-weight: 400; line-height: 1.6;">{{ $bridal->description_en ?? '—' }}</div>
            </div>

            <div class="field">
                <div class="label">عدد الإحصائيات</div>
                <div class="value gold">{{ $bridal->stats_number ?? '1500+' }}</div>
            </div>

            <div class="field">
                <div class="label">تسمية الإحصائية (عربي)</div>
                <div class="value">{{ $bridal->stats_label_ar ?? 'عروس جميلة' }}</div>
            </div>

            <div class="field">
                <div class="label">تسمية الإحصائية (إنجليزي)</div>
                <div class="value">{{ $bridal->stats_label_en ?? 'Beautiful Brides' }}</div>
            </div>

            <div class="field">
                <div class="label">المميزات (عربي)</div>
                <div class="value">
                    @if($bridal->features_ar && count($bridal->features_ar) > 0)
                        @foreach($bridal->features_ar as $feature)
                            <span class="feature-tag">✔ {{ $feature }}</span>
                        @endforeach
                    @else
                        <span style="color: var(--text-muted);">لا توجد مميزات</span>
                    @endif
                </div>
            </div>

            <div class="field">
                <div class="label">المميزات (إنجليزي)</div>
                <div class="value">
                    @if($bridal->features_en && count($bridal->features_en) > 0)
                        @foreach($bridal->features_en as $feature)
                            <span class="feature-tag">✔ {{ $feature }}</span>
                        @endforeach
                    @else
                        <span style="color: var(--text-muted);">لا توجد مميزات</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="actions-bar">
        <a href="{{ route('admin.bridal.edit', $bridal) }}" class="btn-edit">✏️ تعديل القسم</a>

        <button class="btn-toggle {{ $bridal->is_active ? 'active' : 'inactive' }}"
                onclick="toggleStatus({{ $bridal->id }})">
            {{ $bridal->is_active ? '❌ إلغاء التفعيل' : '✅ تفعيل' }}
        </button>
    </div>

@else
    <div class="empty-state">
        <span class="icon">👰</span>
        <p>لا يوجد محتوى في قسم العرائس</p>
        <a href="{{ route('admin.bridal.create') }}" class="btn-edit" style="margin-top: 15px;">➕ إضافة محتوى</a>
    </div>
@endif

<script>
    function toggleStatus(id) {
        if (!confirm('هل أنت متأكد من تغيير حالة القسم؟')) return;

        fetch(`/admin/bridal/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('✅ تم تغيير حالة القسم');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ حدث خطأ', 'error');
        });
    }
</script>

@endsection