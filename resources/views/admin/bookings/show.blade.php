@extends('admin.layouts.admin')

@section('title', 'تفاصيل الحجز')

@section('page-title', '👁️ تفاصيل الحجز')

@section('content')

<style>
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        background: var(--surface-dark);
        padding: 30px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        max-width: 1000px;
        margin: 0 auto;
    }

    .detail-item {
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .detail-item .label {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .detail-item .value {
        font-size: 18px;
        font-weight: 600;
        margin-top: 4px;
    }

    .detail-item .value .gold {
        color: var(--primary-gold);
    }

    .detail-item .value .red {
        color: var(--danger-color);
    }

    .status-badge {
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
    .status-confirmed { background: rgba(0, 188, 212, 0.2); color: var(--info-color); }
    .status-completed { background: rgba(76, 175, 80, 0.2); color: var(--success-color); }
    .status-cancelled { background: rgba(255, 77, 77, 0.2); color: var(--danger-color); }

    .reminder-status-badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .reminder-status-badge.sent {
        background: rgba(76, 175, 80, 0.2);
        color: var(--success-color);
    }

    .reminder-status-badge.pending {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }

    .reminder-status-badge.failed {
        background: rgba(255, 77, 77, 0.2);
        color: var(--danger-color);
    }

    .reminder-status-badge.none {
        background: var(--border-color);
        color: var(--text-muted);
    }

    .custom-service-item {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .custom-service-item:last-child {
        border-bottom: none;
    }

    .custom-service-tag {
        background: var(--info-color);
        color: #000;
        padding: 1px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }

    .custom-service-total {
        display: flex;
        justify-content: space-between;
        padding-top: 10px;
        margin-top: 10px;
        border-top: 2px solid var(--primary-gold);
        font-weight: 700;
    }

    .reminder-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .reminder-table th {
        text-align: right;
        padding: 8px;
        font-size: 13px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color);
    }

    .reminder-table td {
        padding: 8px;
        font-size: 13px;
        border-bottom: 1px solid var(--border-color);
    }

    .reminder-table tr:last-child td {
        border-bottom: none;
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
        display: inline-block;
        font-family: 'Cairo', sans-serif;
    }

    .btn-delete {
        padding: 4px 12px;
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Cairo', sans-serif;
    }

    .btn-view {
        padding: 4px 12px;
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
        font-family: 'Cairo', sans-serif;
    }

    .btn-reminder {
        padding: 4px 12px;
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reminder:hover {
        background: #0097a7;
    }

    .btn-reminder-retry {
        padding: 4px 12px;
        background: var(--success-color);
        color: #000;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reminder-retry:hover {
        background: #388E3C;
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
    }

    .btn-back:hover {
        background: var(--border-color);
    }

    .btn-edit-page {
        padding: 12px 30px;
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-family: 'Cairo', sans-serif;
    }

    .btn-edit-page:hover {
        background: #0097a7;
    }

    .btn-delete-page {
        padding: 12px 30px;
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
    }

    .btn-delete-page:hover {
        background: #cc0000;
    }

    .actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .reminder-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
        .actions {
            flex-direction: column;
            align-items: center;
        }
        .reminder-table {
            font-size: 12px;
        }
        .reminder-table th,
        .reminder-table td {
            padding: 6px 4px;
        }
    }
</style>

<div class="details-grid">
    {{-- ===== معلومات العميل ===== --}}
    <div class="detail-item">
        <div class="label">👤 اسم العميل</div>
        <div class="value">{{ $booking->customer_name }}</div>
    </div>

    <div class="detail-item">
        <div class="label">📱 رقم الهاتف</div>
        <div class="value">{{ $booking->phone }}</div>
    </div>

    <div class="detail-item">
        <div class="label">📧 البريد الإلكتروني</div>
        <div class="value">{{ $booking->email ?? '—' }}</div>
    </div>

    {{-- ===== معلومات الخدمة ===== --}}
    <div class="detail-item">
        <div class="label">💇‍♀️ الخدمة الأساسية</div>
        <div class="value">{{ $booking->service->title_ar ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="label">👤 الموظف</div>
        <div class="value">{{ $booking->staff->name_ar ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="label">📅 التاريخ</div>
        <div class="value">{{ $booking->date->format('Y-m-d') }}</div>
    </div>

    <div class="detail-item">
        <div class="label">⏰ الوقت</div>
        <div class="value">{{ $booking->time }}</div>
    </div>

    {{-- ===== المعلومات المالية ===== --}}
    <div class="detail-item">
        <div class="label">💰 سعر الخدمة الأساسية</div>
        <div class="value gold">{{ number_format($booking->total_price ?? 0, 2) }} ج.م</div>
    </div>

    <div class="detail-item">
        <div class="label">✨ إجمالي الخدمات اللحظية</div>
        <div class="value gold">{{ number_format($booking->customServices->sum('price') ?? 0, 2) }} ج.م</div>
    </div>

    <div class="detail-item">
        <div class="label">💰 السعر الإجمالي</div>
        <div class="value gold" style="font-size: 22px;">
            {{ number_format($booking->grand_total ?? 0, 2) }} ج.م
        </div>
    </div>

    <div class="detail-item">
        <div class="label">💳 العربون</div>
        <div class="value gold">{{ number_format($booking->deposit ?? 0, 2) }} ج.م</div>
    </div>

    <div class="detail-item">
        <div class="label">📊 المتبقي</div>
        <div class="value {{ ($booking->grand_total - $booking->deposit) > 0 ? 'red' : 'gold' }}">
            {{ number_format(($booking->grand_total - $booking->deposit), 2) }} ج.م
        </div>
    </div>

    {{-- ===== الحالة ===== --}}
    <div class="detail-item">
        <div class="label">📊 حالة الحجز</div>
        <div class="value">
            <span class="status-badge status-{{ $booking->status }}">
                @switch($booking->status)
                    @case('pending') ⏳ معلق @break
                    @case('confirmed') ✅ مؤكد @break
                    @case('completed') ✔️ مكتمل @break
                    @case('cancelled') ❌ ملغي @break
                    @default {{ $booking->status }}
                @endswitch
            </span>
        </div>
    </div>

    <div class="detail-item">
        <div class="label">📅 تاريخ الإضافة</div>
        <div class="value">{{ $booking->created_at->format('Y-m-d H:i') }}</div>
    </div>

    {{-- ===== الخدمات اللحظية ===== --}}
    <div class="detail-item" style="grid-column: 1 / -1;">
        <div class="label">✨ الخدمات الإضافية اللحظية</div>
        <div class="value">
            @if($booking->customServices && $booking->customServices->count() > 0)
                @foreach($booking->customServices as $service)
                    <div class="custom-service-item">
                        <div>
                            <span class="custom-service-tag">⚡ لحظي</span>
                            <span style="font-weight: 600;">{{ $service->name }}</span>
                        </div>
                        <span style="color: var(--primary-gold); font-weight: 600;">{{ number_format($service->price, 2) }} ج.م</span>
                    </div>
                @endforeach
                <div class="custom-service-total">
                    <span>إجمالي الخدمات اللحظية</span>
                    <span style="color: var(--primary-gold);">
                        {{ number_format($booking->customServices->sum('price'), 2) }} ج.م
                    </span>
                </div>
            @else
                <span style="color: var(--text-muted);">لا توجد خدمات إضافية لحظية</span>
            @endif
        </div>
    </div>
    <div class="action-buttons">
        {{-- ✅ زر إرسال عبر WhatsApp --}}
        <a class="btn-whatsapp" href="#" onclick="sendWhatsAppMessage({{ $booking->id }})" class="btn-whatsapp" id="whatsappBtn">
            <i class="fab fa-whatsapp"></i>
            📱 إرسال عبر WhatsApp
        </a>

        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-edit">
            ✏️ تعديل الحجز
        </a>

        <a href="{{ route('admin.bookings.index') }}" class="btn-back">
            ← العودة إلى القائمة
        </a>
    </div>
</div>

    {{-- ===== سجل التذكيرات ===== --}}
    <div class="detail-item" style="grid-column: 1 / -1;">
        <div class="label">🔔 سجل التذكيرات</div>
        <div class="value">
            <div class="reminder-actions">
                <button class="btn-reminder" onclick="sendReminderNow({{ $booking->id }}, 'seven_days')">
                    📤 إرسال تذكير 7 أيام
                </button>
                <button class="btn-reminder" onclick="sendReminderNow({{ $booking->id }}, 'one_day_customer')">
                    📤 إرسال تذكير يوم (عميل)
                </button>
                <button class="btn-reminder" onclick="sendReminderNow({{ $booking->id }}, 'one_day_owner')">
                    📤 إرسال تذكير يوم (مالك)
                </button>
            </div>

            @if($booking->reminders && $booking->reminders->count() > 0)
                <table class="reminder-table">
                    <thead>
                        <tr>
                            <th>نوع التذكير</th>
                            <th>المستلم</th>
                            <th>الحالة</th>
                            <th>تاريخ الإرسال</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->reminders as $reminder)
                            <tr>
                                <td>
                                    @switch($reminder->type)
                                        @case('seven_days') 📅 7 أيام @break
                                        @case('one_day_customer') 📅 يوم (عميل) @break
                                        @case('one_day_owner') 📅 يوم (مالك) @break
                                        @default {{ $reminder->type }}
                                    @endswitch
                                </td>
                                <td>
                                    {{ $reminder->recipient_type == 'customer' ? '👤 عميل' : '👔 مالك' }}
                                    <br>
                                    <small style="color: var(--text-muted); font-size: 11px;">{{ $reminder->recipient_phone }}</small>
                                </td>
                                <td>
                                    @if($reminder->status == 'sent')
                                        <span class="reminder-status-badge sent">✅ تم الإرسال</span>
                                        @if($reminder->sent_at)
                                            <br>
                                            <small style="color: var(--text-muted); font-size: 11px;">{{ $reminder->sent_at->format('Y-m-d H:i') }}</small>
                                        @endif
                                    @elseif($reminder->status == 'failed')
                                        <span class="reminder-status-badge failed">❌ فشل</span>
                                        @if($reminder->error_message)
                                            <br>
                                            <small style="color: var(--danger-color); font-size: 11px;">{{ $reminder->error_message }}</small>
                                        @endif
                                    @else
                                        <span class="reminder-status-badge pending">⏳ في الانتظار</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $reminder->sent_at ? $reminder->sent_at->format('Y-m-d H:i') : '—' }}
                                </td>
                                <td>
                                    @if($reminder->status == 'failed')
                                        <button class="btn-reminder-retry" onclick="retryReminder({{ $reminder->id }})">
                                            🔄 إعادة
                                        </button>
                                    @endif
                                    @if($reminder->status == 'pending')
                                        <span style="color: var(--text-muted); font-size: 11px;">في الانتظار</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <span style="color: var(--text-muted);">لا توجد تذكيرات مسجلة</span>
            @endif
        </div>
    </div>

    {{-- ===== ملاحظات ===== --}}
    <div class="detail-item" style="grid-column: 1 / -1;">
        <div class="label">📝 ملاحظات</div>
        <div class="value">{{ $booking->notes ?? 'لا توجد ملاحظات' }}</div>
    </div>
</div>

{{-- ===== أزرار الإجراءات ===== --}}
<div class="actions">
    <a href="{{ route('admin.bookings.index') }}" class="btn-back">⬅ العودة</a>
    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-edit-page">✏️ تعديل</a>
    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete-page" onclick="return confirm('هل أنت متأكد من حذف هذا الحجز؟')">🗑️ حذف</button>
    </form>
</div>

<script>
    // ===== إرسال تذكير فوري =====
    function sendReminderNow(bookingId, type) {
        const typeNames = {
            'seven_days': 'تذكير 7 أيام',
            'one_day_customer': 'تذكير يوم (عميل)',
            'one_day_owner': 'تذكير يوم (مالك)'
        };

        if (!confirm(`هل أنت متأكد من إرسال "${typeNames[type]}" لهذا الحجز؟`)) return;

        fetch('{{ route("admin.reminders.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                booking_id: bookingId,
                type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || '❌ حدث خطأ');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ حدث خطأ في الاتصال');
        });
    }

    // ===== إعادة إرسال تذكير فاشل =====
    function retryReminder(reminderId) {
        if (!confirm('هل أنت متأكد من إعادة إرسال هذا التذكير؟')) return;

        fetch('{{ route("admin.reminders.retry") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reminder_id: reminderId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || '❌ حدث خطأ');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ حدث خطأ في الاتصال');
        });
    }

    // ===== Toast =====
    function showToast(msg) {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    }
    function sendWhatsAppMessage(bookingId) {
        const btn = document.getElementById('whatsappBtn');
        btn.disabled = true;
        btn.textContent = '⏳ جاري التحميل...';

        fetch(`/admin/bookings/${bookingId}/whatsapp-message`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fab fa-whatsapp"></i> 📱 إرسال عبر WhatsApp';

            if (data.success) {
                // ✅ فتح رابط WhatsApp في نافذة جديدة
                window.open(data.whatsapp_url, '_blank');
                showToast('✅ تم فتح WhatsApp بنجاح');
            } else {
                showToast('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = '<i class="fab fa-whatsapp"></i> 📱 إرسال عبر WhatsApp';
            showToast('❌ حدث خطأ في الاتصال');
        });
    }

    function showToast(msg) {
        let toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            toast.style.cssText = `
                position: fixed;
                bottom: 30px;
                left: 30px;
                background: #2d2d2d;
                border: 1px solid #d4af37;
                color: #ffffff;
                padding: 15px 25px;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                font-family: 'Cairo', sans-serif;
                box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                transform: translateY(100px);
                opacity: 0;
                transition: all 0.4s ease;
                z-index: 99999;
                max-width: 400px;
                direction: rtl;
            `;
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(() => {
            toast.style.transform = 'translateY(100px)';
            toast.style.opacity = '0';
        }, 3000);
    }
</script>

@endsection