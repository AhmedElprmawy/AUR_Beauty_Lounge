<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>🔐 التحقق من الصلاحية - تعديل الحجز</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background: #f5f5f5;
        }
        .status-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .status-confirmed { background: rgba(0, 188, 212, 0.2); color: #00bcd4; }
        .status-completed { background: rgba(76, 175, 80, 0.2); color: #4caf50; }
        .status-cancelled { background: rgba(255, 77, 77, 0.2); color: #ff4d4d; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8 border border-gray-100">

        <div class="text-center mb-6">
            <span class="text-6xl block mb-3">🔐</span>
            <h1 class="text-2xl font-bold text-gray-800">
                التحقق من الصلاحية
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                أدخل كلمة المرور لتعديل هذا الحجز
            </p>
            <p class="text-gray-400 text-xs mt-1">
                ⚠️ يجب إدخال كلمة المرور في كل مرة لتعديل أي حجز
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-4">
                <div class="flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded-lg mb-4">
                <div class="flex items-center gap-2">
                    <span>❌</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded-lg mb-4">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <span>⚠️</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- معلومات الحجز --}}
        @if(isset($booking) && $booking)
        <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-gray-500">رقم الحجز</div>
                <div class="font-semibold text-gray-800 text-left">#{{ $booking->id }}</div>
                
                <div class="text-gray-500">العميل</div>
                <div class="font-semibold text-gray-800 text-left">{{ $booking->customer_name }}</div>
                
                <div class="text-gray-500">الخدمة</div>
                <div class="font-semibold text-gray-800 text-left">{{ $booking->service->title_ar ?? '—' }}</div>
                
                <div class="text-gray-500">التاريخ</div>
                <div class="font-semibold text-gray-800 text-left">{{ $booking->date->format('Y-m-d') }}</div>
                
                <div class="text-gray-500">الوقت</div>
                <div class="font-semibold text-gray-800 text-left">{{ $booking->time }}</div>
                
                <div class="text-gray-500">الحالة</div>
                <div class="text-left">
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
        </div>
        @endif

        {{-- نموذج التحقق --}}
        <form method="POST" action="{{ route('admin.bookings.verify.password', $booking ?? 0) }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 font-semibold text-gray-700 text-sm">
                    🔑 كلمة المرور
                </label>
                <input
                    type="password"
                    name="password"
                    class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-center text-lg tracking-widest focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition bg-gray-50"
                    placeholder="••••••••"
                    required
                    autofocus
                >
                <p class="text-xs text-gray-400 mt-2 text-center">
                    💡 كلمة مرور حساب المدير
                </p>
            </div>

            <button
                type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 rounded-xl transition duration-300 transform hover:scale-[1.02] text-lg"
            >
                ✅ تحقق
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-yellow-600 text-sm transition">
                ← العودة إلى قائمة الحجوزات
            </a>
        </div>

        {{-- تنبيه بأنه سيطلب كلمة المرور في كل مرة --}}
        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
            <span class="text-xs text-gray-400">
                ⚠️ سيتم طلب كلمة المرور في كل مرة تقوم فيها بتعديل أي حجز
            </span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            if (passwordInput) {
                setTimeout(() => passwordInput.focus(), 100);
            }
        });

        document.querySelector('form')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement?.tagName === 'INPUT') {
                    e.preventDefault();
                    this.submit();
                }
            }
        });
    </script>

</body>

</html>