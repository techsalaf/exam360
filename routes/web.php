<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\LanguageController; 
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ExamController as UserExamController;
use App\Http\Controllers\User\ResultController;      
use App\Http\Controllers\User\ProfileController;    
use App\Http\Controllers\User\SettingsController;   
use App\Http\Controllers\User\TicketController; 
use App\Http\Controllers\User\PaymentHistoryController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Middleware\CheckInstallation;
use App\Http\Middleware\RedirectIfInstalled;

Route::prefix('install')->name('install.')->middleware(RedirectIfInstalled::class)->group(function () {
    Route::get('/', [InstallerController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallerController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [InstallerController::class, 'permissions'])->name('permissions');
    Route::match(['get', 'post'], '/database', [InstallerController::class, 'database'])->name('database');
    Route::match(['get', 'post'], '/application', [InstallerController::class, 'application'])->name('application');
    Route::get('/finish', [InstallerController::class, 'finish'])->name('finish');
});

Route::middleware(['web', CheckInstallation::class])->group(function () {

    require __DIR__.'/admin.php';

    Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
    Route::get('/exams', [HomeController::class, 'exams'])->name('exams.list');
    Route::get('lang/{code}', [LanguageController::class, 'switch'])->name('lang.switch');

    Route::get('/home', function () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->id === 1 || $user->hasAnyRole(['Super Admin', 'Instructor', 'Admin'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }
        return redirect()->route('frontend.home');
    })->name('home');

    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/cart', 'cart')->name('checkout.cart');
        Route::get('/checkout/add/{id}', 'addToCart')->name('checkout.add');
        Route::get('/checkout/remove/{id}', 'remove')->name('checkout.remove');
        Route::get('/checkout/details', 'details')->name('checkout.details');
        Route::post('/checkout/details', 'storeDetails')->name('checkout.details.store');
        Route::get('/checkout/payment', 'payment')->name('checkout.payment');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/checkout/success', 'success')->name('checkout.success');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
    });

    Route::controller(SocialLoginController::class)->group(function () {
        Route::get('auth/{provider}', 'redirect')->name('social.login');
        Route::get('auth/{provider}/callback', 'callback')->name('social.callback');
    });

    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'sendResetLinkEmail')->name('password.email');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('password/reset', 'reset')->name('password.update');
    });

    Route::get('password/confirm/sent', function () { return view('auth.passwords.confirm-sent'); })->name('password.confirm.sent');
    Route::get('password/confirm/reset', function () { return view('auth.passwords.confirm-reset'); })->name('password.confirm.reset');

    Route::controller(VerificationController::class)->group(function () {
        Route::get('email/verify', 'show')->name('verification.notice');
        Route::get('email/verify/{id}/{hash}', 'verify')->name('verification.verify');
        Route::post('email/resend', 'resend')->name('verification.resend');
    });

    Route::middleware(['auth', 'verified.system'])->group(function () {
        
        Route::get('/stop-impersonation', [UserController::class, 'stopImpersonation'])->name('stop.impersonation');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index')->name('user.notifications.index');
            Route::get('/notifications/read-all', 'markAllRead')->name('user.notifications.markAllRead');
            Route::delete('/notifications/{id}', 'destroy')->name('user.notifications.delete');
        });

        Route::get('/my-exams', [UserExamController::class, 'myExams'])->name('my.exams');
        Route::prefix('exam/{exam}')->group(function () {
            Route::get('/participate', [UserExamController::class, 'participate'])->name('exam.participate');
            Route::post('/start', [UserExamController::class, 'startExam'])->name('exam.start');
            Route::post('/save-answer', [UserExamController::class, 'saveAnswer'])->name('exam.save-answer');
            Route::post('/submit', [UserExamController::class, 'submitExam'])->name('exam.submit');
        });

        Route::controller(ResultController::class)->group(function () {
            Route::get('/results', 'index')->name('user.results');
            Route::get('/results/{id}', 'show')->name('user.results.show');
            Route::get('/certificates', 'certificates')->name('user.certificates');
            Route::get('/certificates/{id}/download', 'downloadCertificate')->name('user.certificate.download');
        });

        Route::get('/transactions', [PaymentHistoryController::class, 'index'])->name('user.transactions');

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('user.profile');
            Route::put('/profile', 'update')->name('user.profile.update');
        });

        Route::controller(SettingsController::class)->group(function () {
            Route::get('/settings', 'index')->name('user.settings');
            Route::put('/settings', 'update')->name('user.settings.update');
        });
        
        Route::prefix('support-tickets')->controller(TicketController::class)->group(function () {
            Route::get('/', 'index')->name('user.tickets');
            Route::post('/', 'store')->name('user.tickets.store');
            Route::get('/{id}', 'show')->name('user.tickets.show');
            Route::post('/{id}/reply', 'reply')->name('user.tickets.reply');
            Route::post('/{id}/close', 'close')->name('user.tickets.close');
        });
    });

    Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');

});