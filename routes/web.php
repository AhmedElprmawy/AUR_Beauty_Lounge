<?php
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BridalController;
use App\Http\Controllers\Admin\BridalPackageController;
use App\Http\Controllers\Admin\TransformationController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CustomServiceController;
use App\Http\Controllers\Admin\ReminderController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingEditAccessController;


// ============================================
// FRONTEND ROUTES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

// ============================================
// LOGIN ROUTES
// ============================================
Route::get('/login', [DashboardController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [DashboardController::class, 'login'])->middleware('guest')->withoutMiddleware([VerifyCsrfToken::class])->name('login.submit');
Route::post('/logout', [DashboardController::class, 'logout'])->middleware('auth')->withoutMiddleware([VerifyCsrfToken::class])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::get('/forgot-password', [AuthController::class, 'showPasswordResetLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email')->withoutMiddleware([VerifyCsrfToken::class]);
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->withoutMiddleware([VerifyCsrfToken::class]);
});

Route::middleware('auth')->group(function () {
    Route::get('/confirm-password', [AuthController::class, 'showConfirmPasswordForm'])->name('password.confirm');
    Route::post('/confirm-password', [AuthController::class, 'confirmPassword'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::get('/verify-email', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update.current')->withoutMiddleware([VerifyCsrfToken::class]);
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    
    // ===== Dashboard & Logout =====
    Route::post('/logout', [DashboardController::class, 'logout'])->withoutMiddleware([VerifyCsrfToken::class])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // REMINDERS
    // ============================================
    Route::post('reminders/send', [ReminderController::class, 'send'])->name('reminders.send');
    Route::post('reminders/retry', [ReminderController::class, 'retry'])->name('reminders.retry');
    
    // داخل مجموعة Admin Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('unread', [NotificationController::class, 'unread'])->name('unread');
        Route::get('all', [NotificationController::class, 'all'])->name('all');
        Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('{id}', [NotificationController::class, 'delete'])->name('delete');
    });

    // ============================================
    // BOOKINGS (Admin)
    // ============================================
    // ✅ Routes المخصصة (يجب أن تأتي قبل Routes العامة)
    Route::get('bookings/filter', [AdminBookingController::class, 'filter'])->name('bookings.filter');
    Route::get('bookings/today', [AdminBookingController::class, 'today'])->name('bookings.today');
    Route::get('bookings/search', [CustomServiceController::class, 'search'])->name('bookings.search');
    Route::post('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::get('bookings/{booking}/whatsapp-message', [AdminBookingController::class, 'getWhatsAppMessage'])->name('bookings.whatsapp-message');
    
    // ✅ Routes الأساسية (CRUD)
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::put('bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // ✅ Routes للتعديل مع كلمة المرور
    Route::get('bookings/{booking}/edit-password', [BookingEditAccessController::class, 'show'])->name('bookings.edit');
    Route::post('bookings/{booking}/verify-password', [BookingEditAccessController::class, 'verify'])->name('bookings.verify.password');
    Route::get('bookings/{booking}/edit-form', [AdminBookingController::class, 'editForm'])->name('bookings.edit.form');

    // ============================================
    // CUSTOM SERVICES (الخدمات اللحظية)
    // ============================================
    Route::get('bookings/{booking}/custom-services', [CustomServiceController::class, 'index'])->name('bookings.custom-services.index');
    Route::get('bookings/{booking}/custom-services/total', [CustomServiceController::class, 'total'])->name('bookings.custom-services.total');
    Route::post('custom-services', [CustomServiceController::class, 'store'])->name('custom-services.store');
    Route::get('custom-services/{id}', [CustomServiceController::class, 'show'])->name('custom-services.show');
    Route::put('custom-services/{id}', [CustomServiceController::class, 'update'])->name('custom-services.update');
    Route::delete('custom-services/{id}', [CustomServiceController::class, 'destroy'])->name('custom-services.destroy');

    // ============================================
    // SERVICES (الخدمات)
    // ============================================
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::post('services/{service}/toggle', [ServiceController::class, 'toggleStatus'])->name('services.toggle');

    // ============================================
    // BRIDAL (العروس)
    // ============================================
    Route::resource('bridal', BridalController::class)->except(['show']);
    Route::post('bridal/{bridal}/toggle', [BridalController::class, 'toggleStatus'])->name('bridal.toggle');

    // ============================================
    // PACKAGES (باقات العروس)
    // ============================================
    Route::resource('packages', BridalPackageController::class)->except(['show']);

    // ============================================
    // TRANSFORMATIONS (التحولات)
    // ============================================
    Route::resource('transformations', TransformationController::class)->except(['show']);

    // ============================================
    // GALLERY (المعرض)
    // ============================================
    Route::resource('gallery', GalleryController::class)->except(['show']);

    // ============================================
    // STAFF (الموظفين)
    // ============================================
    Route::resource('staff', StaffController::class)->except(['show']);

    // ============================================
    // TESTIMONIALS (الشهادات)
    // ============================================
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class)->except(['show']);
    Route::post('videos/{video}/toggle', [\App\Http\Controllers\Admin\VideoController::class, 'toggleStatus'])->name('videos.toggle');
});