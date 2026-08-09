<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Service;
use App\Models\BridalPackage;
use App\Models\Gallery;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Models\Transformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    public function index()
    {
        // إحصائيات رئيسية
        $stats = [
            'services' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'packages' => BridalPackage::count(),
            'popular_packages' => BridalPackage::where('is_popular', true)->count(),
            'gallery' => Gallery::count(),
            'staff' => Staff::count(),
            'active_staff' => Staff::where('is_active', true)->count(),
            'testimonials' => Testimonial::count(),
            'transformations' => Transformation::count(),
        ];

        // عدد العناصر للـ Sidebar
        $servicesCount = Service::count();
        $packagesCount = BridalPackage::count();
        $galleryCount = Gallery::count();
        $staffCount = Staff::count();
        $testimonialsCount = Testimonial::count();

        // آخر التحديثات (مثال)
        $recentUpdates = collect([
            (object) ['message' => 'تم إضافة خدمة جديدة "علاج الشعر"', 'created_at' => now()->subMinutes(5), 'is_new' => true],
            (object) ['message' => 'تم تحديث باقة العرائس الذهبية', 'created_at' => now()->subHours(2), 'is_new' => false],
            (object) ['message' => 'تم إضافة موظف جديد "نورا محمود"', 'created_at' => now()->subDays(1), 'is_new' => false],
            (object) ['message' => 'تم إضافة 3 صور جديدة للمعرض', 'created_at' => now()->subDays(2), 'is_new' => false],
            (object) ['message' => 'تم تحديث إعدادات الموقع', 'created_at' => now()->subDays(3), 'is_new' => false],
        ]);

        return view('admin.dashboard', compact(
            'stats',
            'servicesCount',
            'packagesCount',
            'galleryCount',
            'staffCount',
            'testimonialsCount',
            'recentUpdates'
        ));
    }
}