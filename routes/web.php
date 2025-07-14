<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\YogaController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\AccountUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SpaAdminController;
use App\Http\Controllers\GymAdminController;
use App\Http\Controllers\YogaAdminController;
use App\Http\Controllers\Admin\YogasController;
use App\Http\Controllers\Admin\GymsController;
use App\Http\Controllers\Admin\GymsDetailController;
use App\Http\Controllers\Admin\SpaServicesController;
use App\Http\Controllers\Admin\SpaDetailController;
use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VouchersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatNotificationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\YogaDetailController;
use App\Http\Controllers\Admin\YogaServiceController;
use App\Http\Controllers\Admin\GymServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\WelcomeEmail;
use App\Mail\SpaBookingSuccessMail;
use App\Mail\YogaBookingSuccessMail;
use App\Mail\GymBookingSuccessMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================================
// PUBLIC ROUTES (Accessible without authentication)
// ============================================================================

// Dashboard - accessible to everyone
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Public pages
Route::get('/contact', function () {
    return view('fitur.contact');
})->name('contact');

Route::get('/aboutUs', function () {
    return view('fitur.aboutUs');
})->name('aboutus');

Route::get('/service', function () {
    return view('fitur.service');
})->name('service');

// Public Spa Routes (for browsing without booking)
Route::get('/spa', [SpaController::class, 'index'])->name('spa.index');
Route::get('/spa/{id_spa}', [SpaController::class, 'show'])->name('spa.show');
Route::get('/spa/{id_spa}/details', [SpaController::class, 'details'])->name('spa.details');
Route::get('/spa/detail/{id_spa}', [SpaController::class, 'detail'])->name('spa.detail');

// Public Yoga Routes (for browsing without booking)
Route::get('/yoga', [YogaController::class, 'index'])->name('yoga.index');
Route::get('/yoga/{id_yoga}', [YogaController::class, 'detail'])->name('yoga.detail');

// Public Gym Routes (for browsing without booking)
Route::get('/gym', [GymController::class, 'index'])->name('gym.index');
Route::get('/gym/{id_gym}', [GymController::class, 'detail'])->name('gym.detail');

// Public Event Routes (for browsing without booking)
Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/events/search', [EventController::class, 'search'])->name('search.events');
Route::get('/detailEvent', function () {
    return view('fitur.detailEvent');
});

// Public Voucher Route (browsing only, claiming requires auth)
Route::get('/voucher', [VouchersController::class, 'index'])->name('voucher');

// API Routes for public data
Route::prefix('api')->group(function () {
    Route::get('/spa-service/{serviceId}', [SpaController::class, 'getServiceDetails']);
    Route::get('/spa/{spaId}/services', [SpaController::class, 'getSpaServices']);
    // Route::get('/spa/{spaId}/details', [SpasController::class, 'getSpaDetailsJson']); // Temporary disabled
    // Public API endpoints (no authentication required)
    Route::get('/gym/{gymId}/services', [BookingController::class, 'getGymServices'])->name('gym.services');
});

// Social Login Routes
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.login')->middleware('guest');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback')->middleware('guest');
Route::get('auth/google', [SocialAuthController::class, 'redirectToProvider'])->name('auth.google')->middleware('guest');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleProviderCallback'])->middleware('guest');

// Socialite authentication routes
Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback']);

