# 🌍 День Земли — каждый день!
# Earth Day Platform

Платформа для регистрации и продвижения экологических инициатив юридических лиц.

## 功能概述 / Overview

Многофункциональный веб-портал, включающий:
- Публичный каталог экологических проектов с фильтрами и картой
- Личный кабинет участника (юр. лица)
- Административная панель (CRM)
- Модерация проектов
- Выдача сертификатов (PDF с QR-кодом)
- Продажа партнёрских пакетов и дополнительных опций
- Интеграция с платёжными агрегаторами (ЮKassa)
- Гео-карта проектов (Яндекс.Карты API)
- E-mail-рассылки
- Регистрация на съезд

## 技术栈 / Stack

- **Backend:** PHP 8.2+ / Laravel 11
- **Frontend:** Vue 3 + Inertia.js
- **Styling:** Vanilla CSS (с дизайн-токенами)
- **DB:** MySQL 8.0
- **Cache:** Redis
- **Queue:** Redis / Supervisor
- **Maps:** Яндекс.Карты API v3
- **Payments:** ЮKassa API / CloudPayments
- **Search:** Laravel Scout + database driver
- **PDF:** DomPDF
- **File Storage:** Yandex Object Storage (S3)
- **Email:** Laravel Mail / UniSender
- **Auth:** Laravel Fortify + Sanctum

---

##  Быстрый старт / Quick Start

### Локальная разработка (Windows)

```bash
# 1. Клонировать / скопировать проект
cd earth-day-platform

# 2. Установить зависимости
composer install
npm install

# 3. Скопировать и настроить .env
copy .env.example .env

# 4. Сгенерировать ключ приложения
php artisan key:generate

# 5. Создать базу данных MySQL
# CREATE DATABASE earth_day_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 6. Запустить миграции и сидеры
php artisan migrate --seed

# 7. Создать символическую ссылку для хранилища
php artisan storage:link

# 8. Запустить dev-сервер
php artisan serve
# или
npm run dev
```

### Docker (рекомендуется для production и сложной разработки)

```bash
# Запуск всех сервисов
docker-compose up -d

# Инициализация внутри контейнера
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link

# Остановка
docker-compose down
```

---

## Структура проекта / Project Structure

```
earth-day-platform/
├── app/
│   ├── Console/Commands/         # Artisan-команды (cron, генерация сертификатов)
│   ├── Events/                   # События приложения
│   ├── Exceptions/               # Обработчики исключений
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Контроллеры админ-панели
│   │   │   ├── Auth/             # Контроллеры аутентификации
│   │   │   ├── CatalogController.php
│   │   │   ├── CertificateController.php
│   │   │   ├── ContactController.php
│   │   │   ├── EventController.php
│   │   │   ├── HomepageController.php
│   │   │   ├── MailingController.php
│   │   │   ├── NewsController.php
│   │   │   ├── PageController.php
│   │   │   ├── PartnerController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── RecognitionController.php
│   │   │   └── SearchController.php
│   │   ├── Middleware/           # Промежуточное ПО
│   │   ├── Requests/             # Формы и валидация
│   │   └── Resources/             # API Resources (JSON)
│   ├── Jobs/                     # Очередные задания
│   ├── Listeners/                # Обработчики событий
│   ├── Mail/                     # E-mail-шаблоны
│   ├── Models/                   # Eloquent-модели
│   ├── Notifications/            # Уведомления (email/SMS/DB)
│   ├── Observers/                # Наблюдатели моделей
│   ├── Policies/                 # Политики авторизации
│   ├── Providers/                # Сервис-провайдеры
│   ├── Services/                 # Бизнес-логика (DDD)
│   │   ├── CertificateService.php
│   │   ├── PaymentService.php
│   │   ├── ModerationService.php
│   │   ├── SearchService.php
│   │   └── YandexMapService.php
│   ├── Exporters/                # Экспорт в Excel/PDF
│   └── Imports/                  # Импорт из Excel/CSV
├── bootstrap/                    # Laravel bootstrap
├── config/                       # Конфигурация приложения
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── company.php              # Реквизиты организации
│   ├── database.php
│   ├── filesystems.php          # Локально / S3 / Yandex
│   ├── mail.php
│   ├── queue.php
│   ├── services.php             # API-ключи (ЮKassa, reCAPTCHA, карты)
│   ├── spam.php                 # Защита от спама
│   └── recaptcha.php
├── database/
│   ├── factories/               # Фабрики для тестов
│   ├── migrations/              # Миграции БД
│   └── seeders/                 # Начальные данные
├── docker/                      # Docker-конфигурация
│   ├── Dockerfile
│   ├── php.ini
│   ├── nginx.conf
│   └── supervisord.conf
├── public/                      # Корневая папка веб-сервера
│   ├── index.php                # Точка входа
│   └── .htaccess               # Apache rewrite (для reg.ru)
├── resources/
│   ├── css/app.css              # Основные стили
│   ├── js/
│   │   ├── app.js               # JS entry point
│   │   ├── bootstrap.js
│   │   ├── Components/          # Переиспользуемые Vue-компоненты
│   │   ├── Layouts/              # Лейауты (AppLayout, DashboardLayout)
│   │   ├── Pages/               # Inertia-страницы
│   │   ├── Store/                # Pinia store
│   │   └── Composable/          # Vue composables
│   ├── lang/ru/                 # Локализация
│   └── views/                   # Blade-шаблоны (email, PDF)
├── routes/
│   ├── web.php                  # Публичные маршруты
│   ├── api.php                  # API v1
│   ├── admin.php                # Админ-панель
│   ├── channels.php             # Broadcast-каналы
│   └── console.php              # Console-команды
├── scripts/                     # Утилиты развёртывания
│   ├── deploy.sh
│   └── backup-db.sh
├── storage/
│   ├── app/
│   │   └── public/              # Публичные файлы (symlink → public/storage)
│   │       ├── certificates/    # PDF сертификаты
│   │       ├── organizations/   # Логотипы организаций
│   │       ├── projects/         # Фото/видео проектов
│   │       ├── news/             # Изображения новостей
│   │       ├── resources/        # Файлы базы знаний
│   │       └── media/            # Фото/видео галерея
│   ├── framework/               # Laravel runtime (cache, sessions, views)
│   └── logs/                    # Логи приложения
├── tests/
│   ├── Feature/                 # Интеграционные тесты
│   └── Unit/                    # Юнит-тесты
├── .env.example                 # Шаблон переменных окружения
├── .gitignore
├── composer.json
├── package.json
├── vite.config.js
├── phpunit.xml
├── docker-compose.yml
└── README.md
```

