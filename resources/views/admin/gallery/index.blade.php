@extends('admin.layouts.admin')

@section('title', 'معرض الصور')

@section('page-title', '🖼️ إدارة معرض الصور')

@section('content')

<style>
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-add {
        padding: 10px 24px;
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-add:hover {
        background: var(--dark-gold);
        transform: translateY(-2px);
    }

    .btn-edit {
        padding: 4px 12px;
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-delete {
        padding: 4px 12px;
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
    }

    .btn-toggle {
        padding: 4px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
    }

    .btn-toggle.active {
        background: var(--success-color);
        color: #000;
    }

    .btn-toggle.inactive {
        background: var(--danger-color);
        color: #fff;
    }

    .image-preview {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview .no-image {
        font-size: 12px;
        color: var(--text-muted);
        text-align: center;
    }

    .category-badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        background: var(--surface-light);
        color: var(--text-muted);
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

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
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

    .featured-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        background: rgba(212, 175, 55, 0.2);
        color: var(--primary-gold);
    }
</style>

<div class="header-actions">
    <h2 style="color: var(--primary-gold); margin: 0;">جميع الصور</h2>
    <a href="{{ route('admin.gallery.create') }}" class="btn-add">➕ إضافة صورة جديدة</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>العنوان</th>
                <th>التصنيف</th>
                <th>مميزة</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($galleries as $gallery)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($gallery->image)
                        <div class="image-preview">
                            <img src="{{ asset($gallery->image) }}" 
                                 alt="{{ $gallery->title_ar ?? 'صورة' }}" 
                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'no-image\'>❌</span>'">
                        </div>
                    @else
                        <div class="image-preview">
                            <span class="no-image">لا توجد</span>
                        </div>
                    @endif
                </td>
                <td>
                    <strong>{{ $gallery->title_ar ?? $gallery->title_en ?? 'بدون عنوان' }}</strong>
                    @if($gallery->caption)
                        <br><small style="color: var(--text-muted);">{{ $gallery->caption }}</small>
                    @endif
                </td>
                <td>
                    <span class="category-badge">
                        @switch($gallery->category)
                            @case('bridal') 👰 عرائس @break
                            @case('makeup') 💄 مكياج @break
                            @case('fashion') 👗 أزياء @break
                            @case('hair') ✂️ شعر @break
                            @case('skin') ✨ بشرة @break
                            @case('all') 📁 الكل @break
                            @default {{ $gallery->category }}
                        @endswitch
                    </span>
                </td>
                <td>
                    @if(isset($gallery->is_featured) && $gallery->is_featured)
                        <span class="featured-badge">⭐ مميزة</span>
                    @else
                        <span style="color: var(--text-muted); font-size: 12px;">—</span>
                    @endif
                </td>
                <td>{{ $gallery->order ?? 0 }}</td>
                <td>
                    <button class="btn-toggle {{ $gallery->is_active ? 'active' : 'inactive' }}"
                            onclick="toggleStatus({{ $gallery->id }})">
                        {{ $gallery->is_active ? '✅ نشط' : '❌ غير نشط' }}
                    </button>
                </td>
                <td>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                        <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn-edit">✏️ تعديل</a>
                        <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">🗑️ حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <span class="icon">🖼️</span>
                        <p>لا توجد صور في المعرض</p>
                        <a href="{{ route('admin.gallery.create') }}" class="btn-add" style="margin-top: 15px; display: inline-block;">➕ إضافة صورة جديدة</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleStatus(id) {
        if (!confirm('هل أنت متأكد من تغيير حالة الصورة؟')) return;

        fetch(`/admin/gallery/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('✅ تم تغيير حالة الصورة');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ حدث خطأ', 'error');
        });
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    }
</script>

@endsection