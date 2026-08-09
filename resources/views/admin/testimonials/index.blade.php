@extends('admin.layouts.admin')

@section('title', 'آراء العملاء')

@section('page-title', '💬 إدارة آراء العملاء')

@section('content')

<style>
    .header-actions { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
    .btn-add, .btn-edit, .btn-delete { border: none; border-radius: 8px; cursor: pointer; font-weight: 700; }
    .btn-add { padding: 10px 24px; background: var(--primary-gold); color: #000; }
    .btn-edit { padding: 8px 16px; background: var(--info-color); color: #000; text-decoration: none; }
    .btn-delete { padding: 8px 16px; background: var(--danger-color); color: #fff; }
    .table-container { background: var(--surface-dark); border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px 16px; text-align: right; border-bottom: 1px solid var(--border-color); }
    th { background: var(--bg-dark); color: var(--primary-gold); }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; }
    .badge.active { background: rgba(76, 175, 80, 0.2); color: var(--success-color); }
    .badge.inactive { background: rgba(255, 77, 77, 0.2); color: var(--danger-color); }
</style>

<div class="header-actions">
    <h2 style="color: var(--primary-gold); margin: 0;">آراء العملاء</h2>
    <a href="{{ route('admin.testimonials.create') }}" class="btn-add">➕ إضافة رأي جديد</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>الدور</th>
                <th>التقييم</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($testimonials as $testimonial)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $testimonial->client_name }}</td>
                <td>{{ $testimonial->client_role_ar }}</td>
                <td>{{ $testimonial->rating }}/5</td>
                <td><span class="badge {{ $testimonial->is_active ? 'active' : 'inactive' }}">{{ $testimonial->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                <td>
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-edit">✏️ تعديل</a>
                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف الرأي؟')">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">لا توجد آراء حتى الآن</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
