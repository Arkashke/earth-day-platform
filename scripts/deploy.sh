#!/usr/bin/env bash
# ================================================
# Earth Day Platform — Deploy Script
# Для reg.ru VPS / Ubuntu Server
# ================================================

set -euo pipefail

# --- Цвета ---
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log() { echo -e "${GREEN}[DEPLOY]${NC} $1"; }
warn() { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1" >&2; }

# --- Проверка аргументов ---
if [ $# -lt 1 ]; then
    echo "Использование: ./scripts/deploy.sh <environment>"
    echo "  environment: production | staging"
    exit 1
fi

ENV=$1
PROJECT_DIR="/var/www/earth-day-platform"
BACKUP_DIR="/var/backups/earth-day"

log "Начало деплоя (${ENV})"

# --- Создание резервной копии ---
mkdir -p "${BACKUP_DIR}"
if [ -d "${PROJECT_DIR}/.git" ]; then
    log "Создание резервной копии..."
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    tar -czf "${BACKUP_DIR}/backup_${TIMESTAMP}.tar.gz" \
        --exclude="${PROJECT_DIR}/vendor" \
        --exclude="${PROJECT_DIR}/node_modules" \
        --exclude="${PROJECT_DIR}/.git" \
        --exclude="${PROJECT_DIR}/storage/framework/cache/*" \
        --exclude="${PROJECT_DIR}/storage/framework/sessions/*" \
        -C "$(dirname "${PROJECT_DIR}")" "$(basename "${PROJECT_DIR}")" 2>/dev/null || true
    log "Резервная копия: ${BACKUP_DIR}/backup_${TIMESTAMP}.tar.gz"
fi

cd "${PROJECT_DIR}"

# --- Pull последних изменений ---
if [ -d ".git" ]; then
    log "Обновление исходного кода..."
    git pull origin main
fi

# --- Установка зависимостей ---
log "composer install..."
composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

log "npm install..."
npm ci --prefer-offline

log "npm build..."
npm run build

# --- Настройка окружения ---
if [ ! -f .env ]; then
    cp .env.example .env
    warn ".env создан из шаблона. НАСТРОЙТЕ ЕГО ВРУЧНУЮ!"
fi

log "Генерация ключа приложения..."
php artisan key:generate --force

# --- Миграции ---
log "Запуск миграций базы данных..."
php artisan migrate --force --no-interaction

# --- Очистка и оптимизация ---
log "Очистка кэша..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

log "Кэширование конфигурации..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

log "Создание символических ссылок..."
php artisan storage:link

# --- Права доступа ---
log "Настройка прав..."
chgrp -R www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
chmod -R 775 public

# --- Перезапуск очередей и cron ---
log "Перезапуск Supervisor..."
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart earth-day-queue:*

log "Очистка OPcache..."
php artisan opcache:clear 2>/dev/null || true

# --- Проверка ---
log "Проверка статуса..."
php artisan about | head -20

log ""
log "========================================="
log " Деплой завершён успешно! "
log "========================================="
log "Откройте: https://earthday.ru"
log "Админка: https://earthday.ru/admin"