---

## Развёртывание на хостинге (reg.ru)

### FTP / Хостинг-панель

1. Скопируйте всю папку проекта на хостинг в корневую директорию сайта
2. В панели управления хостингом установите **PHP 8.2+**
3. Создайте базу данных **MySQL 8.0** в панели хостинга
4. Загрузите `.env` файл с реальными параметрами БД
5. Запустите миграции:
   ```bash
   php artisan migrate
   php artisan storage:link
   ```
6. Настройте **CRON** (раз в минуту):
   ```
   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
   ```

### VPS (Ubuntu 22.04)

```bash
# Установка окружения
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
  php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd \
  php8.2-bcmath php8.2-intl php8.2-redis unzip git \
  nginx mysql-server redis-server certbot python3-certbot-nginx

# Клонировать проект
git clone https://github.com/your-repo/earth-day-platform.git
cd earth-day-platform
composer install --optimize-autoloader
npm install && npm run build

# Настроить Nginx
sudo cp docker/nginx.conf /etc/nginx/sites-available/earth-day
sudo ln -s /etc/nginx/sites-available/earth-day /etc/nginx/sites-enabled/
sudo certbot --nginx -d earthday.ru

# Настроить .env для production
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan storage:link

# Настроить Supervisor для очередей
sudo cp docker/supervisord.conf /etc/supervisor/conf.d/earth-day.conf
sudo supervisorctl reread
sudo supervisorctl update
```

---

## API Endpoints (v1)

```
GET    /api/v1/projects              # Каталог проектов
GET    /api/v1/projects/{id}         # Детали проекта
GET    /api/v1/nominations           # Список номинаций
GET    /api/v1/events                # Мероприятия
GET    /api/v1/certificates/search   # Поиск сертификата
POST   /api/v1/orders/webhook        # Webhook платежа (ЮKassa)
GET    /api/v1/search                # Глобальный поиск
```

---

## Ключевые модели данных

| Модель | Описание |
|---|---|
| `User` | Пользователь (юр. лицо) |
| `Organization` | Организация (связь 1:1 с User) |
| `Project` | Проект/инициатива |
| `NominationCategory` | Блок номинаций (I, II, III) |
| `Nomination` | Конкретная номинация |
| `ParticipantPackage` | Пакеты участника (Старт/Развитие) |
| `PartnerPackage` | Партнёрские пакеты |
| `AdditionalOption` | Дополнительные опции |
| `Order` | Заказ (пакет + опции) |
| `Certificate` | Сертификат участника |
| `News` | Новость |
| `Event` | Мероприятие (Съезд) |
| `EventSession` | Сессия съезда |
| `Speaker` | Спикер |
| `EventRegistration` | Регистрация на сессию |
| `Banner` | Баннер главной страницы |
| `FaqCategory` | Категория FAQ |
| `Faq` | Вопрос-ответ |
| `Resource` | Материал базы знаний |
| `PhotoAlbum` | Фотоальбом |
| `Video` | Видеозапись |
| `Page` | Произвольная страница |
| `Mailing` | E-mail-рассылка |
| `Region` | Справочник регионов |
| `OrganizationType` | Тип организации |
| `Notification` | Уведомление пользователя |

---

## 法律事项 / Legal Notes

- Цены в ТЗ — справочные, редактируются в админке
- Все персональные данные обрабатываются в соответствии с **152-ФЗ**
- Хранение данных — только на серверах в **РФ**
- SSL-сертификат обязателен (Let's Encrypt)
- Исключительные права на ПО — после полной оплаты

---

## 联系方式 / Contacts

Оргкомитет проекта «День Земли — каждый день!»
📧 info@earthday.ru | 📞 +7 (495) 123-45-67
