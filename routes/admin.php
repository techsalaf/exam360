<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\LiveExamController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamInstructionController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserNotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SystemInfoController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\CMS\PageController;
use App\Http\Controllers\Admin\CMS\MenuController;
use App\Http\Controllers\Admin\CMS\HomepageController;
use App\Http\Controllers\Admin\CMS\FooterController;
use App\Http\Controllers\Admin\CMS\HeaderController;
use App\Http\Controllers\Admin\CMS\TestimonialController;
use App\Http\Controllers\Admin\Settings\SystemGroupController;
use App\Http\Controllers\Admin\Settings\BrandingController;
use App\Http\Controllers\Admin\Settings\NotificationSettingsController;
use App\Http\Controllers\Admin\Settings\PaymentSettingsController;
use App\Http\Controllers\Admin\Settings\LocalizationSettingsController;
use App\Http\Controllers\Admin\Settings\SeoSettingsController;
use App\Http\Controllers\Admin\Settings\AutomationSettingsController;
use App\Http\Controllers\Admin\Settings\SecuritySettingsController;

Route::middleware(['auth', 'verified.system', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('lang/{code}', [LocalizationSettingsController::class, 'switchLanguage'])->name('lang.switch');

    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/read-all', 'markAllRead')->name('read.all');
        Route::get('/{id}/read', 'markAsRead')->name('read');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('cms')->name('cms.')->group(function () {
        Route::controller(PageController::class)->prefix('pages')->name('pages.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{page}/edit', 'edit')->name('edit');
            Route::put('/{page}', 'update')->name('update');
            Route::delete('/{page}', 'destroy')->name('destroy');
        });

        Route::controller(MenuController::class)->prefix('menus')->name('menus.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{menu}', 'destroy')->name('destroy');
        });

        Route::controller(HeaderController::class)->prefix('header')->name('header.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/update', 'update')->name('update');
        });
        
        Route::controller(HomepageController::class)->prefix('homepage')->group(function () {
            Route::get('/', 'index')->name('homepage.index');
            Route::get('/designs', 'designs')->name('homepage.designs');
            Route::post('/set-design', 'setDesign')->name('homepage.set-design');
            Route::post('/update-thumbnail', 'updateThumbnail')->name('homepage.update-thumbnail');
            Route::post('/update', 'update')->name('homepage.update');
        });

        Route::controller(FooterController::class)->prefix('footer')->name('footer.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/update', 'update')->name('update');
        });

        Route::controller(TestimonialController::class)->prefix('testimonials')->name('testimonials.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::patch('/{id}/toggle', 'toggleStatus')->name('toggle');
        });
    });

    Route::controller(LiveExamController::class)->prefix('live-monitoring')->name('live.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/updates', 'fetchUpdates')->name('update');
        Route::post('/action/{id}', 'action')->name('action');
    });

    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::delete('/bulk-destroy', 'bulkDestroy')->name('bulk-destroy');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}/toggle', 'toggleStatus')->name('toggle');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('exams')->name('exams.')->group(function () {
        Route::controller(ExamController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::post('/generate', 'generate')->name('generate');
            Route::delete('/bulk-destroy', 'bulkDestroy')->name('bulkDestroy'); 
            Route::put('/{id}', 'update')->name('update');
            Route::patch('/{id}/toggle', 'toggleStatus')->name('toggle');
            Route::post('/{id}/make-live', 'makeLive')->name('make.live');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::get('/{id}/instructions', [ExamInstructionController::class, 'edit'])->name('instructions');
        Route::put('/{id}/instructions', [ExamInstructionController::class, 'update'])->name('instructions.update');

        Route::controller(ResultController::class)->prefix('results')->name('results.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/pending', 'pending')->name('pending');
            Route::post('/{id}/publish', 'publish')->name('publish');
            Route::post('/mark-answer/{answerId}', 'markAnswer')->name('mark-answer');
            Route::post('/{id}/analyze', 'analyze')->name('analyze');
            Route::post('/{id}/issue-certificate', 'issueCertificate')->name('issue_certificate');
            Route::get('/{id}/certificate', 'downloadCertificate')->name('certificate');
            Route::get('/{id}/download', 'downloadPdf')->name('download');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}', 'show')->name('show');
        });

        Route::controller(QuestionController::class)->group(function () {
            Route::get('/{examId}/questions', 'index')->name('questions');
            Route::prefix('{examId}/questions')->name('questions.')->group(function () {
                Route::post('/generate', 'generate')->name('generate');
                Route::post('/store', 'store')->name('store');
                Route::post('/import', 'import')->name('import');
                Route::get('/download-template', 'downloadTemplate')->name('download_template');
                Route::put('/{questionId}', 'update')->name('update');
                Route::delete('/{questionId}', 'destroy')->name('destroy');
            });
        });
    });

    Route::controller(PlanController::class)->prefix('plans')->name('plans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{id}/assign', 'assignUser')->name('assign');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}/toggle', 'toggleStatus')->name('toggle');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::controller(UserNotificationController::class)->group(function () {
            Route::get('send-notification', 'create')->name('notifications.create');
            Route::post('send-notification', 'send')->name('notifications.send');
            Route::get('search-recipients', 'searchRecipients')->name('notifications.search');
        });

        Route::get('/unverified-email', 'index')->name('unverified.email')->defaults('status', 'unverified_email');
        Route::get('/unverified-mobile', 'index')->name('unverified.mobile')->defaults('status', 'unverified_mobile');
        Route::get('/active', 'index')->name('active')->defaults('status', 'active');
        Route::get('/banned', 'index')->name('banned')->defaults('status', 'banned');
        
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::put('/{user}', 'update')->name('update');
        Route::post('/{id}/assign-plan', 'assignPlan')->name('assign.plan');
        Route::patch('/{user}/toggle-ban', 'toggleBan')->name('toggle.ban');
        Route::patch('/{user}/verify-email', 'verifyEmail')->name('verify.email');
        Route::patch('/{user}/verify-mobile', 'verifyMobile')->name('verify.mobile');
        Route::post('/{user}/notify', 'sendNotification')->name('notify');
        Route::delete('/{user}/notifications/clear', 'clearNotifications')->name('notifications.clear');
        Route::delete('/notifications/{id}', 'deleteNotification')->name('notifications.delete');
        Route::get('/{user}/login-as', 'loginAsUser')->name('login');
    });

    Route::controller(PaymentController::class)->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::patch('/{payment}/approve', 'approve')->name('approve');
        Route::patch('/{payment}/reject', 'reject')->name('reject');
        Route::patch('/{payment}/sync', 'forceSync')->name('sync'); 
    });

    Route::controller(CouponController::class)->prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}/toggle', 'toggleStatus')->name('toggle');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(TicketController::class)->prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{ticket}', 'show')->name('show');
        Route::post('/{ticket}/reply', 'reply')->name('reply');
        Route::post('/{ticket}/close', 'close')->name('close');
        Route::delete('/{ticket}', 'destroy')->name('destroy');
    });

    Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::delete('{role}', 'delete')->name('delete');
        Route::put('{role}/permissions', 'updatePermissions')->name('update.permissions');
        Route::get('{role}/permissions', 'getPermissionsForRole')->name('get.permissions');
    });

    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('subscriptions', 'subscriptionHistory')->name('subscriptions');
        Route::get('exam-history', 'examHistory')->name('exam.history');
        Route::get('login-history', 'loginHistory')->name('login.history');
    });

    Route::prefix('extra')->name('extra.')->group(function () {
        Route::get('application', [SystemInfoController::class, 'application'])->name('application');
        Route::get('server', [SystemInfoController::class, 'server'])->name('server');
        Route::get('cache', [SystemInfoController::class, 'cache'])->name('cache');
        Route::post('cache/clear', [SystemInfoController::class, 'clearCache'])->name('cache.clear');

        Route::get('update', [UpdateController::class, 'index'])->name('update');
        Route::post('update/upload', [UpdateController::class, 'upload'])->name('update.upload');
        
        Route::controller(AddonController::class)->prefix('addons')->name('addons.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/install', 'store')->name('store');
            Route::post('/toggle', 'toggle')->name('toggle');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('system-group', [SystemGroupController::class, 'update'])->name('system.group.update');
        Route::post('branding', [BrandingController::class, 'update'])->name('branding.update');
        
        Route::controller(NotificationSettingsController::class)->group(function () {
            Route::get('notifications', 'index')->name('notifications.index');
            Route::post('notifications', 'update')->name('notifications.update');
            Route::post('notifications/test', 'sendTestEmail')->name('notifications.test');
            Route::get('notifications/templates/{type}', 'editTemplates')->name('notifications.templates.edit');
            Route::post('notifications/templates/{type}', 'updateTemplates')->name('notifications.templates.update');
        });
        
        Route::post('automation/update', [AutomationSettingsController::class, 'update'])->name('automation.update');
        Route::post('security/update', [SecuritySettingsController::class, 'update'])->name('security.update');
        Route::post('payments/update', [PaymentSettingsController::class, 'update'])->name('payments.update');
        
        Route::controller(LocalizationSettingsController::class)->group(function () {
            Route::post('localization/update', 'update')->name('localization.update');
            Route::post('localization/language', 'storeLanguage')->name('language.store');
            Route::put('localization/language/{id}', 'updateLanguage')->name('language.update');
            Route::patch('localization/language/{id}/default', 'setDefaultLanguage')->name('language.default');
            Route::delete('localization/language/{id}', 'destroyLanguage')->name('language.destroy');
        });
        
        Route::controller(SeoSettingsController::class)->prefix('seo')->group(function () {
            Route::post('update', 'update')->name('seo.update');
            Route::post('generate-sitemap', 'generateSitemap')->name('seo.generate-sitemap');
            Route::get('download-sitemap', 'downloadSitemap')->name('seo.download-sitemap');
        });
    });
});