<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // 1. Организации (juridical persons)
        // ============================================================
        Schema::create('organization_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Бизнес, Госструктура, НКО, Ассоциация, Муниципалитет
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Москва, Московская обл., Санкт-Петербург...
            $table->string('slug')->unique();
            $table->string('type', 20);        // city, region, republic
            $table->string('federal_district')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 30)->default('participant'); // participant, moderator, admin, super_admin
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');       // Полное наименование
            $table->string('short_name')->nullable();
            $table->string('inn', 10)->unique();
            $table->string('kpp', 9)->nullable();
            $table->text('legal_address')->nullable();
            $table->text('actual_address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->jsonb('social_links')->nullable();  // {telegram, vk, facebook}
            $table->foreignId('organization_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->decimal('lat', 10, 7)->nullable();  // Широта для карты
            $table->decimal('lng', 10, 7)->nullable();   // Долгота для карты
            $table->boolean('show_contacts')->default(false); // Согласие на публикацию контактов
            $table->timestamps();
        });

        // Users → Organizations (one-to-one через FK, убираем дублирующую колонку из users)
        Schema::table('users', function (Blueprint $table) {
            // organization_id уже есть выше
        });

        Schema::create('organization_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');            // ФИО
            $table->string('position')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // ============================================================
        // 2. Номинации (3 блока)
        // ============================================================
        Schema::create('nomination_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // I, II, III
            $table->string('title');           // Заголовок блока
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('nominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');            // Эко-Производство, Зеленый офис...
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('examples')->nullable(); // Примеры проектов
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ============================================================
        // 3. Проекты
        // ============================================================
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nomination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('goals')->nullable();
            $table->text('results')->nullable();
            $table->string('status', 30)->default('draft');
                // draft | moderation | published | needs_revision | rejected | completed
            $table->text('moderator_comment')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('published_at');
            $table->index(['is_featured', 'status']);
        });

        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);        // image, video, document
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('url')->nullable();         // Для YouTube/Vimeo
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // 4. Пакеты и опции
        // ============================================================
        Schema::create('participant_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Старт, Развитие
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->jsonb('benefits')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('discount', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('partner_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Генеральный, Платиновый...
            $table->string('code', 50)->unique();
            $table->string('tier', 30);        // general, platinum, gold, silver, location
            $table->text('description')->nullable();
            $table->jsonb('benefits')->nullable();
            $table->decimal('price_min', 12, 2)->nullable(); // Диапазон цен
            $table->decimal('price_max', 12, 2)->nullable();
            $table->decimal('price_fixed', 12, 2)->nullable(); // Фикс. цена
            $table->integer('max_count')->nullable();          // Ограничение количества
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('additional_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->string('category', 50)->nullable(); // Стенды, Съёмка, PR...
            $table->decimal('price', 12, 2)->default(0);
            $table->string('price_note')->nullable();     // «от X руб.», «по согласованию»
            $table->boolean('is_active')->default(true);
            $table->boolean('allows_quantity')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('option_quantity_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity_from');
            $table->integer('quantity_to')->nullable();
            $table->decimal('price_per_unit', 12, 2);
            $table->timestamps();
        });

        // ============================================================
        // 5. Заказы и платежи
        // ============================================================
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 30)->unique();       // EARTH-2026-00001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);   // participant | partner
            $table->foreignId('participant_package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('partner_package_id')->nullable()->constrained()->nullOnDelete();
            $table->jsonb('selected_options')->nullable(); // [{option_id, qty, price}]
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('payment_status', 20)->default('pending');
                // pending | paid | cancelled | refunded | overdue
            $table->string('payment_method', 30)->nullable(); // card, sbp, invoice
            $table->string('payment_id')->nullable();          // ID в ЮKassa
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->jsonb('invoice_data')->nullable();  // Реквизиты для счёта
            $table->timestamps();

            $table->index('payment_status');
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('order_option_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('additional_option_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        // ============================================================
        // 6. Сертификаты
        // ============================================================
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('number', 30)->unique(); // CERT-2026-00001
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nomination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('year', 4);
            $table->string('pdf_path')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->text('revoke_reason')->nullable();
            $table->timestamps();

            $table->index('year');
            $table->index('number');
        });

        // ============================================================
        // 7. Мероприятия (Съезд)
        // ============================================================
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('online_url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('registration_open')->default(false);
            $table->timestamps();
        });

        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue', 100)->nullable();   // Зал А, Онлайн...
            $table->string('format', 20)->default('offline'); // offline | online | hybrid
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('company')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->string('social_links')->nullable();
            $table->timestamps();
        });

        Schema::create('event_session_speaker', function (Blueprint $table) {
            $table->foreignId('session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('speaker_id')->constrained()->cascadeOnDelete();
            $table->primary(['session_id', 'speaker_id']);
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'session_id']);
        });

        Schema::create('past_congress_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 20); // video | photo | presentation | report
            $table->string('path')->nullable();
            $table->string('url')->nullable();    // Для YouTube
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // 8. Контент: новости, ресурсы, медиа, FAQ
        // ============================================================
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('category', 50)->default('news');
                // news | cases | announcements | media
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable(); // PDF, DOCX, XLSX
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('category', 100)->nullable();
            $table->jsonb('tags')->nullable();
            $table->string('access_type', 20)->default('free');
                // free | form (после заполнения формы)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faq_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('question');
            $table->longText('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('photo_albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->integer('photo_count')->default(0);
            $table->timestamps();
        });

        Schema::create('album_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type', 20)->default('youtube'); // youtube | vimeo | upload
            $table->string('url')->nullable();               // Embed URL
            $table->string('file_path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('category', 50)->nullable();
            $table->jsonb('tags')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // 9. Контактные формы и уведомления
        // ============================================================
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('contact_name');
            $table->string('position')->nullable();
            $table->string('phone', 20);
            $table->string('email');
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->text('message');
            $table->boolean('consent')->default(false);   // Согласие на обработку ПД
            $table->string('recaptcha_score')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);    // project_status | payment | event | newsletter
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('action_url')->nullable();
            $table->jsonb('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });

        // ============================================================
        // 10. Главная страница и маркетинг
        // ============================================================
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('image');
            $table->string('url')->nullable();
            $table->string('url_text')->nullable();
            $table->string('position', 20)->default('hero'); // hero | banner | announcement
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('template')->nullable();  // default | landing | minimal
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('mailings', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->longText('content')->nullable();
            $table->string('segment', 50);  // all | partners | with_projects | geo_*
            $table->jsonb('segment_data')->nullable();
            $table->string('status', 20)->default('draft'); // draft | sending | sent | cancelled
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->timestamps();
        });

        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group');           // homepage | contacts | seo | integrations
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string'); // string | text | boolean | number | json
            $table->timestamps();
        });

        // ============================================================
        // 11. Логирование и служебное
        // ============================================================
        Schema::create('payment_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->string('event_type')->nullable();
            $table->jsonb('payload');
            $table->string('status', 20)->default('received');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('payment_id');
            $table->index(['status', 'created_at']);
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('log_name')->nullable();
            $table->string('description');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->jsonb('properties')->nullable();
            $table->jsonb('old_values')->nullable();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        // Удаляем в обратном порядке из-за FK
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('payment_webhook_logs');
        Schema::dropIfExists('website_settings');
        Schema::dropIfExists('mailings');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('album_photos');
        Schema::dropIfExists('photo_albums');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('news_comments');
        Schema::dropIfExists('news');
        Schema::dropIfExists('past_congress_archives');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('event_session_speaker');
        Schema::dropIfExists('speakers');
        Schema::dropIfExists('event_sessions');
        Schema::dropIfExists('events');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('order_option_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('option_quantity_tiers');
        Schema::dropIfExists('additional_options');
        Schema::dropIfExists('partner_packages');
        Schema::dropIfExists('participant_packages');
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('project_media');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('nominations');
        Schema::dropIfExists('nomination_categories');
        Schema::dropIfExists('organization_contacts');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('users');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('organization_types');
    }
};
