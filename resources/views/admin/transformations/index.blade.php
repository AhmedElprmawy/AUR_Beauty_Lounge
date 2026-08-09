@extends('admin.layouts.admin')

@section('title', 'التحولات')

@section('page-title', '🔄 إدارة التحولات (قبل وبعد)')

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
        width: 50px;
        height: 50px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        object-fit: cover;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
</style>

<div class="header-actions">
    <h2 style="color: var(--primary-gold); margin: 0;">جميع التحولات</h2>
    <a href="{{ route('admin.transformations.create') }}" class="btn-add">➕ إضافة تحول جديد</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>صورة قبل</th>
                <th>صورة بعد</th>
                <th>العنوان</th>
                <th>الفئة</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transformations as $transformation)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($transformation->before_image)
                        <div class="image-preview">
                            <img src="{{ asset('storage/' . $transformation->before_image) }}" 
                                 alt="قبل" 
                                 onerror="this.parentElement.innerHTML='❌'">
                        </div>
                    @else
                        <span style="color: var(--text-muted); font-size: 12px;">لا توجد</span>
                    @endif
                </td>
                <td>
                    @if($transformation->after_image)
                        <div class="image-preview">
                            <img src="{{ asset('storage/' . $transformation->after_image) }}" 
                                 alt="بعد" 
                                 onerror="this.parentElement.innerHTML='❌'">
                        </div>
                    @else
                        <span style="color: var(--text-muted); font-size: 12px;">لا توجد</span>
                    @endif
                </td>
                <td><strong>{{ $transformation->title_ar }}</strong></td>
                <td>
                    <span class="category-badge">
                        @switch($transformation->category)
                            @case('makeup') 💄 مكياج @break
                            @case('hair') ✂️ شعر @break
                            @case('skincare') ✨ عناية بالبشرة @break
                            @default {{ $transformation->category }}
                        @endswitch
                    </span>
                </td>
                <td>{{ $transformation->order }}</td>
                <td>
                    <button class="btn-toggle {{ $transformation->is_active ? 'active' : 'inactive' }}"
                            onclick="toggleStatus({{ $transformation->id }})">
                        {{ $transformation->is_active ? '✅ نشط' : '❌ غير نشط' }}
                    </button>
                </td>
                <td>
                    <a href="{{ route('admin.transformations.edit', $transformation) }}" class="btn-edit">✏️ تعديل</a>
                    <form action="{{ route('admin.transformations.destroy', $transformation) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا التحول؟')">🗑️ حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <span class="icon">🔄</span>
                        <p>لا توجد تحولات مسجلة</p>
                        <a href="{{ route('admin.transformations.create') }}" class="btn-add" style="margin-top: 15px; display: inline-block;">➕ إضافة تحول جديد</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleStatus(id) {
        fetch(`/admin/transformations/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

@endsection