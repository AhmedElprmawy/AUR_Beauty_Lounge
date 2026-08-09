@extends('admin.layouts.admin')

@section('title', 'تعديل الحجز')

@section('page-title', '✏️ تعديل الحجز')

@section('content')

<style>
    .form-container { max-width: 1000px; margin: 0 auto; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; color: var(--text-muted); font-weight: 600; margin-bottom: 8px; font-size: 14px; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: 12px 16px; border-radius: 8px;
        border: 1px solid var(--border-color); background: var(--bg-dark);
        color: var(--text-main); font-family: 'Cairo', sans-serif; font-size: 15px;
        transition: 0.3s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: var(--primary-gold); outline: none;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
    }
    .form-group textarea { min-height: 80px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

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
    .info-box .label { color: var(--text-muted); font-size: 13px; }
    .info-box .value { font-weight: 600; color: var(--primary-gold); }

    .status-badge { padding: 4px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; }
    .status-pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
    .status-confirmed { background: rgba(0, 188, 212, 0.2); color: var(--info-color); }
    .status-completed { background: rgba(76, 175, 80, 0.2); color: var(--success-color); }
    .status-cancelled { background: rgba(255, 77, 77, 0.2); color: var(--danger-color); }

    .btn-submit {
        padding: 14px 40px; background: var(--primary-gold); color: #000;
        border: none; border-radius: 8px; font-size: 18px; font-weight: 800;
        cursor: pointer; font-family: 'Cairo', sans-serif; transition: 0.3s;
    }
    .btn-submit:hover { background: var(--dark-gold); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3); }

    .btn-cancel {
        padding: 14px 30px; background: var(--surface-light); color: var(--text-main);
        border: none; border-radius: 8px; font-size: 16px; cursor: pointer;
        font-family: 'Cairo', sans-serif; text-decoration: none; display: inline-block;
        transition: 0.3s;
    }
    .btn-cancel:hover { background: var(--border-color); }

    .btn-danger {
        padding: 14px 30px; background: var(--danger-color); color: #fff;
        border: none; border-radius: 8px; font-size: 16px; cursor: pointer;
        font-family: 'Cairo', sans-serif; text-decoration: none; display: inline-block;
        transition: 0.3s;
    }
    .btn-danger:hover { background: #cc0000; transform: translateY(-2px); }

    .helper-text { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

    /* ===== الخدمات الإضافية اللحظية ===== */
    .custom-services-section {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.15);
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
    .custom-services-section .section-title {
        color: var(--primary-gold); font-size: 17px; font-weight: 700; margin-bottom: 15px;
    }

    .custom-service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: var(--bg-dark);
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid var(--border-color);
        transition: 0.3s;
    }
    .custom-service-item:hover { border-color: var(--info-color); }
    .custom-service-item.editing { border-color: var(--primary-gold); background: rgba(212, 175, 55, 0.05); }

    .custom-service-tag {
        background: var(--info-color);
        color: #000;
        padding: 4px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-left: 10px;
    }

    .service-actions {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .btn-edit-service {
        background: var(--info-color);
        color: #000;
        border: none;
        border-radius: 4px;
        padding: 4px 12px;
        cursor: pointer;
        font-size: 12px;
        transition: 0.3s;
    }
    .btn-edit-service:hover { background: #0097a7; }

    .btn-save-service {
        background: var(--success-color);
        color: #000;
        border: none;
        border-radius: 4px;
        padding: 4px 12px;
        cursor: pointer;
        font-size: 12px;
        transition: 0.3s;
    }
    .btn-save-service:hover { background: #388E3C; }

    .btn-cancel-edit-service {
        background: var(--surface-light);
        color: var(--text-main);
        border: none;
        border-radius: 4px;
        padding: 4px 12px;
        cursor: pointer;
        font-size: 12px;
        transition: 0.3s;
    }
    .btn-cancel-edit-service:hover { background: var(--border-color); }

    .btn-remove-service {
        background: var(--danger-color);
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 4px 12px;
        cursor: pointer;
        font-size: 12px;
        transition: 0.3s;
    }
    .btn-remove-service:hover { background: #cc0000; }

    .edit-input {
        background: var(--bg-dark);
        color: var(--text-main);
        border: 1px solid var(--primary-gold);
        border-radius: 4px;
        padding: 4px 8px;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        width: 120px;
    }
    .edit-input-price {
        width: 80px;
        text-align: center;
    }

    .custom-service-box {
        background: rgba(212, 175, 55, 0.05);
        padding: 15px 20px;
        border-radius: 8px;
        border: 1px dashed var(--border-color);
        margin-bottom: 15px;
    }
    .custom-service-box .box-title {
        font-weight: bold; color: var(--primary-gold); margin-bottom: 10px; font-size: 14px;
    }
    .custom-service-box .input-group {
        display: flex; gap: 10px; flex-wrap: wrap;
    }
    .custom-service-box .input-group input {
        padding: 10px 14px; border-radius: 8px;
        border: 1px solid var(--border-color); background: var(--bg-dark);
        color: var(--text-main); font-family: 'Cairo', sans-serif; font-size: 14px;
    }
    .custom-service-box .input-group .name-input { flex: 2; min-width: 150px; }
    .custom-service-box .input-group .price-input { flex: 1; min-width: 100px; text-align: center; }
    .btn-add-custom-service {
        flex: 0.5; min-width: 100px; font-weight: bold;
        background: var(--primary-gold); color: #000; border: none;
        border-radius: 8px; padding: 10px 20px; cursor: pointer;
        font-family: 'Cairo', sans-serif; font-size: 14px; transition: 0.3s;
    }
    .btn-add-custom-service:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); }

    .no-services-msg { color: var(--text-muted); text-align: center; padding: 15px; font-size: 14px; }

    .custom-services-total {
        background: var(--bg-dark); padding: 15px 20px; border-radius: 8px;
        margin-top: 15px; border: 1px solid var(--border-color);
    }
    .custom-services-total .total-row {
        display: flex; justify-content: space-between; font-size: 16px;
    }
    .custom-services-total .total-row .gold { color: var(--primary-gold); font-weight: 700; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .info-box { flex-direction: column; text-align: center; }
        .custom-service-box .input-group { flex-direction: column; }
        .custom-service-box .input-group input { flex: 1; }
        .btn-add-custom-service { width: 100%; }
        .edit-input { width: 100%; }
        .edit-input-price { width: 100%; }
        .service-actions { flex-wrap: wrap; }
    }
</style>

<div class="form-container">

    <!-- معلومات الحجز -->
    <div class="info-box">
        <div><span class="label">📋 رقم الحجز:</span><span class="value">#{{ $booking->id }}</span></div>
        <div><span class="label">📅 تاريخ الإنشاء:</span><span class="value">{{ $booking->created_at->format('Y-m-d H:i') }}</span></div>
        <div>
            <span class="label">📊 الحالة الحالية:</span>
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

    <!-- نموذج التعديل -->
    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- معلومات العميل -->
        <div class="form-row">
            <div class="form-group">
                <label>👤 اسم العميل *</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $booking->customer_name) }}" required placeholder="مثال: سارة أحمد">
                @error('customer_name') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>📱 رقم الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone', $booking->phone) }}" required placeholder="مثال: 0100000000">
                @error('phone') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- البريد الإلكتروني -->
        <div class="form-group">
            <label>📧 البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email', $booking->email) }}" placeholder="example@email.com">
            <div class="helper-text">اختياري - سيتم إرسال تأكيد الحجز عليه</div>
            @error('email') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <!-- الخدمة والموظف -->
        <div class="form-row">
            <div class="form-group">
                <label>💇‍♀️ الخدمة الأساسية *</label>
                <select name="service_id" id="mainService" required>
                    <option value="">اختر الخدمة</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" 
                                data-price="{{ $service->price ?? 0 }}"
                                {{ old('service_id', $booking->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->icon }} {{ $service->title_ar }}
                            @if($service->price) ({{ number_format($service->price, 2) }} ج.م) @endif
                        </option>
                    @endforeach
                </select>
                @error('service_id') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>👤 الموظف *</label>
                <select name="staff_id" required>
                    <option value="">اختر الموظف</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" {{ old('staff_id', $booking->staff_id) == $member->id ? 'selected' : '' }}>
                            {{ $member->name_ar }}
                        </option>
                    @endforeach
                </select>
                @error('staff_id') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>
 <div class="form-row">
            <div class="form-group">
                <label>💰 العربون (المبلغ المدفوع مقدماً)</label>
                <input type="number" 
                       name="deposit" 
                       value="{{ old('deposit', $booking->deposit ?? 0) }}" 
                       step="0.01" 
                       min="0"
                       placeholder="أدخل قيمة العربون...">
                <div class="helper-text">اختياري - سيتم خصمه من إجمالي الحجز</div>
                @error('deposit') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>
        <!-- التاريخ والوقت -->
        <div class="form-row">
            <div class="form-group">
                <label>📅 التاريخ *</label>
                <input type="date" name="date" value="{{ old('date', $booking->date->format('Y-m-d')) }}" required>
                @error('date') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>⏰ الوقت *</label>
                <input type="time" name="time" value="{{ old('time', $booking->time) }}" required>
                @error('time') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- ===== الخدمات الإضافية اللحظية فقط ===== -->
        <div class="custom-services-section">
            <div class="section-title">✨ الخدمات الإضافية اللحظية</div>

            {{-- إضافة خدمة جديدة --}}
            <div class="custom-service-box">
                <div class="box-title">➕ إضافة خدمة لحظية</div>
                <div class="input-group">
                    <input type="text" id="customServiceNameEdit" placeholder="اسم الخدمة..." class="name-input">
                    <input type="number" id="customServicePriceEdit" placeholder="السعر..." class="price-input" step="0.01" min="0" value="0.00">
                    <button type="button" class="btn-add-custom-service" onclick="addCustomServiceEdit()">➕ إضافة</button>
                </div>
            </div>

            {{-- قائمة الخدمات الإضافية --}}
            <div id="customServicesList">
                @if($booking->customServices && $booking->customServices->count() > 0)
                    @foreach($booking->customServices as $service)
                        <div class="custom-service-item" data-id="{{ $service->id }}" data-price="{{ $service->price }}" id="service-{{ $service->id }}">
                            <div class="service-display-{{ $service->id }}" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; flex: 1;">
                                <span class="custom-service-tag">⚡ لحظي</span>
                                <span style="font-weight: 600;" class="service-name-{{ $service->id }}">{{ $service->name }}</span>
                                <span style="color: var(--primary-gold); font-weight: 700;" class="service-price-{{ $service->id }}">
                                    {{ number_format($service->price, 2) }} ج.م
                                </span>
                            </div>
                            <div class="service-edit-{{ $service->id }}" style="display: none; flex: 1;">
                                <input type="text" class="edit-input edit-name-{{ $service->id }}" value="{{ $service->name }}" placeholder="الاسم">
                                <input type="number" class="edit-input edit-input-price edit-price-{{ $service->id }}" value="{{ $service->price }}" step="0.01" min="0" placeholder="السعر">
                            </div>
                            <div class="service-actions">
                                <button class="btn-edit-service" onclick="enableEdit({{ $service->id }})">✏️ تعديل</button>
                                <button class="btn-save-service" onclick="saveEdit({{ $service->id }})" style="display: none;">💾 حفظ</button>
                                <button class="btn-cancel-edit-service" onclick="cancelEdit({{ $service->id }})" style="display: none;">❌ إلغاء</button>
                                <button class="btn-remove-service" onclick="removeCustomServiceEdit({{ $service->id }})">✕</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-services-msg">لا توجد خدمات إضافية لحظية</div>
                @endif
            </div>

            {{-- إجمالي الخدمات الإضافية --}}
            <div class="custom-services-total">
                <div class="total-row">
                    <span>إجمالي الخدمات الإضافية اللحظية</span>
                    <span class="gold" id="customServicesTotalEdit">
                        {{ number_format($booking->customServices->sum('price') ?? 0, 2) }} ج.م
                    </span>
                </div>
            </div>
        </div>

        <!-- ملاحظات -->
        <div class="form-group" style="margin-top: 20px;">
            <label>📝 ملاحظات</label>
            <textarea name="notes" rows="3" placeholder="أي ملاحظات إضافية عن الحجز...">{{ old('notes', $booking->notes) }}</textarea>
            @error('notes') <div style="color: var(--danger-color); font-size: 13px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <!-- ✅ hidden inputs لتجنب أخطاء Validation -->
        <input type="hidden" name="total_price" value="{{ $booking->total_price }}">
        <input type="hidden" name="status" value="{{ $booking->status }}">

        <!-- الأزرار -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit">💾 حفظ التغييرات</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn-cancel">❌ إلغاء</a>
            <button type="button" class="btn-danger" onclick="confirmDelete({{ $booking->id }})">🗑️ حذف الحجز</button>
        </div>

    </form>
</div>

<script>
    // ===== تحديث إجمالي الخدمات اللحظية =====
    function updateCustomServicesTotalEdit() {
        const items = document.querySelectorAll('#customServicesList .custom-service-item');
        let total = 0;
        items.forEach(item => {
            const price = parseFloat(item.dataset.price) || 0;
            total += price;
        });
        document.getElementById('customServicesTotalEdit').textContent = total.toFixed(2) + ' ج.م';
    }

    // ===== ✅ إضافة خدمة لحظية =====
    function addCustomServiceEdit() {
        const bookingId = {{ $booking->id }};
        const nameInput = document.getElementById('customServiceNameEdit');
        const priceInput = document.getElementById('customServicePriceEdit');
        const name = nameInput.value.trim();
        const price = parseFloat(priceInput.value) || 0;
        
        if (!name) { 
            showToast('⚠️ يرجى إدخال اسم الخدمة'); 
            return; 
        }
        if (price <= 0) { 
            showToast('⚠️ يرجى إدخال سعر صحيح للخدمة'); 
            return; 
        }

        const btn = document.querySelector('.btn-add-custom-service');
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
                notes: 'تم الإضافة من تعديل الحجز'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            btn.disabled = false;
            btn.textContent = '➕ إضافة';
            
            if (data.success) {
                const container = document.getElementById('customServicesList');
                const noMsg = container.querySelector('.no-services-msg');
                if (noMsg) noMsg.remove();

                const div = document.createElement('div');
                div.className = 'custom-service-item';
                div.dataset.id = data.data.id;
                div.dataset.price = data.data.price;
                div.id = 'service-' + data.data.id;
                div.innerHTML = `
                    <div class="service-display-${data.data.id}" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; flex: 1;">
                        <span class="custom-service-tag">⚡ لحظي</span>
                        <span style="font-weight: 600;" class="service-name-${data.data.id}">${data.data.name}</span>
                        <span style="color: var(--primary-gold); font-weight: 700;" class="service-price-${data.data.id}">
                            ${parseFloat(data.data.price).toFixed(2)} ج.م
                        </span>
                    </div>
                    <div class="service-edit-${data.data.id}" style="display: none; flex: 1;">
                        <input type="text" class="edit-input edit-name-${data.data.id}" value="${data.data.name}" placeholder="الاسم">
                        <input type="number" class="edit-input edit-input-price edit-price-${data.data.id}" value="${data.data.price}" step="0.01" min="0" placeholder="السعر">
                    </div>
                    <div class="service-actions">
                        <button class="btn-edit-service" onclick="enableEdit(${data.data.id})">✏️ تعديل</button>
                        <button class="btn-save-service" onclick="saveEdit(${data.data.id})" style="display: none;">💾 حفظ</button>
                        <button class="btn-cancel-edit-service" onclick="cancelEdit(${data.data.id})" style="display: none;">❌ إلغاء</button>
                        <button class="btn-remove-service" onclick="removeCustomServiceEdit(${data.data.id})">✕</button>
                    </div>
                `;
                container.appendChild(div);

                updateCustomServicesTotalEdit();
                nameInput.value = '';
                priceInput.value = '0.00';
                showToast('✅ تم إضافة الخدمة بنجاح');
            } else {
                showToast(data.message || '❌ حدث خطأ في إضافة الخدمة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.textContent = '➕ إضافة';
            showToast('❌ حدث خطأ في الاتصال بالخادم: ' + error.message);
        });
    }

    // ===== حذف خدمة لحظية =====
    function removeCustomServiceEdit(id) {
        if (!confirm('هل أنت متأكد من حذف هذه الخدمة الإضافية؟')) return;

        fetch(`/admin/custom-services/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = document.getElementById(`service-${id}`);
                if (item) item.remove();

                const container = document.getElementById('customServicesList');
                if (container.children.length === 0) {
                    container.innerHTML = '<div class="no-services-msg">لا توجد خدمات إضافية لحظية</div>';
                }

                updateCustomServicesTotalEdit();
                showToast('✅ تم حذف الخدمة');
            }
        })
        .catch(error => { 
            console.error('Error:', error); 
            showToast('❌ حدث خطأ في حذف الخدمة'); 
        });
    }

    // ===== تفعيل وضع التعديل =====
    function enableEdit(id) {
        document.querySelector(`.service-display-${id}`).style.display = 'none';
        document.querySelector(`.service-edit-${id}`).style.display = 'flex';
        document.querySelector(`#service-${id}`).classList.add('editing');
        
        document.querySelector(`#service-${id} .btn-edit-service`).style.display = 'none';
        document.querySelector(`#service-${id} .btn-save-service`).style.display = 'inline-block';
        document.querySelector(`#service-${id} .btn-cancel-edit-service`).style.display = 'inline-block';
    }

    // ===== إلغاء التعديل =====
    function cancelEdit(id) {
        document.querySelector(`.service-display-${id}`).style.display = 'flex';
        document.querySelector(`.service-edit-${id}`).style.display = 'none';
        document.querySelector(`#service-${id}`).classList.remove('editing');
        
        document.querySelector(`#service-${id} .btn-edit-service`).style.display = 'inline-block';
        document.querySelector(`#service-${id} .btn-save-service`).style.display = 'none';
        document.querySelector(`#service-${id} .btn-cancel-edit-service`).style.display = 'none';
        
        const nameInput = document.querySelector(`.edit-name-${id}`);
        const priceInput = document.querySelector(`.edit-price-${id}`);
        const displayName = document.querySelector(`.service-name-${id}`);
        const displayPrice = document.querySelector(`.service-price-${id}`);
        
        nameInput.value = displayName.textContent.trim();
        priceInput.value = parseFloat(displayPrice.textContent.replace(' ج.م', '').trim()) || 0;
    }

    // ===== حفظ التعديل =====
    function saveEdit(id) {
        const nameInput = document.querySelector(`.edit-name-${id}`);
        const priceInput = document.querySelector(`.edit-price-${id}`);
        const newName = nameInput.value.trim();
        const newPrice = parseFloat(priceInput.value) || 0;

        if (!newName) { showToast('⚠️ يرجى إدخال اسم الخدمة'); return; }
        if (newPrice <= 0) { showToast('⚠️ يرجى إدخال سعر صحيح'); return; }

        // ✅ تحديث الواجهة مؤقتاً
        const displayName = document.querySelector(`.service-name-${id}`);
        const displayPrice = document.querySelector(`.service-price-${id}`);
        
        displayName.textContent = newName;
        displayPrice.textContent = newPrice.toFixed(2) + ' ج.م';
        
        const item = document.getElementById(`service-${id}`);
        item.dataset.price = newPrice;

        // ✅ إرسال طلب التحديث إلى السيرفر
        fetch(`/admin/custom-services/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: newName,
                price: newPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCustomServicesTotalEdit();
                cancelEdit(id);
                showToast('✅ تم تحديث الخدمة بنجاح');
            } else {
                showToast(data.message || '❌ حدث خطأ في تحديث الخدمة');
                cancelEdit(id);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ حدث خطأ في الاتصال بالخادم');
            cancelEdit(id);
        });
    }

    // ===== حذف الحجز =====
    function confirmDelete(id) {
        if (confirm('⚠️ هل أنت متأكد من حذف هذا الحجز؟ لا يمكن التراجع عن هذا الإجراء!')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/bookings/${id}`;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
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