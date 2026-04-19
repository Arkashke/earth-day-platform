<template>
  <div>
    <InertiaHead title="Главная" />

    <!-- HERO -->
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <div class="hero-badge">🌍 Earth Day Platform</div>
          <h1>День Земли —<br>каждый день!</h1>
          <p class="hero-quote">
            «День Земли — это не один день в году.<br>
            Это образ жизни, мышления и ответственности.»
          </p>
          <div class="hero-actions">
            <InertiaLink href="/register" class="btn btn-accent btn-lg">
              🚀 Зарегистрировать проект
            </InertiaLink>
            <InertiaLink href="/partners" class="btn btn-outline btn-lg">
              🤝 Стать партнёром
            </InertiaLink>
            <InertiaLink href="/events" class="btn btn-outline btn-lg">
              📅 Участвовать в съезде
            </InertiaLink>
          </div>
        </div>
      </div>
    </section>

    <!-- STATS -->
    <section style="padding: 0 0 48px;">
      <div class="container">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number">{{ stats.participants }}+</div>
            <div class="stat-label">Участников (юр. лиц)</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ stats.projects }}+</div>
            <div class="stat-label">Проектов инициатив</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ stats.partners }}</div>
            <div class="stat-label">Партнёров</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ANNOUNCEMENTS -->
    <section class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">📅 Ближайшие события</h2>
          <p class="section-subtitle">Анонсы презентаций, съездов и вебинаров</p>
        </div>
        <div class="cards-grid">
          <div v-for="event in events" :key="event.id" class="project-card">
            <div class="project-card__image" :style="{ background: event.color || '#e8f5e9' }">
              <div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:48px;">
                {{ event.icon }}
              </div>
            </div>
            <div class="project-card__body">
              <span class="tag" style="margin-bottom:12px;">{{ event.type }}</span>
              <h3 class="project-card__title">{{ event.title }}</h3>
              <p class="project-card__excerpt">{{ event.description }}</p>
              <div style="margin-top:12px;font-size:13px;color:var(--color-text-muted);">
                📅 {{ event.date }}
              </div>
            </div>
            <div class="project-card__footer">
              <span>{{ event.location }}</span>
              <InertiaLink :href="event.url" class="btn btn-primary btn-sm">Подробнее</InertiaLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- INITIATIVES CAROUSEL -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">✨ Лучшие инициативы</h2>
          <p class="section-subtitle">Проекты, одобренные модераторами платформы</p>
        </div>
        <div class="cards-grid">
          <div v-for="project in featuredProjects" :key="project.id" class="project-card">
            <img :src="project.image" :alt="project.title" class="project-card__image" />
            <div class="project-card__body">
              <div class="project-card__org">
                <img :src="project.org_logo" class="project-card__org-logo" :alt="project.org_name" />
                <span class="project-card__org-name">{{ project.org_name }}</span>
              </div>
              <h3 class="project-card__title">{{ project.title }}</h3>
              <p class="project-card__excerpt">{{ project.excerpt }}</p>
              <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;">
                <span class="tag">{{ project.nomination }}</span>
                <span class="tag" style="background:#e3f2fd;color:#1565c0;">📍 {{ project.region }}</span>
              </div>
            </div>
            <div class="project-card__footer">
              <span>{{ project.date }}</span>
              <InertiaLink :href="project.url" class="btn btn-primary btn-sm">Подробнее →</InertiaLink>
            </div>
          </div>
        </div>
        <div style="text-align:center;margin-top:40px;">
          <InertiaLink href="/catalog" class="btn btn-secondary btn-lg">
            Смотреть все проекты →
          </InertiaLink>
        </div>
      </div>
    </section>

    <!-- HOW TO PARTICIPATE -->
    <section class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">🚀 Как стать участником</h2>
          <p class="section-subtitle">Только для юридических лиц — регистрация бесплатна</p>
        </div>
        <div class="grid-3" style="margin-bottom:40px;">
          <div v-for="(step, i) in steps" :key="i" style="text-align:center;padding:24px;">
            <div style="width:64px;height:64px;background:var(--color-primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:900;margin:0 auto 16px;">
              {{ i + 1 }}
            </div>
            <h4 style="font-family:var(--font-heading);font-size:18px;margin-bottom:8px;">{{ step.title }}</h4>
            <p style="font-size:14px;color:var(--color-text-secondary);">{{ step.desc }}</p>
          </div>
        </div>
        <div style="text-align:center;">
          <InertiaLink href="/participate" class="btn btn-primary btn-lg">
            Подробная инструкция →
          </InertiaLink>
        </div>
      </div>
    </section>

    <!-- MAP PREVIEW -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">🗺️ География проектов</h2>
          <p class="section-subtitle">Инициативы участников по всей России</p>
        </div>
        <div style="background:#e8f5e9;border-radius:var(--radius-lg);height:400px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px;">
          <div style="font-size:64px;">🗺️</div>
          <p style="color:var(--color-text-secondary);">Яндекс.Карты с метками проектов</p>
          <InertiaLink href="/catalog?map=true" class="btn btn-primary">
            Открыть карту →
          </InertiaLink>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section style="background:var(--color-primary-dark);padding:72px 0;color:white;text-align:center;">
      <div class="container">
        <h2 style="font-family:var(--font-heading);font-size:36px;font-weight:800;margin-bottom:16px;">
          Присоединяйтесь к сообществу!
        </h2>
        <p style="font-size:18px;opacity:0.9;margin-bottom:36px;max-width:600px;margin-left:auto;margin-right:auto;">
          Зарегистрируйте свою организацию и получите официальный сертификат участника проекта «День Земли — каждый день!»
        </p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
          <InertiaLink href="/register" class="btn btn-accent btn-lg">Зарегистрироваться</InertiaLink>
          <InertiaLink href="/contacts" class="btn btn-outline btn-lg">Связаться с нами</InertiaLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
defineProps({
  stats: {
    type: Object,
    default: () => ({ participants: 0, projects: 0, partners: 0 }),
  },
  events: {
    type: Array,
    default: () => [],
  },
  featuredProjects: {
    type: Array,
    default: () => [],
  },
  steps: {
    type: Array,
    default: () => [
      { title: 'Регистрация', desc: 'Создайте личный кабинет юридического лица на платформе' },
      { title: 'Подача проекта', desc: 'Выберите номинацию и опишите свою экологическую инициативу' },
      { title: 'Модерация', desc: 'После проверки модератором ваш проект появится в каталоге' },
      { title: 'Сертификат', desc: 'Получите официальный сертификат участника в электронном виде' },
      { title: 'Публикация', desc: 'Ваш проект станет доступен в каталоге и на карте России' },
      { title: 'Участие в съезде', desc: 'Посещайте ежегодный съезд и расширяйте деловые контакты' },
    ],
  },
});
</script>
