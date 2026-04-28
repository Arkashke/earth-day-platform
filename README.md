# Earth Day Platform

Платформа для регистрации и продвижения экологических инициатив юридических лиц.

## Overview

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

## Stack

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

## Структура проекта

```
earth-day-platform/
├── app/
│
└── README.md
```
## 联系方式 / Contacts

Оргкомитет проекта «День Земли — каждый день!»
📧 info@earthday.ru | 📞 +7 (495) 123-45-67