// Language change
Route::post('/change-language', [LanguageController::class, 'changeLanguage']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ============================================================================
// AUTHENTICATED USER ROUTES
// ============================================================================

Route::middleware(['auth'])->group(function () {

    // User-facing voucher route
    Route::get('/voucher', [VouchersController::class, 'index'])->name('voucher');

    // Weather
    Route::get('/weather', [WeatherController::class, 'getWeather']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);

    // Comprehensive Spa Booking Routes (REQUIRES AUTH)
    Route::prefix('spa')->name('spa.')->group(function () {
        Route::post('/booking', [BookingController::class, 'book'])->name('booking.store');
        Route::get('/booking/{bookingCode}', [BookingController::class, 'getBooking'])->name('booking.show');
        Route::get('/booking/{bookingCode}/payment', [BookingController::class, 'payment'])->name('booking.payment');
        Route::post('/booking/{bookingCode}/cancel', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
    });

    // API Routes for Spa Booking (REQUIRES AUTH)
    Route::prefix('api')->group(function () {
        Route::post('/spa-booking', [BookingController::class, 'book']);
        Route::get('/spa-booking/{bookingCode}/status', [BookingController::class, 'getBooking']);
        Route::post('/create-spa-payment', [BookingController::class, 'createSpaPayment']);
    });

    // BOOKING ROUTES (REQUIRES AUTH)
    Route::post('/yoga/booking', [BookingController::class, 'book']);
    Route::post('/yoga', [YogaController::class, 'store'])->name('yoga.store');
    Route::post('/gym', [GymController::class, 'store'])->name('gym.store');
    Route::patch('/admin/gyms/{id_gym}/toggle-status', [GymAdminController::class, 'toggleStatus'])->name('admin.gyms.toggle-status');
    Route::post('/gym/booking', [BookingController::class, 'book'])->name('gym.booking.process');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update.email');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.update.image');

    // Chat routes for users
    Route::get('/chat/conversation', [ChatController::class, 'getOrCreateConversation']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::get('/chat/check-admin-status', [ChatController::class, 'checkAdminActivityStatus']);

    // Payment routes
    Route::post('/api/save-payment', [PaymentController::class, 'savePayment']);
    Route::post('/midtrans/callback', [BookingController::class, 'handleMidtransCallback']);

    // Universal Booking Routes (Unified booking system)
    Route::get('/universal-booking', function () {
        return view('universal-booking');
    })->name('universal.booking.form');

    // Universal Booking API
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/entities', [BookingController::class, 'getEntities'])->name('entities');
        Route::get('/services', [BookingController::class, 'getServices'])->name('services');
        Route::get('/{bookingCode}', [BookingController::class, 'getBooking'])->name('show');
        Route::post('/{bookingCode}/cancel', [BookingController::class, 'cancelBooking'])->name('cancel');
    });
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'Adminhomepage'])->name('dashboard');
    Route::get('/website-usage-data', [DashboardController::class, 'getWebsiteUsageData']);

    // Voucher management
    Route::resource('vouchers', VoucherController::class);
    Route::post('/apply-voucher', [VoucherController::class, 'apply'])->name('apply.voucher');

    // Account management
    Route::get('/account/create', [AdminController::class, 'create'])->name('account.create');
    Route::post('/account', [AdminController::class, 'store'])->name('account.store');
    Route::get('/account/{user}/edit', [AdminController::class, 'edit'])->name('account.edit');
    Route::put('/account/{user}', [AdminController::class, 'update'])->name('account.update');
    Route::delete('/account/{user}', [AdminController::class, 'destroy'])->name('account.destroy');
    Route::get('/accountuser', [AccountUserController::class, 'index'])->name('accountuser');

    // Payment management
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::put('/payments/{kode}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');

    // ============================================================================
    // COMPREHENSIVE SPA MANAGEMENT - RESTORED
    // ============================================================================

    // Main Spa Management
    Route::resource('spas', SpaAdminController::class, ['parameters' => ['spas' => 'id_spa']]);
    Route::get('/formspa', [SpaAdminController::class, 'create'])->name('formspa');

    // FIXED: Spa Services Management - Nested routes
    Route::prefix('spas/{spaId}')->name('spas.')->group(function () {
        Route::get('/services', [SpaServicesController::class, 'index'])->name('services.index');
        Route::get('/services/create', [SpaServicesController::class, 'create'])->name('services.create');
        Route::post('/services', [SpaServicesController::class, 'store'])->name('services.store');
        Route::get('/services/{serviceId}', [SpaServicesController::class, 'show'])->name('services.show');
        Route::get('/services/{serviceId}/edit', [SpaServicesController::class, 'edit'])->name('services.edit');
        Route::put('/services/{serviceId}', [SpaServicesController::class, 'update'])->name('services.update');
        Route::delete('/services/{serviceId}', [SpaServicesController::class, 'destroy'])->name('services.destroy');
        Route::post('/services/{serviceId}/toggle-status', [SpaServicesController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::get('/bookings', [BookingsController::class, 'spaBookingsBySpa'])->name('bookings');
    });

    // Spa Details Management
    Route::prefix('spa-details')->name('spa-details.')->group(function () {
        Route::get('/', [SpaDetailController::class, 'index'])->name('index');
        Route::get('/{id}', [SpaDetailController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SpaDetailController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SpaDetailController::class, 'update'])->name('update');
        Route::get('/{id}/preview', [SpaDetailController::class, 'preview'])->name('preview');
        Route::delete('/{id}', [SpaDetailController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-operation', [SpaDetailController::class, 'bulkOperation'])->name('bulk-operation');
        Route::delete('/{id}/gallery/{imageIndex}', [SpaDetailController::class, 'removeGalleryImage'])->name('remove-gallery-image');
    });

    // Spa Services Management (Global)
    Route::prefix('spa-services')->name('spa-services.')->group(function () {
        Route::get('/', [SpaServicesController::class, 'index'])->name('index');
        Route::get('/create', [SpaServicesController::class, 'create'])->name('create');
        Route::post('/', [SpaServicesController::class, 'store'])->name('store');
        Route::get('/{service}', [SpaServicesController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [SpaServicesController::class, 'edit'])->name('edit');
        Route::put('/{service}', [SpaServicesController::class, 'update'])->name('update');
        Route::delete('/{service}', [SpaServicesController::class, 'destroy'])->name('destroy');
        Route::post('/{service}/toggle-status', [SpaServicesController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-update', [SpaServicesController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/spa/{spaId}/services', [SpaServicesController::class, 'getServicesBySpa'])->name('by-spa');
    });

    // Spa Bookings Management
    Route::prefix('spa-bookings')->name('spa-bookings.')->group(function () {
        Route::get('/', [BookingsController::class, 'spaBookings'])->name('index');
        Route::get('/{booking}', [BookingsController::class, 'showSpaBooking'])->name('show');
        Route::patch('/{booking}/status', [BookingsController::class, 'updateSpaBookingStatus'])->name('update-status');
        Route::patch('/{booking}/payment-status', [BookingsController::class, 'updateSpaPaymentStatus'])->name('update-payment-status');
        Route::delete('/{booking}', [BookingsController::class, 'destroySpaBooking'])->name('destroy');
        Route::post('/bulk-operation', [BookingsController::class, 'bulkOperation'])->name('bulk-operation');
        Route::get('/export', [BookingsController::class, 'exportBookings'])->name('export');
    });

    // Spa Analytics & Reports
    // Temporary disabled - SPA Analytics routes
    // Route::prefix('spa-analytics')->name('spa-analytics.')->group(function () {
    //     Route::get('/', [SpasController::class, 'analytics'])->name('index');
    //     Route::get('/revenue', [SpasController::class, 'revenueReport'])->name('revenue');
    //     Route::get('/bookings', [SpasController::class, 'bookingReport'])->name('bookings');
    //     Route::get('/services', [SpasController::class, 'serviceReport'])->name('services');
    //     Route::get('/export', [SpasController::class, 'exportReport'])->name('export');
    // });

    // ============================================================================
    // OTHER EXISTING ROUTES
    // ============================================================================

    // Yoga management
    Route::resource('yogas', YogaAdminController::class, ['parameters' => ['yogas' => 'id_yoga']]);
    Route::get('/formyoga', [YogaAdminController::class, 'create'])->name('formyoga');
    Route::post('/yoga', [YogaAdminController::class, 'store'])->name('yoga.store');

    // Yoga Detail Management
    Route::prefix('yoga-details')->name('yoga-details.')->group(function () {
        Route::get('/', [YogaDetailController::class, 'index'])->name('index');
        Route::get('/{id}', [YogaDetailController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [YogaDetailController::class, 'edit'])->name('edit');
        Route::put('/{id}', [YogaDetailController::class, 'update'])->name('update');
        Route::get('/{id}/preview', [YogaDetailController::class, 'preview'])->name('preview');
    });

    // Yoga Service Management
    Route::prefix('yoga-services')->name('yoga-services.')->group(function () {
        Route::get('/', [YogaServiceController::class, 'index'])->name('index');
        Route::get('/create', [YogaServiceController::class, 'create'])->name('create');
        Route::post('/', [YogaServiceController::class, 'store'])->name('store');
        Route::get('/{id}', [YogaServiceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [YogaServiceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [YogaServiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [YogaServiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [YogaServiceController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Gym management
    Route::resource('gyms', GymAdminController::class, ['parameters' => ['gyms' => 'id_gym']]);
    Route::get('/formgym', [GymAdminController::class, 'create'])->name('formgym');
    Route::post('/gym', [GymAdminController::class, 'store'])->name('gym.store');
    Route::patch('/admin/gyms/{id_gym}/toggle-status', [GymAdminController::class, 'toggleStatus'])->name('admin.gyms.toggle-status');

    // Gym Detail Management
    Route::prefix('gym-details')->name('gym-details.')->group(function () {
        Route::get('/', [GymsDetailController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [GymsDetailController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GymsDetailController::class, 'update'])->name('update');
        Route::get('/{id}/preview', [GymsDetailController::class, 'preview'])->name('preview');
    });

    // Gym Services Management
    Route::resource('services', GymServiceController::class);

    // Event management
    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventController::class, 'adminIndex'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{id_event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{id_event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{id_event}', [EventController::class, 'destroy'])->name('destroy');
    });

    // Feedback management
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');

    // Admin Profile
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');

    // Chat management for admins
    Route::get('/chat', function () {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin privileges required.');
        }
        return view('admin.chat.index');
    })->name('chat');

    // Admin chat API routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/conversations', [ChatController::class, 'adminGetConversations'])->name('conversations');
        Route::get('/conversations/{conversationId}', [ChatController::class, 'adminGetMessages'])->name('messages');
        Route::post('/conversations/{conversationId}/send', [ChatController::class, 'adminSendMessage'])->name('send');
        Route::put('/conversations/{conversationId}/close', [ChatController::class, 'adminCloseConversation'])->name('close');
        Route::put('/conversations/{conversationId}/reopen', [ChatController::class, 'adminReopenConversation'])->name('reopen');
        Route::post('/admin-activity', [ChatController::class, 'updateAdminActivityStatus'])->name('activity');
    });

    // Chat notifications
    Route::get('/chat/notifications/unread', [ChatNotificationController::class, 'getUnreadCount']);

    // VPS Email Testing Routes
    Route::get('/email-test', function () {
        return view('admin.email-test');
    })->name('email.test.form');

    Route::post('/email-test/send', function (Request $request) {
        try {
            $type = $request->input('type');
            $email = $request->input('email');

            switch ($type) {
                case 'welcome':
                    $data = [
                        'userName' => 'VPS Test User',
                        'userEmail' => $email,
                        'userPhone' => '+62 812-3456-7890',
                        'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.web.id')
                    ];
                    Mail::to($email)->send(new App\Mail\WelcomeEmail($data));
                    break;

                case 'spa':
                    $data = [
                        'bookingCode' => 'SPA-VPS-' . strtoupper(uniqid()),
                        'customerName' => 'VPS Test Customer',
                        'customerEmail' => $email,
                        'bookingDate' => now()->addDays(1)->format('Y-m-d'),
                        'bookingTime' => '14:00',
                        'duration' => 90,
                        'therapistPreference' => 'Female Therapist',
                        'totalAmount' => '350.000',
                        'status' => 'confirmed',
                        'paymentStatus' => 'paid',
                        'paymentMethod' => 'Bank Transfer',
                        'notes' => 'VPS Email Test - Spa Booking',
                        'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.web.id')
                    ];
                    Mail::to($email)->send(new App\Mail\SpaBookingSuccessMail($data));
                    break;

                case 'yoga':
                    $data = [
                        'bookingCode' => 'YOGA-VPS-' . strtoupper(uniqid()),
                        'customerName' => 'VPS Test Customer',
                        'customerEmail' => $email,
                        'bookingDate' => now()->addDays(1)->format('Y-m-d'),
                        'bookingTime' => '07:00',
                        'classType' => 'Hatha Yoga',
                        'participants' => 1,
                        'totalAmount' => '100.000',
                        'status' => 'confirmed',
                        'paymentStatus' => 'paid',
                        'paymentMethod' => 'Bank Transfer',
                        'specialRequests' => 'VPS Email Test - Yoga Booking',
                        'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.web.id')
                    ];
                    Mail::to($email)->send(new App\Mail\YogaBookingSuccessMail($data));
                    break;

                case 'gym':
                    $data = [
                        'bookingCode' => 'GYM-VPS-' . strtoupper(uniqid()),
                        'customerName' => 'VPS Test Customer',
                        'customerEmail' => $email,
                        'bookingDate' => now()->addDays(1)->format('Y-m-d'),
                        'bookingTime' => '18:00',
                        'duration' => 2,
                        'totalAmount' => '75.000',
                        'status' => 'confirmed',
                        'paymentStatus' => 'paid',
                        'paymentMethod' => 'E-Wallet',
                        'notes' => 'VPS Email Test - Gym Booking',
                        'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.web.id')
                    ];
                    Mail::to($email)->send(new App\Mail\GymBookingSuccessMail($data));
                    break;

                default:
                    return response()->json(['success' => false, 'message' => 'Invalid email type']);
            }

            return response()->json([
                'success' => true,
                'message' => "✅ {$type} email berhasil dikirim ke {$email}!",
                'smtp_host' => env('MAIL_HOST'),
                'from_address' => env('MAIL_FROM_ADDRESS')
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "❌ Gagal mengirim email: " . $e->getMessage()
            ]);
        }
    })->name('email.test.send');
});

// ============================================================================
// TEST AND MISC ROUTES
// ============================================================================

// Chat test route
Route::get('/chat-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Chat system is working correctly',
        'timestamp' => now()->toDateTimeString(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
    ]);
});

// Legacy route
Route::get('/spaadmin', function () {
    return view('spaadmin');
});

// Test route for gym services API
Route::get('/test-gym-api', function () {
    return view('test-gym-api');
});

// Public API endpoints (no authentication required)
Route::get('/gym/{gymId}/services', [BookingController::class, 'getGymServices'])->name('gym.services');

// Include auth routes
require __DIR__ . '/auth.php';

