@extends('admin.layouts.admin')

@section('title', 'الفيديوهات')

@section('page-title', '🎥 إدارة الفيديوهات')

@section('content')

<style>
    .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
    .btn-add { padding: 10px 24px; background: var(--primary-gold); color: #000; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-family: 'Cairo', sans-serif; transition: 0.3s; }
    .btn-add:hover { background: var(--dark-gold); transform: translateY(-2px); }
    .btn-edit { padding: 4px 12px; background: var(--info-color); color: #000; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
    .btn-delete { padding: 4px 12px; background: var(--danger-color); color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }
    .btn-toggle { padding: 4px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }
    .btn-toggle.active { background: var(--success-color); color: #000; }
    .btn-toggle.inactive { background: var(--danger-color); color: #fff; }
    .video-preview { width: 80px; height: 60px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); background: #000; }
    .video-preview video { width: 100%; height: 100%; object-fit: cover; }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
    .empty-state .icon { font-size: 48px; display: block; margin-bottom: 15px; }
</style>

<div class="header-actions">
    <h2 style="color: var(--primary-gold); margin: 0;">جميع الفيديوهات</h2>
    <a href="{{ route('admin.videos.create') }}" class="btn-add">➕ إضافة فيديو جديد</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>🎥 الفيديو</th>
                <th>العنوان</th>
                <th>الوصف</th>
                <th>الحالة</th>
                <th>تاريخ الإضافة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $video)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($video->video)
                        <div class="video-preview">
                            <video muted onmouseover="this.play()" onmouseout="this.pause(); this.currentTime = 0;">
                                <source src="{{ asset('storage/' . $video->video) }}" type="video/mp4">
                            </video>
                        </div>
                    @else
                        <span style="color: var(--text-muted);">—</span>
                    @endif
                </td>
                <td><strong>{{ $video->title_ar }}</strong></td>
                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $video->description_ar }}</td>
                <td>
                    <button class="btn-toggle {{ $video->is_active ? 'active' : 'inactive' }}"
                            onclick="toggleStatus({{ $video->id }})">
                        {{ $video->is_active ? '✅ نشط' : '❌ غير نشط' }}
                    </button>
                </td>
                <td>{{ $video->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('admin.videos.edit', $video) }}" class="btn-edit">✏️ تعديل</a>
                    <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الفيديو؟')">🗑️ حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <span class="icon">🎥</span>
                        <p>لا توجد فيديوهات مسجلة</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleStatus(id) {
        fetch(`/admin/videos/${id}/toggle`, {
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