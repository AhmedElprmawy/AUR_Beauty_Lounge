@extends('admin.layouts.admin')

@section('title', 'باقات العرائس')

@section('page-title', '💎 إدارة باقات العرائس')

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
    <h2 style="color: var(--primary-gold); margin: 0;">كل باقات العرائس</h2>
    <a href="{{ route('admin.packages.create') }}" class="btn-add">➕ إضافة باقة جديدة</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الفئة</th>
                <th>السعر</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($packages as $package)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $package->name_ar }}</strong><br><small style="color: var(--text-muted);">{{ $package->name_en }}</small></td>
                <td>{{ ucfirst($package->tier) }}</td>
                <td>{{ $package->price }} {{ $package->currency }}</td>
                <td>{{ $package->order }}</td>
                <td><span class="badge {{ $package->is_active ? 'active' : 'inactive' }}">{{ $package->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                <td>
                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn-edit">✏️ تعديل</a>
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف الباقة؟')">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">لا توجد باقات مسجلة</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
