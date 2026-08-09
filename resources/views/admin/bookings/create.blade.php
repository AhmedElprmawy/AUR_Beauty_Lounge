@extends('admin.layouts.admin')

@section('title', 'إضافة حجز جديد')

@section('page-title', '➕ إضافة حجز جديد')

@section('content')

<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Cairo', sans-serif;
        font-size: 15px;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary-gold);
        outline: none;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
    }

    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: var(--text-muted);
        font-size: 14px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }

    .info-box {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.15);
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .info-box .label {
        color: var(--text-muted);
        font-size: 13px;
    }

    .info-box .value {
        font-weight: 600;
        color: var(--primary-gold);
    }

    .btn-submit {
        padding: 14px 40px;
        background: var(--primary-gold);
        color: #000;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: var(--dark-gold);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .btn-cancel {
        padding: 14px 30px;
        background: var(--surface-light);
        color: var(--text-main);
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-cancel:hover {
        background: var(--border-color);
    }

    .helper-text {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .price-total {
        background: rgba(212, 175, 55, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 8px;
        padding: 15px 20px;
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .price-total .label {
        color: var(--text-muted);
        font-size: 14px;
    }

    .price-total .amount {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary-gold);
    }

    .remaining-display {
        font-size: 18px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 8px;
        display: inline-block;
    }

    .remaining-display.positive {
        color: var(--danger-color);
        background: rgba(255, 77, 77, 0.1);
    }

    .remaining-display.zero {
        color: var(--success-color);
        background: rgba(76, 175, 80, 0.1);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-row-3 {
            grid-template-columns: 1fr;
        }
        .info-box {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="form-container">

    <div class="info-box">
        <div>
            <span class="label">📌 إضافة حجز جديد</span>
            <span class="value">املأ جميع الحقول المطلوبة</span>
        </div>
    </div>

    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf

        <!-- اسم العميل ورقم الهاتف -->
        <div class="form-row">
            <div class="form-group">
                <label>👤 اسم العميل *</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="مثال: سارة أحمد">
                @error('customer_name')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>📱 رقم الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="مثال: 0100000000">
                @error('phone')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- البريد الإلكتروني -->
        <div class="form-group">
            <label>📧 البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com">
            <div class="helper-text">اختياري - سيتم إرسال تأكيد الحجز عليه</div>
            @error('email')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- الخدمة والموظف -->
        <div class="form-row">
            <div class="form-group">
                <label>💇‍♀️ الخدمة *</label>
                <select name="service_id" required>
                    <option value="">اختر الخدمة</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->icon }} {{ $service->title_ar }}
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>👤 الموظف *</label>
                <select name="staff_id" required>
                    <option value="">اختر الموظف</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name_ar }}
                        </option>
                    @endforeach
                </select>
                @error('staff_id')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- التاريخ والوقت -->
        <div class="form-row">
            <div class="form-group">
                <label>📅 التاريخ *</label>
                <input type="date" name="date" value="{{ old('date') }}" required>
                <div class="helper-text">اختر تاريخ الحجز</div>
                @error('date')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>⏰ الوقت *</label>
                <input type="time" name="time" value="{{ old('time') }}" required>
                <div class="helper-text">اختر وقت الحجز</div>
                @error('time')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- ✅ الحقول المالية -->
        <div class="form-row-3">
            <div class="form-group">
                <label>💰 السعر الإجمالي *</label>
                <input type="number" name="total_price" id="totalPrice" value="{{ old('total_price') }}" step="0.01" min="0" required placeholder="0.00" oninput="calculateRemaining()">
                <div class="helper-text">السعر الكامل للخدمة</div>
                @error('total_price')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>💳 العربون (المدفوع)</label>
                <input type="number" name="deposit" id="deposit" value="{{ old('deposit') }}" step="0.01" min="0" placeholder="0.00" oninput="calculateRemaining()">
                <div class="helper-text">المبلغ المدفوع مقدمًا</div>
                @error('deposit')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>📊 الحالة</label>
                <select name="status">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ معلق</option>
                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>✅ مؤكد</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✔️ مكتمل</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ ملغي</option>
                </select>
                @error('status')
                    <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- ✅ عرض المتبقي -->
        <div class="price-total" id="remainingBox">
            <span class="label">📊 المتبقي</span>
            <span class="remaining-display zero" id="remainingDisplay">0.00 ج.م</span>
        </div>

        <!-- ملاحظات -->
        <div class="form-group">
            <label>📝 ملاحظات</label>
            <textarea name="notes" rows="3" placeholder="أي ملاحظات إضافية عن الحجز...">{{ old('notes') }}</textarea>
            @error('notes')
                <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- الأزرار -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ الحجز</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn-cancel">❌ إلغاء</a>
        </div>

    </form>
</div>

<script>
    // ===== حساب المتبقي تلقائياً =====
    function calculateRemaining() {
        const totalPrice = parseFloat(document.getElementById('totalPrice').value) || 0;
        const deposit = parseFloat(document.getElementById('deposit').value) || 0;
        const remaining = totalPrice - deposit;
        const display = document.getElementById('remainingDisplay');

        display.textContent = remaining.toFixed(2) + ' ج.م';

        if (remaining > 0) {
            display.className = 'remaining-display positive';
        } else {
            display.className = 'remaining-display zero';
        }
    }

    // حساب عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        calculateRemaining();
    });
</script>

@endsection