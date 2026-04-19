<template>
  <div>
    <InertiaHead title="Личный кабинет" />

    <div style="background:#1a1a1a;padding:48px 0 32px;color:white;">
      <div class="container">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:8px;">
          <img
            :src="$page.props.auth?.user?.organization?.logo || '/img/default-org.svg'"
            style="width:64px;height:64px;border-radius:12px;object-fit:cover;border:2px solid rgba(255,255,255,.2);"
            :alt="$page.props.auth?.user?.organization?.name"
          />
          <div>
            <h1 style="font-family:var(--font-heading);font-size:28px;font-weight:800;margin-bottom:4px;">
              {{ $page.props.auth?.user?.organization?.name }}
            </h1>
            <p style="opacity:0.7;font-size:14px;">
              Личный кабинет участника
            </p>
          </div>
          <div style="margin-left:auto;text-align:right;">
            <span class="project-card__badge badge-published" style="font-size:13px;padding:6px 14px;">
              ✓ Активен
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="container" style="padding-top:32px;padding-bottom:80px;">
      <!-- Tabs -->
      <div class="tabs">
        <button class="tab-btn" :class="{ active: activeTab === 'projects' }" @click="activeTab = 'projects'">
          📋 Проекты ({{ projects.length }})
        </button>
        <button class="tab-btn" :class="{ active: activeTab === 'profile' }" @click="activeTab = 'profile'">
          🏢 Профиль
        </button>
        <button class="tab-btn" :class="{ active: activeTab === 'finance' }" @click="activeTab = 'finance'">
          💳 Финансы
        </button>
        <button class="tab-btn" :class="{ active: activeTab === 'certificates' }" @click="activeTab = 'certificates'">
          🏆 Сертификаты
        </button>
        <button class="tab-btn" :class="{ active: activeTab === 'events' }" @click="activeTab = 'events'">
          📅 Мероприятия
        </button>
        <button class="tab-btn" :class="{ active: activeTab === 'notifications' }" @click="activeTab = 'notifications'">
          🔔 Уведомления ({{ unreadCount }})
        </button>
      </div>

      <!-- === Projects Tab === -->
      <div v-if="activeTab === 'projects'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
          <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;">Мои проекты</h2>
          <InertiaLink href="/dashboard/projects/create" class="btn btn-primary">
            + Создать проект
          </InertiaLink>
        </div>

        <div v-if="projects.length === 0" class="alert alert-info">
          У вас пока нет проектов. Нажмите «Создать проект», чтобы подать заявку на участие.
        </div>

        <div v-else>
          <div class="table-wrapper">
            <table class="table">
              <thead>
                <tr>
                  <th>Название</th>
                  <th>Номинация</th>
                  <th>Статус</th>
                  <th>Дата подачи</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="project in projects" :key="project.id">
                  <td>
                    <strong>{{ project.title }}</strong>
                  </td>
                  <td>{{ project.nomination }}</td>
                  <td>
                    <span :class="`project-card__badge badge-${project.status_key}`">
                      {{ project.status }}
                    </span>
                  </td>
                  <td>{{ project.submitted_at }}</td>
                  <td>
                    <div style="display:flex;gap:8px;">
                      <InertiaLink :href="`/projects/${project.id}`" class="btn btn-sm btn-secondary">👁 Просмотр</InertiaLink>
                      <InertiaLink v-if="project.can_edit" :href="`/dashboard/projects/${project.id}/edit`" class="btn btn-sm btn-outline">✏️ Редактировать</InertiaLink>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- === Profile Tab === -->
      <div v-if="activeTab === 'profile'">
        <div class="grid-2">
          <div>
            <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;margin-bottom:24px;">
              Данные организации
            </h2>
            <form @submit.prevent="saveProfile">
              <div class="form-group">
                <label class="form-label">Полное наименование <span class="required">*</span></label>
                <input v-model="profile.full_name" type="text" class="form-control" required />
              </div>
              <div class="form-group">
                <label class="form-label">Сокращённое наименование</label>
                <input v-model="profile.short_name" type="text" class="form-control" />
              </div>
              <div class="grid-2">
                <div class="form-group">
                  <label class="form-label">ИНН <span class="required">*</span></label>
                  <input v-model="profile.inn" type="text" class="form-control" maxlength="10" required />
                </div>
                <div class="form-group">
                  <label class="form-label">КПП</label>
                  <input v-model="profile.kpp" type="text" class="form-control" maxlength="9" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Юридический адрес</label>
                <input v-model="profile.legal_address" type="text" class="form-control" />
              </div>
              <div class="form-group">
                <label class="form-label">Фактический адрес</label>
                <input v-model="profile.actual_address" type="text" class="form-control" />
              </div>
              <div class="form-group">
                <label class="form-label">Телефон <span class="required">*</span></label>
                <input v-model="profile.phone" type="tel" class="form-control" required />
              </div>
              <div class="form-group">
                <label class="form-label">Сайт</label>
                <input v-model="profile.website" type="url" class="form-control" placeholder="https://" />
              </div>
              <div class="form-group">
                <label class="form-label">Логотип</label>
                <input type="file" accept="image/*" class="form-control" />
                <p class="form-hint">Рекомендуемый размер: 200×200 px, PNG или JPG</p>
              </div>
              <button type="submit" class="btn btn-primary">💾 Сохранить изменения</button>
            </form>
          </div>
          <div>
            <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;margin-bottom:24px;">
              Контактные лица
            </h2>
            <div v-for="contact in profile.contacts" :key="contact.id" style="background:white;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:16px;margin-bottom:12px;">
              <strong>{{ contact.name }}</strong> — {{ contact.position }}<br>
              <span style="font-size:13px;color:var(--color-text-secondary);">
                📞 {{ contact.phone }} &nbsp; ✉️ {{ contact.email }}
              </span>
            </div>
            <button class="btn btn-secondary btn-sm">+ Добавить контакт</button>

            <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;margin-top:40px;margin-bottom:24px;">
              Аккаунт
            </h2>
            <div class="form-group">
              <label class="form-label">Email</label>
              <input :value="$page.props.auth?.user?.email" type="email" class="form-control" disabled />
            </div>
            <InertiaLink href="/dashboard/settings" class="btn btn-secondary btn-sm">
              🔒 Изменить пароль
            </InertiaLink>
          </div>
        </div>
      </div>

      <!-- === Finance Tab === -->
      <div v-if="activeTab === 'finance'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
          <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;">Финансы</h2>
          <InertiaLink href="/dashboard/orders/create" class="btn btn-primary">🛒 Конструктор заказа</InertiaLink>
        </div>

        <div class="grid-3" style="margin-bottom:32px;">
          <div class="stat-card" style="text-align:left;">
            <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:4px;">Текущий пакет</div>
            <div style="font-family:var(--font-heading);font-size:20px;font-weight:700;color:var(--color-primary);">
              {{ currentPackage || 'Базовый' }}
            </div>
          </div>
          <div class="stat-card" style="text-align:left;">
            <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:4px;">Оплачено за всё время</div>
            <div style="font-family:var(--font-heading);font-size:20px;font-weight:700;">
              {{ totalPaid }} ₽
            </div>
          </div>
          <div class="stat-card" style="text-align:left;">
            <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:4px;">Активных опций</div>
            <div style="font-family:var(--font-heading);font-size:20px;font-weight:700;">
              {{ activeOptions }}
            </div>
          </div>
        </div>

        <h3 style="font-family:var(--font-heading);font-size:18px;margin-bottom:16px;">История платежей</h3>
        <div class="table-wrapper">
          <table class="table">
            <thead>
              <tr>
                <th>№</th>
                <th>Дата</th>
                <th>Описание</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Документы</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orders" :key="order.id">
                <td>#{{ order.id }}</td>
                <td>{{ order.date }}</td>
                <td>{{ order.description }}</td>
                <td><strong>{{ order.amount }} ₽</strong></td>
                <td>
                  <span :class="`project-card__badge badge-${order.status_key}`">
                    {{ order.status }}
                  </span>
                </td>
                <td>
                  <a v-if="order.invoice_url" :href="order.invoice_url" class="btn btn-sm btn-outline">📄 Счёт</a>
                  <a v-if="order.receipt_url" :href="order.receipt_url" class="btn btn-sm btn-outline">🧾 Квитанция</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- === Certificates Tab === -->
      <div v-if="activeTab === 'certificates'">
        <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;margin-bottom:24px;">Мои сертификаты</h2>
        <div class="grid-3">
          <div
            v-for="cert in certificates"
            :key="cert.id"
            style="background:white;border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:24px;text-align:center;box-shadow:var(--shadow-sm);"
          >
            <div style="font-size:64px;margin-bottom:16px;">🏆</div>
            <h4 style="font-family:var(--font-heading);margin-bottom:8px;">{{ cert.year }}</h4>
            <p style="font-size:14px;color:var(--color-text-secondary);margin-bottom:4px;">{{ cert.nomination }}</p>
            <p style="font-size:13px;color:var(--color-text-muted);margin-bottom:16px;">{{ cert.project }}</p>
            <div style="margin-bottom:16px;">
              <span class="project-card__badge badge-published">Действителен</span>
            </div>
            <div style="font-size:11px;color:var(--color-text-muted);margin-bottom:12px;">
              № {{ cert.number }}
            </div>
            <a :href="cert.download_url" class="btn btn-primary btn-sm btn-block">
              ⬇️ Скачать PDF
            </a>
          </div>
        </div>
      </div>

      <!-- === Events Tab === -->
      <div v-if="activeTab === 'events'">
        <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;margin-bottom:24px;">
          Мои регистрации на съезд
        </h2>
        <div v-for="reg in eventRegistrations" :key="reg.id" style="background:white;border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:20px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
          <div>
            <h4 style="font-family:var(--font-heading);">{{ reg.session }}</h4>
            <p style="font-size:13px;color:var(--color-text-secondary);">
              📅 {{ reg.date }} &nbsp; 🕐 {{ reg.time }} &nbsp; 📍 {{ reg.venue }}
            </p>
          </div>
          <div style="display:flex;gap:8px;align-items:center;">
            <a :href="reg.ics_url" class="btn btn-sm btn-secondary">📅 .ics</a>
            <button class="btn btn-sm" style="background:#ffebee;color:var(--color-danger);" @click="cancelRegistration(reg.id)">
              ✕ Отменить
            </button>
          </div>
        </div>
        <InertiaLink href="/events/2026/congress/registration" class="btn btn-primary" style="margin-top:16px;">
          📋 Зарегистрироваться на сессии
        </InertiaLink>
      </div>

      <!-- === Notifications Tab === -->
      <div v-if="activeTab === 'notifications'">
        <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;margin-bottom:24px;">Уведомления</h2>
        <div v-for="notif in notifications" :key="notif.id" style="background:white;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:16px;margin-bottom:10px;display:flex;gap:12px;align-items:flex-start;">
          <div style="font-size:24px;">{{ notif.icon }}</div>
          <div style="flex:1;">
            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
              <strong>{{ notif.title }}</strong>
              <span style="font-size:12px;color:var(--color-text-muted);">{{ notif.time }}</span>
            </div>
            <p style="font-size:14px;color:var(--color-text-secondary);">{{ notif.body }}</p>
          </div>
          <div v-if="!notif.read" style="width:8px;height:8px;background:var(--color-primary);border-radius:50%;flex-shrink:0;margin-top:6px;"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  organization: Object,
  projects: { type: Array, default: () => [] },
  orders: { type: Array, default: () => [] },
  certificates: { type: Array, default: () => [] },
  eventRegistrations: { type: Array, default: () => [] },
  notifications: { type: Array, default: () => [] },
  currentPackage: { type: String, default: '' },
  totalPaid: { type: Number, default: 0 },
  activeOptions: { type: Number, default: 0 },
  unreadCount: { type: Number, default: 0 },
});

const activeTab = ref('projects');

const profile = ref({
  full_name: 'ООО «ЭкоСфера»',
  short_name: 'ЭкоСфера',
  inn: '7712345678',
  kpp: '771201001',
  legal_address: 'г. Москва, ул. Зелёная, д. 10',
  actual_address: 'г. Москва, ул. Зелёная, д. 10',
  phone: '+7 (495) 111-22-33',
  website: 'https://ecosfera.ru',
  contacts: [
    { id: 1, name: 'Иванов Иван Иванович', position: 'Директор по устойчивому развитию', phone: '+7 (495) 111-22-33', email: 'ivanov@ecosfera.ru' },
    { id: 2, name: 'Петрова Мария Сергеевна', position: 'PR-менеджер', phone: '+7 (495) 111-22-34', email: 'petrova@ecosfera.ru' },
  ],
});

function saveProfile() {
  // Отправка формы через Inertia
}
function cancelRegistration(id) {
  if (confirm('Отменить регистрацию на эту сессию?')) {
    // Отправка через Inertia
  }
}
</script>
