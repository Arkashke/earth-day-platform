<?php

use Illuminate\Support\Facades\Route;

// === Панель администратора (Earth Day CRM) ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Дашборд
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // === Управление участниками (юр. лица) ===
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verify'])
        ->name('users.verify');
    Route::post('users/{user}/block', [\App\Http\Controllers\Admin\UserController::class, 'block'])
        ->name('users.block');
    Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])
        ->name('users.reset-password');
    Route::get('users/export/excel', [\App\Http\Controllers\Admin\UserController::class, 'exportExcel'])
        ->name('users.export');

    // === Модерация проектов ===
    Route::get('moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'index'])
        ->name('moderation.index');
    Route::get('moderation/{project}', [\App\Http\Controllers\Admin\ModerationController::class, 'show'])
        ->name('moderation.show');
    Route::post('moderation/{project}/approve', [\App\Http\Controllers\Admin\ModerationController::class, 'approve'])
        ->name('moderation.approve');
    Route::post('moderation/{project}/reject', [\App\Http\Controllers\Admin\ModerationController::class, 'reject'])
        ->name('moderation.reject');
    Route::post('moderation/{project}/request-changes', [\App\Http\Controllers\Admin\ModerationController::class, 'requestChanges'])
        ->name('moderation.request-changes');
    Route::post('moderation/bulk-action', [\App\Http\Controllers\Admin\ModerationController::class, 'bulkAction'])
        ->name('moderation.bulk');

    // === Справочники ===
    Route::resource('nominations', \App\Http\Controllers\Admin\NominationController::class);
    Route::resource('nomination-categories', \App\Http\Controllers\Admin\NominationCategoryController::class);
    Route::resource('organization-types', \App\Http\Controllers\Admin\OrganizationTypeController::class);
    Route::resource('regions', \App\Http\Controllers\Admin\RegionController::class);

    // === Партнёрские пакеты и опции ===
    Route::resource('partner-packages', \App\Http\Controllers\Admin\PartnerPackageController::class);
    Route::resource('additional-options', \App\Http\Controllers\Admin\AdditionalOptionController::class);
    Route::resource('participant-packages', \App\Http\Controllers\Admin\ParticipantPackageController::class);

    // === Сертификаты ===
    Route::get('certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'index'])
        ->name('certificates.index');
    Route::get('certificates/{certificate}/download', [\App\Http\Controllers\Admin\CertificateController::class, 'download'])
        ->name('certificates.download');
    Route::post('certificates/generate/{project}', [\App\Http\Controllers\Admin\CertificateController::class, 'generate'])
        ->name('certificates.generate');
    Route::post('certificates/regenerate/{certificate}', [\App\Http\Controllers\Admin\CertificateController::class, 'regenerate'])
        ->name('certificates.regenerate');
    Route::get('certificates/export/zip', [\App\Http\Controllers\Admin\CertificateController::class, 'exportZip'])
        ->name('certificates.export-zip');

    // === Новости ===
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::post('news/{news}/toggle', [\App\Http\Controllers\Admin\NewsController::class, 'toggle'])
        ->name('news.toggle');

    // === Мероприятия (Съезд) ===
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
    Route::resource('events.sessions', \App\Http\Controllers\Admin\EventSessionController::class);
    Route::resource('events.speakers', \App\Http\Controllers\Admin\SpeakerController::class);
    Route::get('events/{event}/registrations', [\App\Http\Controllers\Admin\EventRegistrationController::class, 'index'])
        ->name('events.registrations.index');

    // === Ресурсы / База знаний ===
    Route::resource('resources', \App\Http\Controllers\Admin\ResourceController::class);

    // === Медиа ===
    Route::resource('photo-albums', \App\Http\Controllers\Admin\PhotoAlbumController::class);
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class);

    // === Контент главной страницы ===
    Route::get('homepage', [\App\Http\Controllers\Admin\HomepageController::class, 'edit'])
        ->name('homepage.edit');
    Route::put('homepage', [\App\Http\Controllers\Admin\HomepageController::class, 'update'])
        ->name('homepage.update');
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);

    // === FAQ ===
    Route::resource('faq-categories', \App\Http\Controllers\Admin\FaqCategoryController::class);
    Route::resource('faq', \App\Http\Controllers\Admin\FaqController::class);

    // === Заказы и финансы ===
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
        ->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])
        ->name('orders.show');
    Route::post('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'sendInvoice'])
        ->name('orders.invoice');
    Route::post('orders/{order}/refund', [\App\Http\Controllers\Admin\OrderController::class, 'refund'])
        ->name('orders.refund');
    Route::get('orders/export/excel', [\App\Http\Controllers\Admin\OrderController::class, 'exportExcel'])
        ->name('orders.export');

    // === Отчёты ===
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('projects', [\App\Http\Controllers\Admin\ReportController::class, 'projects'])
            ->name('projects');
        Route::get('revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])
            ->name('revenue');
        Route::get('participants', [\App\Http\Controllers\Admin\ReportController::class, 'participants'])
            ->name('participants');
        Route::get('export/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])
            ->name('export');
    });

    // === Рассылки ===
    Route::resource('mailings', \App\Http\Controllers\Admin\MailingController::class);
    Route::post('mailings/{mailing}/send', [\App\Http\Controllers\Admin\MailingController::class, 'send'])
        ->name('mailings.send');

    // === Настройки ===
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])
        ->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])
        ->name('settings.update');
    Route::get('settings/integrations', [\App\Http\Controllers\Admin\SettingController::class, 'integrations'])
        ->name('settings.integrations');
    Route::get('settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'seo'])
        ->name('settings.seo');
    Route::put('settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'updateSeo'])
        ->name('settings.seo.update');
    Route::get('logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])
        ->name('logs.index');

    // === Страницы (произвольный контент) ===
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
});
