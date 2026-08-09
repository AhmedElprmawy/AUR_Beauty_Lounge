@extends('admin.layouts.admin')

@section('title', 'الحجوزات')

@section('page-title', '📅 إدارة الحجوزات')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: var(--surface-dark);
        padding: 18px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        text-align: center;
        transition: 0.3s;
    }

    .stat-card:hover {
        border-color: var(--primary-gold);
    }

    .stat-card .number {
        font-size: 24px;
        font-weight: 800;
    }

    .stat-card .label {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .stat-card.total .number { color: var(--primary-gold); }
    .stat-card.pending .number { color: #ffc107; }
    .stat-card.confirmed .number { color: var(--info-color); }
    .stat-card.completed .number { color: var(--success-color); }
    .stat-card.cancelled .number { color: var(--danger-color); }
    .stat-card.today .number { color: #9c27b0; }
    .stat-card.total-income .number { color: var(--primary-gold); }
    .stat-card.total-deposit .number { color: var(--info-color); }
    .stat-card.custom-services .number { color: var(--info-color); }

    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-bar input,
    .filter-bar select {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Cairo', sans-serif;
        flex: 1;
        min-width: 150px;
        outline: none;
        transition: 0.3s;
    }

    .filter-bar input:focus,
    .filter-bar select:focus {
        border-color: var(--primary-gold);
    }

    .filter-bar input::placeholder {
        color: var(--text-muted);
    }

    .btn-filter {
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        background: var(--primary-gold);
        color: #000;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-filter:hover {
        background: var(--dark-gold);
        transform: translateY(-2px);
    }

    .btn-reset {
        padding: 10px 24px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--surface-light);
        color: var(--text-main);
        font-weight: 600;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-reset:hover {
        background: var(--border-color);
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
    .status-confirmed { background: rgba(0, 188, 212, 0.2); color: var(--info-color); }
    .status-completed { background: rgba(76, 175, 80, 0.2); color: var(--success-color); }
    .status-cancelled { background: rgba(255, 77, 77, 0.2); color: var(--danger-color); }

    .reminder-status {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 4px;
    }
    .reminder-status.sent { background: var(--success-color); }
    .reminder-status.pending { background: #ffc107; }
    .reminder-status.failed { background: var(--danger-color); }
    .reminder-status.none { background: var(--border-color); }

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

    .btn-add-custom {
        padding: 10px 24px;
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-add-custom:hover {
        background: #0097a7;
        transform: translateY(-2px);
    }

    .btn-reminder {
        padding: 2px 8px;
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-reminder:hover {
        background: #0097a7;
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .status-select {
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Cairo', sans-serif;
        font-size: 12px;
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

    .base-price-cell {
        font-weight: 600;
        color: var(--text-muted);
    }

    .total-price-cell {
        font-size: 16px;
        font-weight: 800;
        color: var(--primary-gold);
    }

    .deposit-cell {
        font-weight: 600;
        color: var(--info-color);
        white-space: nowrap;
    }

    .remaining-cell {
        font-weight: 600;
        white-space: nowrap;
    }

    .remaining-cell.zero { color: var(--success-color); }
    .remaining-cell.positive { color: var(--danger-color); }

    .table-container {
        overflow-x: auto;
    }

    .table-container table {
        min-width: 1800px;
    }

    .custom-service-tag {
        display: inline-block;
        background: var(--info-color);
        color: #000;
        padding: 1px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 4px;
    }

    .custom-service-item {
        display: flex;
        justify-content: space-between;
        padding: 2px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .custom-service-item:last-child {
        border-bottom: none;
    }

    .custom-services-total-row {
        display: flex;
        justify-content: space-between;
        padding-top: 4px;
        border-top: 1px solid var(--primary-gold);
        font-size: 12px;
        font-weight: 700;
    }

    .custom-services-total-row .gold {
        color: var(--primary-gold);
    }

    .reminder-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 11px;
    }

    .reminder-row {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .reminder-row .label {
        color: var(--text-muted);
        font-size: 10px;
        min-width: 45px;
    }

    .reminder-row .status-text {
        font-size: 10px;
        padding: 1px 6px;
        border-radius: 10px;
    }

    .reminder-row .status-text.sent {
        color: var(--success-color);
    }
    .reminder-row .status-text.pending {
        color: #ffc107;
    }
    .reminder-row .status-text.failed {
        color: var(--danger-color);
    }

    .custom-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        display: none;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .custom-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .custom-modal-overlay.active .custom-modal-box {
        transform: scale(1);
    }

    .custom-modal-box {
        background: var(--surface-dark);
        border: 2px solid var(--primary-gold);
        border-radius: 15px;
        padding: 30px;
        width: 95%;
        max-width: 600px;
        text-align: right;
        box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        transform: scale(0.8);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-family: 'Cairo', sans-serif;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h3 {
        color: var(--primary-gold);
        margin: 0;
        font-size: 22px;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 24px;
        cursor: pointer;
        transition: 0.3s;
    }

    .modal-close-btn:hover {
        color: var(--danger-color);
    }

    .form-group-modal {
        margin-bottom: 20px;
    }

    .form-group-modal label {
        display: block;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group-modal input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Cairo', sans-serif;
        font-size: 15px;
        outline: none;
        transition: 0.3s;
    }

    .form-group-modal input:focus {
        border-color: var(--primary-gold);
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--surface-dark);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        margin-top: 4px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .search-result-item {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: 0.2s;
    }

    .search-result-item:hover {
        background: rgba(212, 175, 55, 0.05);
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .selected-customer {
        display: none;
        background: rgba(212, 175, 55, 0.05);
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        margin-bottom: 15px;
    }

    .selected-customer.show {
        display: block;
    }

    .btn-add-custom-modal {
        flex: 1;
        min-width: 150px;
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 8px;
        padding: 14px 24px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-add-custom-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 188, 212, 0.3);
    }

    .btn-add-custom-modal:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-cancel-modal {
        flex: 0.5;
        min-width: 100px;
        background: var(--surface-light);
        color: var(--text-main);
        border: none;
        border-radius: 8px;
        padding: 14px 24px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-cancel-modal:hover {
        background: var(--border-color);
    }

    .error-message {
        display: none;
        background: rgba(255, 77, 77, 0.15);
        color: var(--danger-color);
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 15px;
        font-size: 14px;
        border-right: 4px solid var(--danger-color);
    }

    .error-message.show {
        display: block;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    /* Toast Styles */
    #toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--surface-dark);
        border: 1px solid var(--primary-gold);
        color: var(--text-main);
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
    }

    #toast.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>

<!-- ===== Toast Container ===== -->
<div id="toast"></div>

<!-- ===== إحصائيات مع التعامل مع القيم الخالية ===== -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="number">{{ isset($stats['total']) ? (int) $stats['total'] : 0 }}</div>
        <div class="label">📊 إجمالي الحجوزات</div>
    </div>
    <div class="stat-card pending">
        <div class="number">{{ isset($stats['pending']) ? (int) $stats['pending'] : 0 }}</div>
        <div class="label">⏳ معلق</div>
    </div>
    <div class="stat-card confirmed">
        <div class="number">{{ isset($stats['confirmed']) ? (int) $stats['confirmed'] : 0 }}</div>
        <div class="label">✅ مؤكد</div>
    </div>
    <div class="stat-card completed">
        <div class="number">{{ isset($stats['completed']) ? (int) $stats['completed'] : 0 }}</div>
        <div class="label">✔️ مكتمل</div>
    </div>
    <div class="stat-card cancelled">
        <div class="number">{{ isset($stats['cancelled']) ? (int) $stats['cancelled'] : 0 }}</div>
        <div class="label">❌ ملغي</div>
    </div>
    <div class="stat-card today">
        <div class="number">{{ isset($stats['today']) ? (int) $stats['today'] : 0 }}</div>
        <div class="label">📅 حجوزات اليوم</div>
    </div>
    <div class="stat-card total-income">
        <div class="number">{{ isset($stats['total_income']) ? number_format((float) $stats['total_income'], 2) : '0.00' }} ج.م</div>
        <div class="label">💰 إجمالي المبيعات</div>
    </div>
    <div class="stat-card total-deposit">
        <div class="number">{{ isset($stats['total_deposit']) ? number_format((float) $stats['total_deposit'], 2) : '0.00' }} ج.م</div>
        <div class="label">💳 إجمالي العربون</div>
    </div>
    <div class="stat-card custom-services">
        <div class="number">{{ isset($stats['total_custom_services']) ? number_format((float) $stats['total_custom_services'], 2) : '0.00' }} ج.م</div>
        <div class="label">✨ إجمالي الخدمات اللحظية</div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
            {{ isset($stats['custom_services_count']) ? (int) $stats['custom_services_count'] : 0 }} خدمة
        </div>
    </div>
</div>

<!-- ===== Header Actions ===== -->
<div class="header-actions">
    <h2 style="color: var(--primary-gold); margin: 0;">جميع الحجوزات</h2>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <button class="btn-add-custom" onclick="openCustomServiceModal()">
            ✨ إضافة خدمة لحظية
        </button>
        <a href="{{ route('admin.bookings.create') }}" class="btn-add">➕ إضافة حجز جديد</a>
    </div>
</div>

<!-- ===== Filter Bar ===== -->
<div class="filter-bar">
    <input type="text" id="searchInput" placeholder="🔍 بحث بالاسم أو رقم الهاتف..." onkeyup="applyFilters()">
    <select id="statusFilter" onchange="applyFilters()">
        <option value="all">جميع الحالات</option>
        <option value="pending">⏳ معلق</option>
        <option value="confirmed">✅ مؤكد</option>
        <option value="completed">✔️ مكتمل</option>
        <option value="cancelled">❌ ملغي</option>
    </select>
    <input type="date" id="dateFrom" onchange="applyFilters()">
    <input type="date" id="dateTo" onchange="applyFilters()">
    <button onclick="applyFilters()" class="btn-filter">🔍 بحث</button>
    <button onclick="resetFilters()" class="btn-reset">🔄 إعادة ضبط</button>
</div>

<!-- ===== Table ===== -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>الهاتف</th>
                <th>الخدمة</th>
                <th>الموظف</th>
                <th>التاريخ</th>
                <th>الوقت</th>
                <th>السعر الأساسي</th>
                <th>✨ الخدمات اللحظية</th>
                <th>السعر الإجمالي</th>
                <th>العربون</th>
                <th>المتبقي</th>
                <th>🔔 التذكيرات</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody id="bookingsTable">
            @forelse($bookings as $booking)
            @php
                $basePrice = (float) ($booking->total_price ?? 0);
                $customTotal = (float) $booking->customServices->sum('price');
                $grandTotal = $basePrice + $customTotal;
                $deposit = (float) ($booking->deposit ?? 0);
                $remaining = $grandTotal - $deposit;

                $sevenDays = $booking->getReminder('seven_days', 'customer');
                $oneDayCustomer = $booking->getReminder('one_day_customer', 'customer');
                $oneDayOwner = $booking->getReminder('one_day_owner', 'owner');
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $booking->customer_name }}</strong></td>
                <td>{{ $booking->phone }}</td>
                <td>{{ $booking->service->title_ar ?? '—' }}</td>
                <td>{{ $booking->staff->name_ar ?? '—' }}</td>
                <td>{{ $booking->date->format('Y-m-d') }}</td>
                <td>{{ $booking->time }}</td>
                
                <td class="base-price-cell">{{ number_format($basePrice, 2) }} ج.م</td>
                
                <td>
                    @if($booking->customServices && $booking->customServices->count() > 0)
                        @foreach($booking->customServices as $cs)
                            <div class="custom-service-item">
                                <span style="font-size: 12px;">
                                    <span class="custom-service-tag">⚡</span>
                                    {{ $cs->name }}
                                </span>
                                <span style="color: var(--primary-gold); font-size: 12px;">
                                    {{ number_format($cs->price, 2) }} ج.م
                                </span>
                            </div>
                        @endforeach
                        <div class="custom-services-total-row">
                            <span>إجمالي الخدمات اللحظية</span>
                            <span class="gold">{{ number_format($customTotal, 2) }} ج.م</span>
                        </div>
                    @else
                        <span style="color: var(--text-muted); font-size: 12px;">—</span>
                    @endif
                </td>
                
                <td class="total-price-cell">{{ number_format($grandTotal, 2) }} ج.م</td>
                
                <td class="deposit-cell">{{ number_format($deposit, 2) }} ج.م</td>
                <td class="remaining-cell {{ $remaining > 0 ? 'positive' : 'zero' }}">
                    {{ number_format($remaining, 2) }} ج.م
                </td>
                
                <td>
                    <div class="reminder-cell">
                        <div class="reminder-row">
                            <span class="label">7 أيام:</span>
                            @if($sevenDays)
                                <span class="status-text {{ $sevenDays->status }}">
                                    {{ $sevenDays->status == 'sent' ? '✅' : ($sevenDays->status == 'failed' ? '❌' : '⏳') }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 10px;">—</span>
                            @endif
                            <button class="btn-reminder" onclick="sendReminder({{ $booking->id }}, 'seven_days')" title="إرسال تذكير 7 أيام">
                                📤
                            </button>
                        </div>
                        <div class="reminder-row">
                            <span class="label">يوم (عميل):</span>
                            @if($oneDayCustomer)
                                <span class="status-text {{ $oneDayCustomer->status }}">
                                    {{ $oneDayCustomer->status == 'sent' ? '✅' : ($oneDayCustomer->status == 'failed' ? '❌' : '⏳') }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 10px;">—</span>
                            @endif
                            <button class="btn-reminder" onclick="sendReminder({{ $booking->id }}, 'one_day_customer')" title="إرسال تذكير يوم للعميل">
                                📤
                            </button>
                        </div>
                        <div class="reminder-row">
                            <span class="label">يوم (مالك):</span>
                            @if($oneDayOwner)
                                <span class="status-text {{ $oneDayOwner->status }}">
                                    {{ $oneDayOwner->status == 'sent' ? '✅' : ($oneDayOwner->status == 'failed' ? '❌' : '⏳') }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 10px;">—</span>
                            @endif
                            <button class="btn-reminder" onclick="sendReminder({{ $booking->id }}, 'one_day_owner')" title="إرسال تذكير يوم للمالك">
                                📤
                            </button>
                        </div>
                    </div>
                </td>
                
                <td>
                    <span class="status-badge status-{{ $booking->status }}">
                        @switch($booking->status)
                            @case('pending') ⏳ معلق @break
                            @case('confirmed') ✅ مؤكد @break
                            @case('completed') ✔️ مكتمل @break
                            @case('cancelled') ❌ ملغي @break
                            @default {{ $booking->status }}
                        @endswitch
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-view">👁️</a>
                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-edit">✏️</a>
                         <button onclick="sendWhatsAppFromIndex({{ $booking->id }})" class="btn-whatsapp" style="padding: 4px 12px; background: #25D366; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                         <i class="fab fa-whatsapp"></i></button>
                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الحجز؟')">🗑️</button>
                        </form>
                        <select class="status-select" onchange="updateStatus({{ $booking->id }}, this.value)">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>⏳ معلق</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>✅ مؤكد</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>✔️ مكتمل</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>❌ ملغي</option>
                        </select>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="15" style="text-align: center; color: var(--text-muted); padding: 40px;">
                    لا توجد حجوزات مسجلة
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ===== Modal: إضافة خدمة لحظية ===== -->
<div id="customServiceModal" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="modal-header">
            <h3>✨ إضافة خدمة لحظية</h3>
            <button class="modal-close-btn" onclick="closeCustomServiceModal()">✕</button>
        </div>

        <div class="form-group-modal">
            <label>🔍 البحث عن عميل</label>
            <div style="position: relative;">
                <input type="text" id="customerSearchInput" 
                       placeholder="ابحث باسم العميل أو رقم الهاتف..." 
                       oninput="searchCustomers(this.value)">
                <div id="searchResults" class="search-results"></div>
            </div>
        </div>

        <div id="selectedCustomerDisplay" class="selected-customer">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <strong id="selectedCustomerName" style="color: var(--text-main);">—</strong>
                    <span id="selectedCustomerPhone" style="color: var(--text-muted); font-size: 13px; margin-right: 10px;">—</span>
                    <span id="selectedBookingId" style="display: none;">—</span>
                    <span style="background: var(--primary-gold); color: #000; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        حجز #<span id="selectedBookingNumber">—</span>
                    </span>
                </div>
                <button onclick="clearSelectedCustomer()" 
                        style="background: none; border: none; color: var(--danger-color); cursor: pointer; font-size: 14px; transition: 0.3s;"
                        onmouseover="this.style.color='#cc0000'"
                        onmouseout="this.style.color='var(--danger-color)'">
                    ✕ إلغاء
                </button>
            </div>
            <div style="margin-top: 5px; font-size: 13px; color: var(--text-muted);">
                <span>الخدمة الأساسية: </span>
                <span id="selectedCustomerService" style="color: var(--text-main);">—</span>
                <span style="margin-right: 15px;">السعر: </span>
                <span id="selectedCustomerPrice" style="color: var(--primary-gold);">—</span>
            </div>
        </div>

        <div class="form-group-modal">
            <label>📝 اسم الخدمة اللحظية *</label>
            <input type="text" id="customServiceName" placeholder="مثال: Hair Mask, Treatment إضافي...">
        </div>

        <div class="form-group-modal">
            <label>💰 السعر *</label>
            <input type="number" id="customServicePrice" placeholder="0.00" step="0.01" min="0" 
                   oninput="if(this.value < 0) this.value = 0">
        </div>

        <div class="form-group-modal">
            <label>📝 ملاحظات (اختياري)</label>
            <input type="text" id="customServiceNotes" placeholder="أي ملاحظات إضافية...">
        </div>

        <div class="modal-actions">
            <button class="btn-add-custom-modal" id="addCustomServiceBtn" onclick="addCustomService()">
                ➕ إضافة الخدمة
            </button>
            <button class="btn-cancel-modal" onclick="closeCustomServiceModal()">إلغاء</button>
        </div>

        <div id="customServiceError" class="error-message">
            ⚠️ <span id="errorMessage">حدث خطأ</span>
        </div>
    </div>
</div>

<script>
    // ==========================================
    // 1. تحديث حالة الحجز
    // ==========================================
    function updateStatus(id, status) {
        fetch(`/admin/bookings/${id}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ==========================================
    // 2. البحث والفلترة
    // ==========================================
    function applyFilters() {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;

        let url = `/admin/bookings/filter?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`;
        
        if (dateFrom) {
            url += `&date_from=${encodeURIComponent(dateFrom)}`;
        }
        if (dateTo) {
            url += `&date_to=${encodeURIComponent(dateTo)}`;
        }

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.data) {
                updateTable(data.data);
                showToast(`✅ تم العثور على ${data.data.length} نتيجة`);
            } else if (Array.isArray(data)) {
                updateTable(data);
                showToast(`✅ تم العثور على ${data.length} نتيجة`);
            } else {
                updateTable([]);
                showToast('❌ لا توجد نتائج');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ حدث خطأ في البحث: ' + error.message);
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
        applyFilters();
    }

    // ==========================================
    // 3. تحديث الجدول
    // ==========================================
    function updateTable(bookings) {
        const tbody = document.getElementById('bookingsTable');
        
        if (!bookings || bookings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="15" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        📭 لا توجد حجوزات تطابق البحث
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        bookings.forEach((booking, index) => {
            const statusMap = {
                'pending': '⏳ معلق',
                'confirmed': '✅ مؤكد',
                'completed': '✔️ مكتمل',
                'cancelled': '❌ ملغي'
            };

            const basePrice = Number(booking.total_price || 0);
            const customServicesTotal = (booking.custom_services || []).reduce(
                (sum, service) => sum + Number(service.price || 0),
                0
            );
            const grandTotal = basePrice + customServicesTotal;
            const deposit = Number(booking.deposit || 0);
            const remaining = grandTotal - deposit;
            const remainingClass = remaining > 0 ? 'positive' : 'zero';

            const sevenDays = booking.reminders?.find(r => r.type === 'seven_days' && r.recipient_type === 'customer');
            const oneDayCustomer = booking.reminders?.find(r => r.type === 'one_day_customer' && r.recipient_type === 'customer');
            const oneDayOwner = booking.reminders?.find(r => r.type === 'one_day_owner' && r.recipient_type === 'owner');

            let customServicesHtml = '<span style="color: var(--text-muted); font-size: 12px;">—</span>';
            if (booking.custom_services && booking.custom_services.length > 0) {
                customServicesHtml = '';
                booking.custom_services.forEach(cs => {
                    customServicesHtml += `
                        <div class="custom-service-item">
                            <span style="font-size: 12px;">
                                <span class="custom-service-tag">⚡</span>
                                ${cs.name || 'خدمة'}
                            </span>
                            <span style="color: var(--primary-gold); font-size: 12px; font-weight: 600;">
                                ${Number(cs.price || 0).toFixed(2)} ج.م
                            </span>
                        </div>
                    `;
                });
                const total = booking.custom_services.reduce((sum, cs) => sum + Number(cs.price || 0), 0);
                customServicesHtml += `
                    <div class="custom-services-total-row">
                        <span>إجمالي الخدمات اللحظية</span>
                        <span class="gold">${total.toFixed(2)} ج.م</span>
                    </div>
                `;
            }

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${booking.customer_name || '—'}</strong></td>
                    <td>${booking.phone || '—'}</td>
                    <td>${booking.service?.title_ar || '—'}</td>
                    <td>${booking.staff?.name_ar || '—'}</td>
                    <td>${booking.date || '—'}</td>
                    <td>${booking.time || '—'}</td>
                    <td class="base-price-cell">${Number(basePrice).toFixed(2)} ج.م</td>
                    <td>${customServicesHtml}</td>
                    <td class="total-price-cell">${Number(grandTotal).toFixed(2)} ج.م</td>
                    <td class="deposit-cell">${Number(deposit).toFixed(2)} ج.م</td>
                    <td class="remaining-cell ${remainingClass}">${remaining.toFixed(2)} ج.م</td>
                    <td>
                        <div class="reminder-cell">
                            <div class="reminder-row">
                                <span class="label">7 أيام:</span>
                                ${sevenDays ? `<span class="status-text ${sevenDays.status}">${sevenDays.status == 'sent' ? '✅' : (sevenDays.status == 'failed' ? '❌' : '⏳')}</span>` : '<span style="color: var(--text-muted); font-size: 10px;">—</span>'}
                                <button class="btn-reminder" onclick="sendReminder(${booking.id}, 'seven_days')" title="إرسال تذكير 7 أيام">📤</button>
                            </div>
                            <div class="reminder-row">
                                <span class="label">يوم (عميل):</span>
                                ${oneDayCustomer ? `<span class="status-text ${oneDayCustomer.status}">${oneDayCustomer.status == 'sent' ? '✅' : (oneDayCustomer.status == 'failed' ? '❌' : '⏳')}</span>` : '<span style="color: var(--text-muted); font-size: 10px;">—</span>'}
                                <button class="btn-reminder" onclick="sendReminder(${booking.id}, 'one_day_customer')" title="إرسال تذكير يوم للعميل">📤</button>
                            </div>
                            <div class="reminder-row">
                                <span class="label">يوم (مالك):</span>
                                ${oneDayOwner ? `<span class="status-text ${oneDayOwner.status}">${oneDayOwner.status == 'sent' ? '✅' : (oneDayOwner.status == 'failed' ? '❌' : '⏳')}</span>` : '<span style="color: var(--text-muted); font-size: 10px;">—</span>'}
                                <button class="btn-reminder" onclick="sendReminder(${booking.id}, 'one_day_owner')" title="إرسال تذكير يوم للمالك">📤</button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-${booking.status}">
                            ${statusMap[booking.status] || booking.status}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="/admin/bookings/${booking.id}" class="btn-view">👁️</a>
                            <a href="/admin/bookings/${booking.id}/edit" class="btn-edit">✏️</a>
                            <form action="/admin/bookings/${booking.id}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد؟')">🗑️</button>
                            </form>
                            <select class="status-select" onchange="updateStatus(${booking.id}, this.value)">
                                <option value="pending" ${booking.status == 'pending' ? 'selected' : ''}>⏳ معلق</option>
                                <option value="confirmed" ${booking.status == 'confirmed' ? 'selected' : ''}>✅ مؤكد</option>
                                <option value="completed" ${booking.status == 'completed' ? 'selected' : ''}>✔️ مكتمل</option>
                                <option value="cancelled" ${booking.status == 'cancelled' ? 'selected' : ''}>❌ ملغي</option>
                            </select>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // ==========================================
    // 4. إرسال تذكير
    // ==========================================
    function sendReminder(bookingId, type) {
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

    // ==========================================
    // 5. البحث عن العملاء (لـ Modal)
    // ==========================================
    let selectedBookingId = null;
    let searchTimeout;

    function searchCustomers(query) {
        clearTimeout(searchTimeout);
        
        const resultsContainer = document.getElementById('searchResults');
        
        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/admin/bookings/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(booking => {
                        const grandTotal = Number(booking.grand_total || booking.service_price || 0);
                        const customTotal = Number(booking.custom_services_total || 0);
                        
                        html += `
                            <div class="search-result-item" onclick="selectCustomer(${booking.id}, '${booking.customer_name || ''}', '${booking.phone || ''}', '${booking.service_name || '—'}', ${grandTotal})">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 5px;">
                                    <div>
                                        <strong style="color: var(--text-main);">${booking.customer_name || '—'}</strong>
                                        <span style="color: var(--text-muted); font-size: 13px; margin-right: 10px;">${booking.phone || '—'}</span>
                                        <span style="background: var(--primary-gold); color: #000; padding: 1px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">
                                            حجز #${booking.id}
                                        </span>
                                    </div>
                                    <div style="color: var(--primary-gold); font-weight: 600; font-size: 13px;">
                                        ${grandTotal.toFixed(2)} ج.م
                                    </div>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                    ${booking.service_name || '—'} 
                                    ${customTotal > 0 ? ' • ✨ +' + customTotal.toFixed(2) + ' ج.م' : ''}
                                    <span style="margin-right: 10px; padding: 1px 8px; border-radius: 8px; background: rgba(212,175,55,0.1); color: var(--primary-gold); font-size: 10px;">
                                        ${booking.status || 'نشط'}
                                    </span>
                                </div>
                            </div>
                        `;
                    });
                    resultsContainer.innerHTML = html;
                    resultsContainer.style.display = 'block';
                } else {
                    resultsContainer.innerHTML = `
                        <div style="padding: 16px; color: var(--text-muted); text-align: center; font-size: 14px;">
                            🔍 لا توجد نتائج مطابقة لـ "${query}"
                        </div>
                    `;
                    resultsContainer.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsContainer.innerHTML = `
                    <div style="padding: 16px; color: var(--danger-color); text-align: center; font-size: 14px;">
                        ⚠️ حدث خطأ في البحث: ${error.message}
                    </div>
                `;
                resultsContainer.style.display = 'block';
            });
        }, 400);
    }

    function selectCustomer(id, name, phone, service, price) {
        selectedBookingId = id;
        
        document.getElementById('selectedCustomerName').textContent = name || '—';
        document.getElementById('selectedCustomerPhone').textContent = phone || '—';
        document.getElementById('selectedBookingNumber').textContent = id || '—';
        document.getElementById('selectedCustomerService').textContent = service || '—';
        document.getElementById('selectedCustomerPrice').textContent = (Number(price) || 0).toFixed(2) + ' ج.م';
        document.getElementById('selectedBookingId').textContent = id || '';
        document.getElementById('selectedCustomerDisplay').classList.add('show');
        
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('customerSearchInput').value = '';
        
        setTimeout(() => document.getElementById('customServiceName').focus(), 100);
    }

    function clearSelectedCustomer() {
        selectedBookingId = null;
        document.getElementById('selectedCustomerDisplay').classList.remove('show');
        document.getElementById('selectedBookingId').textContent = '';
        document.getElementById('customerSearchInput').focus();
    }

    // ==========================================
    // 6. إضافة خدمة لحظية
    // ==========================================
    function addCustomService() {
        const bookingId = selectedBookingId;
        const name = document.getElementById('customServiceName').value.trim();
        const price = parseFloat(document.getElementById('customServicePrice').value);
        const notes = document.getElementById('customServiceNotes').value.trim();
        const errorDiv = document.getElementById('customServiceError');
        const errorMsg = document.getElementById('errorMessage');

        if (!bookingId) {
            errorMsg.textContent = 'يرجى اختيار عميل أولاً';
            errorDiv.classList.add('show');
            return;
        }

        if (!name) {
            errorMsg.textContent = 'يرجى إدخال اسم الخدمة';
            errorDiv.classList.add('show');
            document.getElementById('customServiceName').focus();
            return;
        }

        if (!price || price <= 0) {
            errorMsg.textContent = 'يرجى إدخال سعر صحيح أكبر من 0';
            errorDiv.classList.add('show');
            document.getElementById('customServicePrice').focus();
            return;
        }

        errorDiv.classList.remove('show');

        const btn = document.getElementById('addCustomServiceBtn');
        btn.disabled = true;
        btn.textContent = '⏳ جاري الإضافة...';

        fetch('/admin/custom-services', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                booking_id: bookingId,
                name: name,
                price: price,
                notes: notes,
                staff_id: null
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('✅ تم إضافة الخدمة اللحظية بنجاح');
                closeCustomServiceModal();
                setTimeout(() => location.reload(), 500);
            } else {
                errorMsg.textContent = data.message || 'حدث خطأ في إضافة الخدمة';
                errorDiv.classList.add('show');
                btn.disabled = false;
                btn.textContent = '➕ إضافة الخدمة';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMsg.textContent = 'حدث خطأ في الاتصال بالخادم';
            errorDiv.classList.add('show');
            btn.disabled = false;
            btn.textContent = '➕ إضافة الخدمة';
        });
    }

    // ==========================================
    // 7. فتح وإغلاق Modal
    // ==========================================
    function openCustomServiceModal() {
        const modal = document.getElementById('customServiceModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        document.getElementById('customerSearchInput').value = '';
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('selectedCustomerDisplay').classList.remove('show');
        document.getElementById('customServiceName').value = '';
        document.getElementById('customServicePrice').value = '';
        document.getElementById('customServiceNotes').value = '';
        document.getElementById('customServiceError').classList.remove('show');
        document.getElementById('addCustomServiceBtn').disabled = false;
        document.getElementById('addCustomServiceBtn').textContent = '➕ إضافة الخدمة';
        
        setTimeout(() => document.getElementById('customerSearchInput').focus(), 300);
    }

    function closeCustomServiceModal() {
        const modal = document.getElementById('customServiceModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ==========================================
    // 8. Toast
    // ==========================================
    function showToast(msg) {
        let toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.classList.add('show');
        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // ==========================================
    // 9. Close modal with Escape
    // ==========================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('customServiceModal');
            if (modal.classList.contains('active')) {
                closeCustomServiceModal();
            }
        }
    });

    // ==========================================
    // 10. Close modal clicking outside
    // ==========================================
    document.getElementById('customServiceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCustomServiceModal();
        }
    });
    function sendWhatsAppFromIndex(bookingId) {
    fetch(`/admin/bookings/${bookingId}/whatsapp-message`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.open(data.whatsapp_url, '_blank');
            showToast('✅ تم فتح WhatsApp بنجاح');
        } else {
            showToast('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ حدث خطأ في الاتصال');
    });
}
function updateStatus(id, status) {
    fetch(`/admin/bookings/${id}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            
            // ✅ إذا تم التأكيد، عرض خيار إرسال WhatsApp
            if (data.whatsapp && status === 'confirmed') {
                if (confirm('✅ تم تأكيد الحجز! هل تريد إرسال تفاصيل الحجز عبر WhatsApp للعميل؟')) {
                    window.open(data.whatsapp.whatsapp_url, '_blank');
                }
            }
            
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

@endsection