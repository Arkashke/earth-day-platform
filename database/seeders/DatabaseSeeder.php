<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\NominationCategory;
use App\Models\Nomination;
use App\Models\NominationCategory;
use App\Models\ParticipantPackage;
use App\Models\PartnerPackage;
use App\Models\AdditionalOption;
use App\Models\Region;
use App\Models\FaqCategory;
use App\Models\Faq;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Speaker;
use App\Models\News;
use App\Models\Banner;
use App\Models\Page;
use App\Models\WebsiteSetting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            self::class,
            OrganizationTypeSeeder::class,
            NominationSeeder::class,
            ParticipantPackageSeeder::class,
            PartnerPackageSeeder::class,
            AdditionalOptionSeeder::class,
            RegionSeeder::class,
            FaqSeeder::class,
            AdminUserSeeder::class,
            EventSeeder::class,
            NewsSeeder::class,
            HomepageSeeder::class,
        ]);
    }

    // --- Администратор ---
    public function adminUser(): void
    {
        $org = Organization::create([
            'full_name' => 'Оргкомитет проекта «День Земли — каждый день!»',
            'short_name' => 'Оргкомитет',
            'inn' => '7712345678',
            'kpp' => '771201001',
            'legal_address' => 'г. Москва, ул. Примерная, д. 1',
            'actual_address' => 'г. Москва, ул. Примерная, д. 1',
            'phone' => '+7 (495) 123-45-67',
            'email' => 'info@earthday.ru',
            'website' => 'https://earthday.ru',
            'is_verified' => true,
        ]);

        User::create([
            'organization_id' => $org->id,
            'name' => 'Администратор',
            'email' => 'admin@earthday.ru',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }

    // --- Номинации (3 блока) ---
    public function nominations(): void
    {
        $cat1 = NominationCategory::create([
            'name' => 'I',
            'title' => 'Корпоративная экологическая ответственность',
            'description' => 'Инициативы в области корпоративной экологической ответственности бизнеса',
            'sort_order' => 1,
        ]);

        $cat2 = NominationCategory::create([
            'name' => 'II',
            'title' => 'Продукты и услуги для устойчивого развития',
            'description' => 'Экологичные продукты, технологии и услуги',
            'sort_order' => 2,
        ]);

        $cat3 = NominationCategory::create([
            'name' => 'III',
            'title' => 'Инвестиции в будущее',
            'description' => 'Проекты инвестирования в зелёные технологии и устойчивое развитие',
            'sort_order' => 3,
        ]);

        // Категория I
        $noms1 = [
            ['name' => 'Эко-Производство', 'slug' => 'eco-production', 'description' => 'Внедрение экологичных технологий в производственные процессы', 'examples' => 'Пример: переход на возобновляемую энергию, сокращение выбросов'],
            ['name' => 'Устойчивая упаковка', 'slug' => 'sustainable-packaging', 'description' => 'Разработка и использование экологичной упаковки', 'examples' => 'Пример: биоразлагаемая упаковка, многоразовая тара'],
            ['name' => 'Зелёный офис', 'slug' => 'green-office', 'description' => 'Экологичная организация офисного пространства', 'examples' => 'Пример: раздельный сбор, энергоэффективность, зелёные закупки'],
            ['name' => 'Социальная ответственность', 'slug' => 'social-responsibility', 'description' => 'Экологические инициативы в области КСО', 'examples' => 'Пример: волонтёрские акции, экообразование'],
        ];

        // Категория II
        $noms2 = [
            ['name' => 'Эко-продукт года', 'slug' => 'eco-product', 'description' => 'Лучший экологически безопасный продукт', 'examples' => 'Пример: товары с эко-сертификацией'],
            ['name' => 'Зелёные технологии', 'slug' => 'green-tech', 'description' => 'Инновационные экологические технологии', 'examples' => 'Пример: переработка отходов, очистка воды'],
            ['name' => 'Эко-услуга года', 'slug' => 'eco-service', 'description' => 'Экологически ориентированные услуги', 'examples' => 'Пример: экотуризм, шеринг'],
            ['name' => 'Циркулярная экономика', 'slug' => 'circular-economy', 'description' => 'Проекты замкнутого цикла', 'examples' => 'Пример: апсайклинг, рециклинг'],
        ];

        // Категория III
        $noms3 = [
            ['name' => 'Инвестор в зелёные технологии', 'slug' => 'green-investor', 'description' => 'Значительные инвестиции в зелёные технологии', 'examples' => 'Пример: венчурные фонды, корпоративные инвестиции'],
            ['name' => 'Эко-акции', 'slug' => 'eco-shares', 'description' => 'Компании с лучшими ESG-практиками', 'examples' => 'Пример: публичные компании с ESG-рейтингом'],
            ['name' => 'Партнёр фестиваля', 'slug' => 'festival-partner', 'description' => 'Партнёрство в экологических мероприятиях', 'examples' => 'Пример: организаторы эко-фестивалей'],
        ];

        foreach ($noms1 as $i => $n) { Nomination::create(array_merge($n, ['category_id' => $cat1->id, 'sort_order' => $i])); }
        foreach ($noms2 as $i => $n) { Nomination::create(array_merge($n, ['category_id' => $cat2->id, 'sort_order' => $i])); }
        foreach ($noms3 as $i => $n) { Nomination::create(array_merge($n, ['category_id' => $cat3->id, 'sort_order' => $i])); }
    }

    // --- Пакеты участников ---
    public function participantPackages(): void
    {
        ParticipantPackage::create([
            'name' => 'Базовый',
            'code' => 'basic',
            'description' => 'Бесплатная регистрация проекта. Без привилегий и партнёрских опций.',
            'benefits' => ['Регистрация 1 проекта', 'Публикация в каталоге', 'Электронный сертификат участника'],
            'price' => 0,
            'is_active' => true,
            'is_featured' => false,
        ]);

        ParticipantPackage::create([
            'name' => 'Корпоративный Старт',
            'code' => 'start',
            'description' => 'Пакет для небольших компаний, начинающих экологический путь.',
            'benefits' => ['Регистрация до 3 проектов', 'Приоритетная модерация', 'Сертификат участника', 'Логотип на сайте'],
            'price' => 150000,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        ParticipantPackage::create([
            'name' => 'Корпоративный Развитие',
            'code' => 'growth',
            'description' => 'Максимальные возможности для активных участников.',
            'benefits' => ['Без ограничений проектов', 'VIP-модерация', 'Выход на сцену съезда', 'PR-материалы', 'VIP-стол на съезде', 'Бесплатные дополнительные опции'],
            'price' => 250000,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 2,
        ]);
    }

    // --- Партнёрские пакеты ---
    public function partnerPackages(): void
    {
        PartnerPackage::create([
            'name' => 'Генеральный партнёр',
            'tier' => 'general',
            'code' => 'general',
            'description' => 'Эксклюзивный статус главного партнёра проекта',
            'benefits' => ['Co-branding', 'Приветственное слово', 'Брендирование сцены', 'Эксклюзивная номинация', 'Лендинг-страница', 'PR-пакет', '20 VIP-билетов', 'B2B-брейкфаст'],
            'price_fixed' => 10000000,
            'max_count' => 1,
            'is_active' => true,
        ]);

        PartnerPackage::create([
            'name' => 'Платиновый партнёр',
            'tier' => 'platinum',
            'code' => 'platinum',
            'description' => 'Максимальная видимость и привилегии',
            'benefits' => ['Логотип в шапке', 'Модерация сессии', '8 VIP-билетов', 'Стенд 9-12 м²', 'Быстрые презентации'],
            'price_min' => 3000000,
            'price_max' => 5000000,
            'max_count' => 10,
            'is_active' => true,
        ]);

        PartnerPackage::create([
            'name' => 'Золотой партнёр',
            'tier' => 'gold',
            'code' => 'gold',
            'description' => 'Премиальное партнёрство',
            'benefits' => ['Логотип на сайте', 'Быстрые презентации', 'Стенд 6-9 м²', '4 VIP-билета'],
            'price_min' => 1000000,
            'price_max' => 2500000,
            'is_active' => true,
        ]);

        PartnerPackage::create([
            'name' => 'Серебряный партнёр',
            'tier' => 'silver',
            'code' => 'silver',
            'description' => 'Видимость бренда в партнёрском блоке',
            'benefits' => ['Логотип в партнёрском блоке', '1-2 упоминания в соцсетях'],
            'price_min' => 400000,
            'price_max' => 900000,
            'is_active' => true,
        ]);

        PartnerPackage::create([
            'name' => 'Партнёр локации/события',
            'tier' => 'location',
            'code' => 'location',
            'description' => 'Брендирование отдельной зоны',
            'benefits' => ['Брендирование локальной зоны', 'Упоминание в афише'],
            'price_min' => 150000,
            'price_max' => 350000,
            'is_active' => true,
        ]);
    }

    // --- Регионы России ---
    public function regions(): void
    {
        $regions = [
            ['name' => 'Москва', 'slug' => 'moscow', 'type' => 'city', 'federal_district' => 'Центральный'],
            ['name' => 'Московская область', 'slug' => 'moscow-oblast', 'type' => 'region', 'federal_district' => 'Центральный'],
            ['name' => 'Санкт-Петербург', 'slug' => 'spb', 'type' => 'city', 'federal_district' => 'Северо-Западный'],
            ['name' => 'Ленинградская область', 'slug' => 'lenoblast', 'type' => 'region', 'federal_district' => 'Северо-Западный'],
            ['name' => 'Краснодарский край', 'slug' => 'krasnodar', 'type' => 'region', 'federal_district' => 'Южный'],
            ['name' => 'Республика Татарстан', 'slug' => 'tatarstan', 'type' => 'republic', 'federal_district' => 'Приволжский'],
            ['name' => 'Свердловская область', 'slug' => 'sverdlovsk', 'type' => 'region', 'federal_district' => 'Уральский'],
            ['name' => 'Новосибирская область', 'slug' => 'novosibirsk', 'type' => 'region', 'federal_district' => 'Сибирский'],
            ['name' => 'Красноярский край', 'slug' => 'krasnoyarsk', 'type' => 'region', 'federal_district' => 'Сибирский'],
            ['name' => 'Приморский край', 'slug' => 'primorsky', 'type' => 'region', 'federal_district' => 'Дальневосточный'],
            ['name' => 'Республика Крым', 'slug' => 'crimea', 'type' => 'republic', 'federal_district' => 'Южный'],
            ['name' => 'Ростовская область', 'slug' => 'rostov', 'type' => 'region', 'federal_district' => 'Южный'],
            ['name' => 'Нижегородская область', 'slug' => 'nizhny', 'type' => 'region', 'federal_district' => 'Приволжский'],
            ['name' => 'Челябинская область', 'slug' => 'chelyabinsk', 'type' => 'region', 'federal_district' => 'Уральский'],
            ['name' => 'Самарская область', 'slug' => 'samara', 'type' => 'region', 'federal_district' => 'Приволжский'],
        ];

        foreach ($regions as $r) {
            Region::create($r);
        }
    }

    // --- FAQ ---
    public function faqs(): void
    {
        $cat1 = FaqCategory::create(['name' => 'Участие', 'sort_order' => 1]);
        $cat2 = FaqCategory::create(['name' => 'Оплата', 'sort_order' => 2]);
        $cat3 = FaqCategory::create(['name' => 'Сертификаты', 'sort_order' => 3]);
        $cat4 = FaqCategory::create(['name' => 'Съезд', 'sort_order' => 4]);

        $faqs = [
            [$cat1->id, 'Кто может участвовать в проекте?', 'В проекте могут участвовать только юридические лица: компании, ассоциации, государственные структуры, муниципалитеты и НКО. Физические лица к участию не допускаются.'],
            [$cat1->id, 'Сколько проектов можно зарегистрировать?', 'Количество проектов зависит от выбранного пакета участника. Базовый пакет — 1 проект, «Старт» — до 3 проектов, «Развитие» — без ограничений.'],
            [$cat1->id, 'Как проходит модерация?', 'Проекты проходят модерацию в течение 5 рабочих дней. Модератор проверяет соответствие номинации, полноту описания и наличие обязательных материалов.'],
            [$cat2->id, 'Как оплатить пакет участника?', 'Оплата производится онлайн картой или через СБП. Для юридических лиц доступна оплата по счёту с указанием реквизитов.'],
            [$cat2->id, 'Можно ли получить счёт для оплаты?', 'Да, при оформлении заказа выберите способ оплаты «Счёт для юридического лица». Счёт будет отправлен на email и доступен в личном кабинете.'],
            [$cat3->id, 'Как получить сертификат?', 'Сертификат автоматически генерируется после одобрения вашего проекта модератором. PDF-файл доступен в личном кабинете и в реестре сертификатов на сайте.'],
            [$cat3->id, 'Как проверить подлинность сертификата?', 'Каждый сертификат имеет уникальный номер и QR-код. Отсканировав QR-код или введя номер на странице реестра, можно проверить подлинность и данные сертификата.'],
            [$cat4->id, 'Как зарегистрироваться на съезд?', 'Авторизованные участники могут зарегистрироваться на сессии съезда в разделе «Мероприятия» личного кабинета. После регистрации можно скачать .ics-файл для добавления в календарь.'],
            [$cat4->id, 'Будет ли онлайн-трансляция?', 'Да, основные сессии съезда будут транслироваться онлайн. Ссылка на трансляцию появится в день мероприятия в личном кабинете и на странице события.'],
        ];

        foreach ($faqs as $i => [$catId, $q, $a]) {
            Faq::create(['category_id' => $catId, 'question' => $q, 'answer' => $a, 'sort_order' => $i]);
        }
    }

    // --- Мероприятие (Съезд 2026) ---
    public function events(): void
    {
        $event = Event::create([
            'title' => 'Ежегодный съезд экоучастников — 2026',
            'slug' => 'congress-2026',
            'description' => 'Главное событие года для экологически ответственного бизнеса. Доклады ведущих экспертов, церемония награждения, нетворкинг.',
            'start_date' => '2026-09-15',
            'end_date' => '2026-09-15',
            'venue_name' => 'Президент-Отель',
            'venue_address' => 'г. Москва, ул. Большая Якиманка, д. 24',
            'online_url' => null,
            'is_active' => true,
            'registration_open' => true,
        ]);

        EventSession::create([
            'event_id' => $event->id,
            'title' => 'Открытие съезда',
            'description' => 'Приветственное слово организаторов, презентация итогов года',
            'date' => '2026-09-15',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'venue' => 'Большой зал',
            'format' => 'hybrid',
            'max_participants' => 500,
        ]);

        EventSession::create([
            'event_id' => $event->id,
            'title' => 'Панельная дискуссия: ESG и будущее',
            'description' => 'Эксперты обсуждают тренды устойчивого развития',
            'date' => '2026-09-15',
            'start_time' => '11:15',
            'end_time' => '12:30',
            'venue' => 'Большой зал',
            'format' => 'hybrid',
            'max_participants' => 400,
        ]);

        EventSession::create([
            'event_id' => $event->id,
            'title' => 'Церемония награждения',
            'description' => 'Торжественное вручение сертификатов и наград участникам',
            'date' => '2026-09-15',
            'start_time' => '17:00',
            'end_time' => '19:00',
            'venue' => 'Банкетный зал',
            'format' => 'offline',
            'max_participants' => 300,
        ]);

        EventSession::create([
            'event_id' => $event->id,
            'title' => 'B2B-брейкфаст для партнёров',
            'description' => 'Нетворкинг для генеральных и платиновых партнёров',
            'date' => '2026-09-15',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'venue' => 'Зал «Москва»',
            'format' => 'offline',
            'max_participants' => 50,
        ]);
    }

    // --- Новости ---
    public function news(): void
    {
        News::create([
            'title' => 'Старт приёма заявок на 2026 год',
            'slug' => 'start-applications-2026',
            'excerpt' => 'Объявляем о начале приёма заявок на участие в проекте «День Земли — каждый день!» в 2026 году.',
            'content' => '<p>Дорогие коллеги!</p><p>Мы рады объявить о начале приёма заявок на участие в проекте «День Земли — каждый день!» в 2026 году.</p><p>В этом году мы расширили количество номинаций и упростили процесс подачи заявки. Присоединяйтесь к нашему сообществу экологически ответственных организаций!</p>',
            'category' => 'announcements',
            'is_published' => true,
            'published_at' => now()->subDays(3),
        ]);

        News::create([
            'title' => 'Кейс: как «ЭкоСфера» сократила выбросы на 40%',
            'slug' => 'case-ecosfera-emissions',
            'excerpt' => 'Делимся успешным опытом участника проекта — компании «ЭкоСфера»',
            'content' => '<p>Компания «ЭкоСфера» поделилась своим опытом реализации программы по сокращению углеродного следа...</p>',
            'category' => 'cases',
            'is_published' => true,
            'published_at' => now()->subDays(7),
        ]);

        News::create([
            'title' => 'Вебинар: подготовка заявки на конкурс',
            'slug' => 'webinar-application-prep',
            'excerpt' => 'Приглашаем на бесплатный вебинар по подготовке конкурсной заявки',
            'content' => '<p>Вебинар состоится 20 апреля в 14:00 (мск). Регистрация обязательна.</p>',
            'category' => 'announcements',
            'is_published' => true,
            'published_at' => now()->subDays(1),
        ]);
    }

    // --- Баннеры ---
    public function banners(): void
    {
        Banner::create([
            'title' => 'День Земли — каждый день!',
            'subtitle' => 'Присоединяйтесь к крупнейшему экологическому движению России',
            'image' => '/img/banners/hero-default.jpg',
            'url' => '/register',
            'url_text' => 'Участвовать',
            'position' => 'hero',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'Съезд 2026 — 15 сентября',
            'subtitle' => 'Регистрация на главное экологическое событие года открыта!',
            'image' => '/img/banners/congress-banner.jpg',
            'url' => '/events/congress-2026',
            'url_text' => 'Зарегистрироваться',
            'position' => 'announcement',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    // --- Страницы ---
    public function pages(): void
    {
        Page::create([
            'title' => 'О проекте',
            'slug' => 'about',
            'content' => '<h2>О проекте «День Земли — каждый день!»</h2><p>Мы верим, что экологическая ответственность — это не разовая акция, а образ жизни...</p>',
            'template' => 'default',
            'meta_title' => 'О проекте — День Земли',
            'meta_description' => 'Узнайте больше о проекте «День Земли — каждый день!», его миссии и целях.',
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Политика конфиденциальности',
            'slug' => 'privacy',
            'content' => '<h2>Политика в отношении обработки персональных данных</h2><p>Настоящая политика определяет порядок обработки персональных данных пользователей сайта...</p>',
            'template' => 'minimal',
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Договор оферты',
            'slug' => 'offer',
            'content' => '<h2>Публичная оферта</h2><p>Настоящий документ является официальным предложением...</p>',
            'template' => 'minimal',
            'is_published' => true,
        ]);
    }

    // --- Настройки сайта ---
    public function websiteSettings(): void
    {
        $settings = [
            ['homepage', 'mission_text', 'День Земли — это не один день в году.\nЭто образ жизни, мышления и ответственности.\nЭто объединение тех, кто действует.', 'text'],
            ['homepage', 'counter_participants', '120', 'number'],
            ['homepage', 'counter_projects', '340', 'number'],
            ['homepage', 'counter_partners', '25', 'number'],
            ['contacts', 'address', 'г. Москва, ул. Примерная, д. 1', 'string'],
            ['contacts', 'phone', '+7 (495) 123-45-67', 'string'],
            ['contacts', 'email', 'info@earthday.ru', 'string'],
            ['contacts', 'working_hours', 'Пн-Пт: 10:00–18:00', 'string'],
            ['seo', 'site_name', 'День Земли — каждый день!', 'string'],
            ['seo', 'default_description', 'Платформа для регистрации и продвижения экологических инициатив юридических лиц России', 'text'],
            ['integrations', 'recaptcha_enabled', 'false', 'boolean'],
            ['integrations', 'yandex_maps_enabled', 'true', 'boolean'],
            ['integrations', 'yandex_metrika', '', 'string'],
        ];

        foreach ($settings as [$group, $key, $value, $type]) {
            WebsiteSetting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $value, 'type' => $type]
            );
        }
    }
}
