<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\CustomService;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingReminderNotification; 


class BookingController extends Controller
{
    /**
     * عرض جميع الحجوزات
     */
    public function index()
    {
        try {
            $bookings = Booking::with(['service', 'staff', 'customServices'])
                ->orderBy('created_at', 'desc')
                ->get();

            // ✅ إحصائيات الحجوزات مع حساب الخدمات اللحظية
            $stats = [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'today' => Booking::whereDate('date', today())->count(),
                
                // ✅ إجمالي المبيعات (يشمل الخدمات اللحظية للحجوزات المكتملة)
                'total_income' => $this->calculateTotalIncome(),
                
                // ✅ إجمالي العربون
                'total_deposit' => (float) Booking::sum('deposit'),
                
                // ✅ إجمالي الخدمات اللحظية
                'total_custom_services' => (float) CustomService::sum('price'),
                'custom_services_count' => CustomService::count(),
            ];

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $bookings,
                    'stats' => $stats
                ]);
            }

            return view('admin.bookings.index', compact('bookings', 'stats'));

        } catch (\Exception $e) {
            Log::error('خطأ في عرض الحجوزات: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ في تحميل البيانات'
                ], 500);
            }

            return back()->with('error', '❌ حدث خطأ في تحميل الحجوزات');
        }
    }

    /**
     * ✅ حساب إجمالي المبيعات (يشمل الخدمات اللحظية للحجوزات المكتملة)
     */
    private function calculateTotalIncome()
    {
        try {
            $completedBookings = Booking::with('customServices')
                ->where('status', 'completed')
                ->get();

            $total = 0;
            foreach ($completedBookings as $booking) {
                $basePrice = (float) ($booking->total_price ?? 0);
                $customTotal = (float) $booking->customServices->sum('price');
                $total += $basePrice + $customTotal;
            }

            return $total;

        } catch (\Exception $e) {
            Log::error('خطأ في حساب إجمالي المبيعات: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * عرض نموذج إضافة حجز جديد
     */
    public function create()
    {
        try {
            $services = Service::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();
            
            $staff = Staff::where('is_active', true)
                ->orderBy('name_ar', 'asc')
                ->get();

            return view('admin.bookings.create', compact('services', 'staff'));

        } catch (\Exception $e) {
            Log::error('خطأ في عرض نموذج الإضافة: ' . $e->getMessage());
            return back()->with('error', '❌ حدث خطأ في تحميل النموذج');
        }
    }

    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'service_id' => 'required|exists:services,id',
                'staff_id' => 'required|exists:staff,id',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'total_price' => 'required|numeric|min:0',
                'deposit' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string|max:500',
                'status' => 'required|in:pending,confirmed,completed,cancelled'
            ]);

            // ✅ تعيين القيم الافتراضية
            $validated['deposit'] = $validated['deposit'] ?? 0;

            $booking = Booking::create($validated);

            Log::info('تم إنشاء حجز جديد', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id()
            ]);

            if ($booking->status === 'confirmed') {
                $admins = User::where('is_admin', true)->get();
                foreach ($admins as $admin) {
                    $admin->notify(new BookingReminderNotification($booking));
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ تم إضافة الحجز بنجاح',
                    'data' => $booking
                ], 201);
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', '✅ تم إضافة الحجز بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء الحجز: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ في إنشاء الحجز'
                ], 500);
            }

            return back()->with('error', '❌ حدث خطأ في إنشاء الحجز')
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل حجز معين
     */
    public function show(Booking $booking)
    {
        try {
            $booking->load(['service', 'staff', 'customServices', 'additionalServices']);
            return view('admin.bookings.show', compact('booking'));

        } catch (\Exception $e) {
            Log::error('خطأ في عرض تفاصيل الحجز: ' . $e->getMessage());
            return back()->with('error', '❌ حدث خطأ في تحميل تفاصيل الحجز');
        }
    }

    /**
     * ✅ عرض نموذج التعديل الفعلي (بعد التحقق من كلمة المرور)
     * يتم الوصول إليها عبر Route: admin.bookings.edit.form
     */
    public function editForm(Booking $booking)
    {
        // ✅ التحقق من Session (أن المستخدم دخل كلمة المرور صح)
        if (!session()->get('booking_edit_verified_' . $booking->id)) {
            return redirect()->route('admin.bookings.edit', $booking)
                ->with('error', '❌ يجب إدخال كلمة المرور أولاً');
        }

        $services = Service::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        $staff = Staff::where('is_active', true)
            ->orderBy('name_ar', 'asc')
            ->get();
        
        $bookingServices = $booking->additionalServices;
        $customServices = $booking->customServices;
        
        return view('admin.bookings.edit', compact('booking', 'services', 'staff', 'bookingServices', 'customServices'));
    }

    /**
     * ✅ تحديث بيانات الحجز
     */
    public function update(Request $request, Booking $booking)
    {
        try {
            // ✅ التحقق من Session (booking-scoped)
            if (!session()->get('booking_edit_verified_' . $booking->id)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ يجب التحقق من كلمة المرور أولاً'
                    ], 403);
                }
                return redirect()->route('admin.bookings.edit', $booking)
                    ->with('error', '❌ يجب التحقق من كلمة المرور أولاً');
            }

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'service_id' => 'required|exists:services,id',
                'staff_id' => 'required|exists:staff,id',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i:s',
                'total_price' => 'required|numeric|min:0',
                'deposit' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string|max:500',
                'status' => 'required|in:pending,confirmed,completed,cancelled',
                'additional_services' => 'nullable|array',
                'additional_services.*' => 'exists:services,id'
            ]);

            unset($validated['additional_services']);
            $validated['deposit'] = $validated['deposit'] ?? 0;

            // ✅ تحديث الحجز
            $booking->update($validated);

            // ✅ معالجة الخدمات الإضافية (من القائمة)
            if ($request->has('additional_services')) {
                $booking->additionalServices()->delete();
                
                foreach ($request->additional_services as $serviceId) {
                    $service = Service::find($serviceId);
                    if ($service) {
                        $booking->additionalServices()->create([
                            'service_id' => $serviceId,
                            'price' => $service->price ?? 0,
                            'name_ar' => $service->title_ar,
                        ]);
                    }
                }
            }

            Log::info('تم تحديث الحجز', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id()
            ]);

            // ✅ إزالة Session بعد التحديث (إجبار المستخدم على إدخال كلمة المرور مرة أخرى لأي تعديل جديد)
            session()->forget('booking_edit_verified_' . $booking->id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ تم تحديث الحجز بنجاح',
                    'data' => $booking
                ]);
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', '✅ تم تحديث الحجز بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث الحجز: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ في تحديث الحجز'
                ], 500);
            }

            return back()->with('error', '❌ حدث خطأ في تحديث الحجز')
                ->withInput();
        }
    }

    /**
     * حذف الحجز
     */
    public function destroy(Booking $booking)
    {
        try {
            // ✅ حذف الخدمات اللحظية المرتبطة
            $booking->customServices()->delete();
            
            // ✅ حذف الخدمات الإضافية المرتبطة
            $booking->additionalServices()->delete();
            
            // ✅ حذف التذكيرات المرتبطة (إذا وجدت العلاقة)
            if (method_exists($booking, 'reminders')) {
                $booking->reminders()->delete();
            }
            
            $booking->delete();

            Log::info('تم حذف الحجز', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ تم حذف الحجز بنجاح'
                ]);
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', '✅ تم حذف الحجز بنجاح');

        } catch (\Exception $e) {
            Log::error('خطأ في حذف الحجز: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ في حذف الحجز'
                ], 500);
            }

            return back()->with('error', '❌ حدث خطأ في حذف الحجز');
        }
    }

    /**
     * تحديث حالة الحجز (API)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,completed,cancelled'
            ]);

            $oldStatus = $booking->status;
            $booking->update(['status' => $request->status]);

            Log::info('تم تحديث حالة الحجز', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin_id' => Auth::id()
            ]);

            $whatsappData = null;
            if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
                $booking->load(['service', 'staff', 'customServices']);
                $whatsappData = [
                    'message' => $this->buildWhatsAppMessage($booking),
                    'phone' => $this->formatPhoneNumber($booking->phone),
                    'whatsapp_url' => "https://wa.me/" . $this->formatPhoneNumber($booking->phone) . "?text=" . urlencode($this->buildWhatsAppMessage($booking))
                ];
            }

            return response()->json([
                'success' => true,
                'message' => '✅ تم تحديث حالة الحجز بنجاح',
                'status' => $booking->status,
                'whatsapp' => $whatsappData
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث حالة الحجز: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث حالة الحجز'
            ], 500);
        }
    }

    /**
     * جلب حجوزات اليوم
     */
    public function today()
    {
        try {
            $bookings = Booking::with(['service', 'staff', 'customServices'])
                ->whereDate('date', today())
                ->orderBy('time', 'asc')
                ->get();

            return view('admin.bookings.today', compact('bookings'));

        } catch (\Exception $e) {
            Log::error('خطأ في جلب حجوزات اليوم: ' . $e->getMessage());
            return back()->with('error', '❌ حدث خطأ في تحميل حجوزات اليوم');
        }
    }

    /**
     * جلب الحجوزات حسب الفلتر (دعم AJAX)
     */
    public function filter(Request $request)
    {
        try {
            $query = Booking::with(['service', 'staff', 'customServices']);

            // ✅ فلترة حسب الحالة
            if ($request->status && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // ✅ فلترة حسب التاريخ من
            if ($request->date_from) {
                $query->whereDate('date', '>=', $request->date_from);
            }

            // ✅ فلترة حسب التاريخ إلى
            if ($request->date_to) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            // ✅ فلترة حسب البحث (الاسم، الهاتف، البريد)
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('customer_name', 'like', "%{$request->search}%")
                      ->orWhere('phone', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
                });
            }

            $bookings = $query->orderBy('date', 'desc')
                ->orderBy('time', 'asc')
                ->get();

            // ✅ إذا كان الطلب AJAX
            if ($request->ajax()) {
                $stats = [
                    'total' => $bookings->count(),
                    'pending' => $bookings->where('status', 'pending')->count(),
                    'confirmed' => $bookings->where('status', 'confirmed')->count(),
                    'completed' => $bookings->where('status', 'completed')->count(),
                    'cancelled' => $bookings->where('status', 'cancelled')->count(),
                ];

                return response()->json([
                    'success' => true,
                    'data' => $bookings,
                    'stats' => $stats,
                    'count' => $bookings->count()
                ]);
            }

            return view('admin.bookings.index', compact('bookings'));

        } catch (\Exception $e) {
            Log::error('خطأ في فلترة الحجوزات: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ في فلترة البيانات'
                ], 500);
            }

            return back()->with('error', '❌ حدث خطأ في فلترة الحجوزات');
        }
    }

    public function getWhatsAppMessage(Booking $booking)
    {
        try {
            // تحميل العلاقات المطلوبة
            $booking->load(['service', 'staff', 'customServices']);

            // ✅ تنسيق رقم الهاتف (إزالة الأصفار الزائدة)
            $phone = $this->formatPhoneNumber($booking->phone);

            // ✅ بناء نص الرسالة
            $message = $this->buildWhatsAppMessage($booking);

            // ✅ ترميز الرسالة للـ URL
            $encodedMessage = urlencode($message);

            // ✅ إنشاء رابط WhatsApp
            $whatsappUrl = "https://wa.me/{$phone}?text={$encodedMessage}";

            return response()->json([
                'success' => true,
                'whatsapp_url' => $whatsappUrl,
                'message' => $message,
                'phone' => $phone
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء رسالة WhatsApp: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الرسالة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ تنسيق رقم الهاتف
     */
    private function formatPhoneNumber($phone)
    {
        // إزالة كل ما ليس أرقام
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // إذا كان الرقم يبدأ بـ 0، قم بإزالته
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }

        // إذا كان الرقم لا يبدأ بـ كود الدولة، أضف كود مصر (+20)
        if (strlen($phone) === 10 || strlen($phone) === 11) {
            $phone = '20' . $phone;
        }

        return $phone;
    }

    /**
     * ✅ بناء نص رسالة WhatsApp
     */
    private function buildWhatsAppMessage(Booking $booking)
    {
        // حساب الإجماليات
        $totalPrice = (float) ($booking->total_price ?? 0);
        $deposit = (float) ($booking->deposit ?? 0);
        $remaining = max(0, $totalPrice - $deposit);
        
        $customServicesTotal = (float) $booking->customServices->sum('price');
        $grandTotal = $totalPrice + $customServicesTotal;
        $totalRemaining = max(0, $grandTotal - $deposit);

        // ✅ بناء الرسالة
        $message = "🕌 *مرحباً عزيزتي {$booking->customer_name}*\n\n";
        $message .= "📋 *تم تأكيد حجزك في Aur Beauty*\n";
        // $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "📅 *التاريخ:* {$booking->date->format('Y-m-d')}\n";
        $message .= "⏰ *الوقت:* {$booking->time}\n";
        $message .= "💇‍♀️ *الخدمة:* {$booking->service->title_ar}\n";
        $message .= "👤 *الموظف:* {$booking->staff->name_ar}\n\n";
        
        // الأسعار
        $message .= "💰 *تفاصيل الأسعار:*\n";
        // $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "السعر الأساسي: {$totalPrice} ج.م\n";
        
        if ($customServicesTotal > 0) {
            $message .= "✨ *الخدمات الإضافية اللحظية:*\n";
            foreach ($booking->customServices as $service) {
                $message .= "  • {$service->name}: {$service->price} ج.م\n";
            }
            $message .= "إجمالي الخدمات الإضافية: {$customServicesTotal} ج.م\n";
            // $message .= "────────────────────\n";
            $message .= "السعر النهائي: {$grandTotal} ج.م\n";
        }
        
        $message .= "العربون المدفوع: {$deposit} ج.م\n";
        $message .= "المتبقي: {$totalRemaining} ج.م\n\n";
        
        $message .= "📍 *العنوان:* [عنوان الصالون]\n";
        $message .= "📱 *للتواصل:* [رقم التواصل]\n\n";
        
        // $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "✨ *نحن في انتظارك!*\n";
        $message .= "🕌 *مع تحيات فريق Aur Beauty*";

        return $message;
    }
}