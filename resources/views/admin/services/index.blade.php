@extends('admin.layouts.admin')

@section('title', 'الخدمات')

@section('page-title', 'إدارة الخدمات')

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

    .service-icon {
        font-size: 28px;
    }

    .features-list {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .features-list .tag {
        background: var(--surface-light);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        color: var(--text-muted);
    }
</style>

<div class="header-actions">
    <h2 style="color: var(--primary-gold); margin: 0;">جميع الخدمات</h2>
    <a href="{{ route('admin.services.create') }}" class="btn-add">➕ إضافة خدمة جديدة</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الأيقونة</th>
                <th>الاسم</th>
                <th>المميزات</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="service-icon">{{ $service->icon }}</td>
                <td><strong>{{ $service->title_ar }}</strong><br><small style="color: var(--text-muted);">{{ $service->label }}</small></td>
                <td>
                    <div class="features-list">
                        @foreach($service->features_ar as $feature)
                            <span class="tag">{{ $feature }}</span>
                        @endforeach
                    </div>
                </td>
                <td>{{ $service->order }}</td>
                <td>
                    <button class="btn-toggle {{ $service->is_active ? 'active' : 'inactive' }}"
                            onclick="toggleStatus({{ $service->id }})">
                        {{ $service->is_active ? '✅ نشط' : '❌ غير نشط' }}
                    </button>
                </td>
                <td>
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn-edit">✏️ تعديل</a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد؟')">🗑️ حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                    لا توجد خدمات مسجلة
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleStatus(id) {
        fetch(`/admin/services/${id}/toggle`, {
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